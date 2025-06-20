<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start for this specific test sequence

// 1. Add First Item (Product 1)
$simulated_json_input_1 = json_encode([
    "id" => 1, "product_id" => 1, "name" => "Test Product 1",
    "price" => 10.99, "image" => "img1.jpg", "quantity" => 2
]);

global $argv;
$original_argv = $argv;
$argv = ['api/addToCart.php', $simulated_json_input_1];
ob_start(); require 'api/addToCart.php'; ob_get_clean(); // Run and discard output

// 2. Add Second Item (Product 2)
$simulated_json_input_2 = json_encode([
    "id" => 2, "product_id" => 2, "name" => "Test Product 2",
    "price" => 5.50, "image" => "img2.jpg", "quantity" => 1
]);

$argv = ['api/addToCart.php', $simulated_json_input_2];
ob_start(); require 'api/addToCart.php'; $api_output_2 = ob_get_clean();
$argv = $original_argv; // Restore original argv

echo "API Output (Add Product 2): " . trim($api_output_2) . "\n";
echo "Final Session State: " . json_encode($_SESSION) . "\n";
?>
