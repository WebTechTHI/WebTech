<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// 1. Add Product 1 (qty 2)
$input_1 = json_encode([
    "id" => 1, "product_id" => 1, "name" => "Test Product 1",
    "price" => 10.99, "image" => "img1.jpg", "quantity" => 2
]);
global $argv; $original_argv = $argv;
$argv = ['api/addToCart.php', $input_1];
ob_start(); require 'api/addToCart.php'; ob_get_clean();

// 2. Add Product 1 again (qty 3) - should update quantity
$input_2 = json_encode([
    "id" => 1, "product_id" => 1, "name" => "Test Product 1",
    "price" => 10.99, "image" => "img1.jpg", "quantity" => 3
]);
$argv = ['api/addToCart.php', $input_2];
ob_start(); require 'api/addToCart.php'; $api_output_2 = ob_get_clean();
$argv = $original_argv;

echo "API Output (Update Product 1): " . trim($api_output_2) . "\n";
echo "Final Session State: " . json_encode($_SESSION) . "\n";
?>
