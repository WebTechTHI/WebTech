<?php
// getCart.php ✅
session_start();
require_once '../db_verbindung.php';

if (!isset($_SESSION['user']['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
    exit;
}

$userId = $_SESSION['user']['user_id'];

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
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

echo json_encode(['status' => 'success', 'items' => $items]);
