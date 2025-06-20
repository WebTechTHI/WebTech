<?php
require_once 'db_verbindung.php'; // For $conn if CartModel doesn't manage its own
require_once 'model/CartModel.php'; // Assumes CartModel uses global $conn or similar

global $conn; // Ensure $conn is available if CartModel uses it globally.

$userId = isset($argv[1]) ? (int)$argv[1] : null;
if (!$userId) {
    echo json_encode(["status" => "error", "message" => "User ID required"]);
    exit(1);
}

// Check if CartModel class exists
if (!class_exists('CartModel')) {
    echo json_encode(["status" => "error", "message" => "CartModel class not found. Ensure model/CartModel.php is loaded."]);
    exit(1);
}

$cartModel = new CartModel();
// The getCartItems method in CartModel is expected to fetch items with product details (name, price, image)
$cartItems = $cartModel->getCartItems($userId);

// CartModel->getCartItems already returns items structured with 'id', 'name', 'price', 'image', 'quantity'.
// For consistency with other API JSON outputs, wrap it.
echo json_encode(['status' => 'success', 'items' => $cartItems]);
?>
