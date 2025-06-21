<?php
session_start();
require_once __DIR__ . '/../model/WishlistModel.php';

$pid = intval($_POST['product_id'] ?? 0);

if ($pid > 0) {
    WishlistModel::addProduct($pid);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Ungültige Produkt-ID']);
}
