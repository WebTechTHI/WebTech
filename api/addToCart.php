<?php
session_start();
require_once '../db_verbindung.php';

// Eingeloggt?
if (!isset($_SESSION['user'])) {
    echo json_encode(['status' => 'error', 'message' => 'Nicht eingeloggt']);
    exit;
}

$userId = $_SESSION['user']['user_id'];

// JSON lesen
$data = json_decode(file_get_contents('php://input'), true);

// Es kommt jetzt: { items: [ { id, name, price, image, quantity } ] }
if (!isset($data['items']) || !is_array($data['items'])) {
    echo json_encode(['status' => 'error', 'message' => 'Keine Items empfangen']);
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
        $newQty = $row['quantity'] + $quantity;
        $updateSql = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("iii", $newQty, $userId, $productId);
        $updateStmt->execute();
    } else {
        $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("iii", $userId, $productId, $quantity);
        $insertStmt->execute();
    }
}

echo json_encode(['status' => 'success']);
