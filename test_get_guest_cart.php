<?php
if (php_sapi_name() === 'cli') {
    ini_set('session.save_path', sys_get_temp_dir());
}
session_start();
$_SESSION = []; // Clean start

// 1. Add Product 1 (qty 2 initially, then updated to 5)
$_SESSION['guest_cart'] = [
    ["id" => 1, "name" => "Test Product 1", "price" => 10.99, "image" => "img1.jpg", "quantity" => 5],
    ["id" => 2, "name" => "Test Product 2", "price" => 5.50, "image" => "img2.jpg", "quantity" => 1]
];


// 2. "Call" api/getCart.php
// The $response variable will be populated by getCart.php when included
$response = null;
require 'api/getCart.php'; // This will populate $response due to the modification in getCart.php

echo "API Output (Get Guest Cart): " . json_encode($response) . "\n";
// No need to echo $_SESSION again as getCart.php reads from it.
?>
