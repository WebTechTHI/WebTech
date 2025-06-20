<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// 1. Simulate User Login
$_SESSION['user'] = ['user_id' => 100, 'username' => 'user100']; // Add other details if your models need them

// 2. Prepare payload (items array)
$payload_json = json_encode([
    "items" => [
        ["id" => 5, "name" => "User Product 5", "price" => 15.00, "image" => "img5.jpg", "quantity" => 1]
    ]
]);

// 3. Call api/addToCart.php logic
global $argv; $original_argv = $argv;
$argv = ['api/addToCart.php', $payload_json];
ob_start(); require 'api/addToCart.php'; $api_output = ob_get_clean();
$argv = $original_argv;

echo "API Output (Add Logged-in Item): " . trim($api_output) . "\n";

// 4. The script will then be followed by a direct DB query using debug_db.php
?>
