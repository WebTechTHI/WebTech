<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// Add Product 7 (qty 2)
$input_p7 = json_encode([
    "id" => 7, "product_id" => 7, "name" => "Product 7 for Merge",
    "price" => 7.77, "image" => "img7.jpg", "quantity" => 2
]);
global $argv; $original_argv = $argv; // Save original argv
$argv = ['api/addToCart.php', $input_p7];
ob_start(); require 'api/addToCart.php'; ob_get_clean();

// Add Product 8 (qty 1)
$input_p8 = json_encode([
    "id" => 8, "product_id" => 8, "name" => "Product 8 for Merge",
    "price" => 8.88, "image" => "img8.jpg", "quantity" => 1
]);
$argv = ['api/addToCart.php', $input_p8];
ob_start(); require 'api/addToCart.php'; ob_get_clean();
$argv = $original_argv; // Restore original argv

echo "Session after guest cart setup: " . json_encode($_SESSION) . "\n";
// This script needs to output the session ID to be used by the next script.
echo "SESSION_ID:" . session_id() . "\n";
?>
