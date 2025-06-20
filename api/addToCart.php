<?php
// Ensure session is started, and manage CLI-specific settings only if not already handled.
if (session_status() == PHP_SESSION_NONE) {
    if (php_sapi_name() === 'cli') {
        ini_set('session.save_path', sys_get_temp_dir());
    }
    session_start();
}
require_once __DIR__ . '/../db_verbindung.php'; // Use __DIR__ for robust path

// JSON-Eingabe lesen
$data = null;
if (php_sapi_name() === 'cli') {
    // For CLI testing, expect JSON string as the first argument after script name
    // Example: php api/addToCart.php '{"id":1, "quantity":2, ...}'
    if (isset($argv[1])) {
        $data = json_decode($argv[1], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid JSON provided in CLI argument: ' . json_last_error_msg()]);
            exit;
        }
    } else {
        // Or allow piping JSON to STDIN for CLI
        $stdin_json = file_get_contents('php://stdin');
        if (!empty($stdin_json)) {
            $data = json_decode($stdin_json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                 echo json_encode(['status' => 'error', 'message' => 'Invalid JSON provided via STDIN: ' . json_last_error_msg()]);
                 exit;
            }
        }
    }
     // If no CLI arg or STDIN, $data remains null, and existing error handling for $data should catch it.
} else {
    // Web request: read from php://input
    $data = json_decode(file_get_contents('php://input'), true);
}


if (isset($_SESSION['user']['user_id'])) {
    // Eingeloggter Benutzer: Logik für Datenbank
    $userId = $_SESSION['user']['user_id'];

    if (!isset($data['items']) || !is_array($data['items'])) {
        // Hier könnte auch die Logik für einzelne Produktaddition für eingeloggte Benutzer stehen,
        // aber aktuell erwartet das Frontend ein 'items' Array für eingeloggte Benutzer.
        echo json_encode(['status' => 'error', 'message' => 'Keine Items empfangen oder falsches Format für eingeloggte Benutzer.']);
        exit;
    }

    foreach ($data['items'] as $item) {
        $productId = (int)$item['id'];
        $quantity = (int)$item['quantity'];

        // Prüfen ob existiert
        $sql = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $newQty = $row['quantity'] + $quantity; // Annahme: quantity aus $item ist die hinzuzufügende Menge
            if ($newQty <= 0) {
                $deleteSql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
                $deleteStmt = $conn->prepare($deleteSql);
                $deleteStmt->bind_param("ii", $userId, $productId);
                $deleteStmt->execute();
            } else {
                $updateSql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("iii", $newQty, $userId, $productId);
                $updateStmt->execute();
            }
        } else if ($quantity > 0) {
            $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            // Beachten Sie, dass hier Produktname, Preis, Bild nicht gespeichert werden, da sie in der 'products' Tabelle sind.
            // Das Frontend muss diese Informationen für eingeloggte Benutzer ggf. separat laden.
            $insertStmt->bind_param("iii", $userId, $productId, $quantity);
            $insertStmt->execute();
        }
    }
    echo json_encode(['status' => 'success', 'message' => 'Warenkorb aktualisiert (Datenbank).']);

} else {
    // Gastbenutzer: Logik für Session-Warenkorb
    if (!isset($_SESSION['guest_cart'])) {
        $_SESSION['guest_cart'] = [];
    }

    if (isset($data['items']) && is_array($data['items'])) {
        // Mehrere Items (z.B. bei Login oder von localStorage Übernahme)
        foreach ($data['items'] as $item) {
            $productId = (int)$item['id'];
            $quantity = (int)$item['quantity'];
            // Annahme: $item enthält auch name, price, image

            $found = false;
            foreach ($_SESSION['guest_cart'] as $key => $cartItem) {
                if ($cartItem['id'] == $productId) {
                    if ($quantity > 0) {
                        $_SESSION['guest_cart'][$key]['quantity'] = $quantity; // Oder $cartItem['quantity'] + $quantity; je nach Anforderung
                    } else {
                        unset($_SESSION['guest_cart'][$key]); // Entfernen, wenn Menge 0 oder weniger
                    }
                    $found = true;
                    break;
                }
            }
            if (!$found && $quantity > 0) {
                 // Frontend muss sicherstellen, dass alle Details (id, name, price, image, quantity) gesendet werden
                $_SESSION['guest_cart'][] = $item;
            }
        }
        // Re-Indizieren des Arrays, falls Elemente entfernt wurden
        $_SESSION['guest_cart'] = array_values($_SESSION['guest_cart']);
        echo json_encode(['status' => 'success', 'message' => 'Gast-Warenkorb aktualisiert (mehrere Items).']);

    } elseif (isset($data['product_id']) && isset($data['quantity'])) {
        // Einzelnes Produkt hinzufügen/aktualisieren
        $productId = (int)$data['product_id'];
        $quantity = (int)$data['quantity'];
        // Annahme: $data enthält auch name, price, image, falls das Produkt neu hinzugefügt wird

        $found = false;
        foreach ($_SESSION['guest_cart'] as $key => $cartItem) {
            if ($cartItem['id'] == $productId) { // Annahme: 'id' im Gast-Warenkorb entspricht 'product_id'
                $newQuantity = $cartItem['quantity'] + $quantity; // Menge addieren
                if ($newQuantity > 0) {
                    $_SESSION['guest_cart'][$key]['quantity'] = $newQuantity;
                } else {
                    unset($_SESSION['guest_cart'][$key]); // Entfernen, wenn Menge 0 oder weniger
                }
                $found = true;
                break;
            }
        }

        if (!$found && $quantity > 0) {
            // Produkt nicht im Warenkorb, füge es hinzu
            // Frontend muss id, name, price, image, quantity senden
            // $data sollte hier die vollständigen Produktinformationen enthalten
            $_SESSION['guest_cart'][] = [
                'id' => $productId,
                'name' => $data['name'], // Erfordert, dass Frontend dies sendet
                'price' => $data['price'], // Erfordert, dass Frontend dies sendet
                'image' => $data['image'], // Erfordert, dass Frontend dies sendet
                'quantity' => $quantity
            ];
        }
        // Re-Indizieren des Arrays, falls Elemente entfernt wurden
        $_SESSION['guest_cart'] = array_values($_SESSION['guest_cart']);
        echo json_encode(['status' => 'success', 'message' => 'Gast-Warenkorb aktualisiert (einzelnes Item).']);

    } else {
        echo json_encode(['status' => 'error', 'message' => 'Keine Items oder Produkt-ID/Menge empfangen für Gast.']);
        exit;
    }
}
?>
