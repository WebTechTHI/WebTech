<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$pid = intval($input['product_id']);
$qty = intval($input['quantity']);

if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

if (isset($_SESSION['cart'][$pid])) {
  $_SESSION['cart'][$pid] += $qty;
} else {
  $_SESSION['cart'][$pid] = $qty;
}

echo json_encode(['status' => 'success']);
