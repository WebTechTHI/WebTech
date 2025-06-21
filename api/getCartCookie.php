<?php
// Dies kommt ganz an den Anfang der Datei

// Prüfen, ob der Nutzer die Cookies nicht akzeptiert hat
if (!isset($_COOKIE['cookie_consent']) || $_COOKIE['cookie_consent'] !== 'accepted') {
    // Wenn keine Zustimmung vorliegt, einen Fehler zurückgeben
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Bitte akzeptieren Sie die Cookies, um den Warenkorb zu nutzen.',
        'needs_consent' => true // Ein Flag, das wir im JS nutzen können
    ]);
    exit; // Wichtig: Skriptausführung hier beenden!
}
// api/getCartCookie.php

session_start(); // Session wird hier nicht mehr für den Warenkorb, aber evtl. für andere Dinge (Login) benötigt
header('Content-Type: application/json');

require_once __DIR__ . '/../db_verbindung.php';

$cookieName = 'mlr_cart';
$cartData = [];

// Warenkorb aus Cookie lesen
if (isset($_COOKIE[$cookieName])) {
    $cartData = json_decode($_COOKIE[$cookieName], true);
}

if (empty($cartData)) {
    echo json_encode(['status' => 'success', 'cart' => []]);
    exit;
}

$cartWithDetails = [];

// Alle Produkt-IDs aus dem Warenkorb sammeln
$productIds = array_keys($cartData);
$placeholders = str_repeat('?,', count($productIds) - 1) . '?';

// SQL-Query bleibt identisch
$sql = "
    SELECT 
        p.product_id, p.name, p.price,
        (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
    FROM product p
    WHERE p.product_id IN ($placeholders)
";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    $types = str_repeat('i', count($productIds));
    mysqli_stmt_bind_param($stmt, $types, ...$productIds);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    while ($product = mysqli_fetch_assoc($result)) {
        $productId = $product['product_id'];
        $quantity = $cartData[$productId]; // Menge aus unserem Cookie-Array holen
        
        $cartWithDetails[] = [
            'id' => $product['product_id'],
            'name' => $product['name'],
            'price' => floatval($product['price']),
            'image' => $product['image'] ?? '/assets/images/placeholder.jpg',
            'quantity' => $quantity
        ];
    }
    
    mysqli_stmt_close($stmt);
    echo json_encode(['status' => 'success', 'cart' => $cartWithDetails]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Fehler beim Laden der Produktdaten']);
}
?>