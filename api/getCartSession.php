<?php
session_start();
header('Content-Type: application/json');

// Datenbankverbindung einbinden
require_once __DIR__ . '/../db_verbindung.php';

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo json_encode(['status' => 'success', 'cart' => []]);
    exit;
}

$cartWithDetails = [];

// Alle Produkt-IDs aus dem Warenkorb sammeln
$productIds = array_keys($_SESSION['cart']);
$placeholders = str_repeat('?,', count($productIds) - 1) . '?';

// SQL-Query mit MySQLi
$sql = "
    SELECT 
        p.product_id, 
        p.name, 
        p.price,
        (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
    FROM product p
    WHERE p.product_id IN ($placeholders)
    
";

$stmt = mysqli_prepare($conn, $sql);

if ($stmt) {
    // Parameter binden (alle als Integer)
    $types = str_repeat('i', count($productIds));
    mysqli_stmt_bind_param($stmt, $types, ...$productIds);
    
    // Query ausführen
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    // Ergebnisse verarbeiten
    while ($product = mysqli_fetch_assoc($result)) {
        $productId = $product['product_id'];
        $quantity = $_SESSION['cart'][$productId];
        
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