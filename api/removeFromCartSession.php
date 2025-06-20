<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

// Überprüfen, ob product_id gesendet wurde
if (!isset($input['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID fehlt.']);
    exit;
}

$pid = intval($input['product_id']);

if (isset($_SESSION['cart'][$pid])) {
    // Entferne das Produkt aus dem Warenkorb-Array
    unset($_SESSION['cart'][$pid]);
}

echo json_encode(['status' => 'success', 'message' => 'Produkt entfernt.']);