<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// 1. Initial session state (simulating previous steps)
// Product 1, qty 5 (from Test 1.4)
// Product 2, qty 1 (from Test 1.3)
$_SESSION['guest_cart'] = [
    ["id" => 1, "product_id" => 1, "name" => "Test Product 1", "price" => 10.99, "image" => "img1.jpg", "quantity" => 5],
    ["id" => 2, "product_id" => 2, "name" => "Test Product 2", "price" => 5.50, "image" => "img2.jpg", "quantity" => 1]
];

// 2. Simulate "zurKasseButton" payload (sync from localStorage)
$checkout_payload_json = json_encode([
    "items" => [
        ["id" => 1, "name" => "Test Product 1", "price" => 10.99, "image" => "img1.jpg", "quantity" => 4], // Qty updated
        ["id" => 3, "name" => "Test Product 3", "price" => 20.00, "image" => "img3.jpg", "quantity" => 1]  // New item
    ]
]);

global $argv; $original_argv = $argv;
$argv = ['api/addToCart.php', $checkout_payload_json];
ob_start(); require 'api/addToCart.php'; $api_output = ob_get_clean();
$argv = $original_argv;

echo "API Output (Checkout Sync): " . trim($api_output) . "\n";
echo "Final Session State: " . json_encode($_SESSION) . "\n";

?>
