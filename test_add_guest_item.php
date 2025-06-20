<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start(); // Start session to store guest cart

// Clear the session specifically for this test run to ensure isolation
$_SESSION = [];

$simulated_json_input = json_encode([
    "id" => 1,
    "product_id" => 1,
    "name" => "Test Product 1",
    "price" => 10.99,
    "image" => "img1.jpg",
    "quantity" => 2
]);

// For CLI, make $argv[1] be the JSON payload
// This simulates: php api/addToCart.php 'JSON_PAYLOAD'
global $argv;
$original_argv = $argv; // Store original argv if any
$argv = [];
$argv[0] = 'api/addToCart.php'; // Script name
$argv[1] = $simulated_json_input; // Argument

ob_start();
require 'api/addToCart.php'; // This will now use $argv[1] due to the modification
$api_output = ob_get_clean();
$argv = $original_argv; // Restore original argv

echo "API Output: " . trim($api_output) . "\n";
echo "Final Session State: " . json_encode($_SESSION) . "\n";
?>
