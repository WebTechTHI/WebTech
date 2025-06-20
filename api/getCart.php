<?php
// getCart.php

// Ensure session is started, and manage CLI-specific settings only if not already handled.
if (session_status() == PHP_SESSION_NONE) {
    if (php_sapi_name() === 'cli') {
        ini_set('session.save_path', sys_get_temp_dir());
    }
    session_start();
}
require_once __DIR__ . '/../db_verbindung.php'; // Use __DIR__ for robust path

// In CLI mode, if this script is the one being run directly, we echo.
// If it's included, the including script should handle echoing.
$is_direct_cli_execution = (php_sapi_name() === 'cli' && isset($argv[0]) && realpath($argv[0]) === realpath(__FILE__));

if (isset($_SESSION['user']['user_id'])) {
    // Eingeloggter Benutzer: Warenkorb aus der Datenbank abrufen
    $userId = $_SESSION['user']['user_id'];
    $items = [];

    $sql = "
        SELECT
            c.quantity,
            p.product_id AS id,
            p.name,
            p.price,
            (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
        FROM cart c
        JOIN product p ON c.product_id = p.product_id
        WHERE c.user_id = ?
    ";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $row['id'] = (int)$row['id']; // Ensure id is int
            $row['price'] = (float)$row['price'];
            $row['quantity'] = (int)$row['quantity']; // Ensure quantity is int
            $items[] = $row;
        }
        $stmt->close();
        $response = ['status' => 'success', 'items' => $items];
    } else {
        error_log("SQL prepare failed (getCart for user): " . $conn->error);
        $response = ['status' => 'error', 'message' => 'Fehler beim Abrufen des Warenkorbs (DB).'];
    }
    if ($conn) $conn->close();
} else {
    // Gastbenutzer: Warenkorb aus der Session abrufen
    if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
        $guestItems = [];
        foreach ($_SESSION['guest_cart'] as $item) {
            // Ensure consistent data types, matching what api/addToCart sets and DB returns
            $guestItems[] = [
                'id' => (int)$item['id'],
                'name' => (string)($item['name'] ?? ''),
                'price' => (float)($item['price'] ?? 0.0),
                'image' => (string)($item['image'] ?? ''),
                'quantity' => (int)($item['quantity'] ?? 0)
            ];
        }
        $response = ['status' => 'success', 'items' => $guestItems];
    } else {
        $response = ['status' => 'success', 'items' => []]; // Leerer Warenkorb für Gast
    }
}

if ($is_direct_cli_execution || php_sapi_name() !== 'cli') {
    echo json_encode($response);
    if (php_sapi_name() === 'cli') echo PHP_EOL;
}
// If included in CLI, the $response variable is available to the including script.
?>
