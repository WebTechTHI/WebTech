<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// 1. Simulate User Login
// Make sure user 100 exists in DB (done by previous test)
// And has item 5, qty 1 in cart (done by previous test)
$_SESSION['user'] = ['user_id' => 100, 'username' => 'user100'];

// 2. "Call" api/getCart.php
$response = null;
require 'api/getCart.php'; // This will populate $response

echo "API Output (Get Logged-in Cart): " . json_encode($response) . "\n";
?>
