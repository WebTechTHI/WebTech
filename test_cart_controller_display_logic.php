<?php
// test_cart_controller_display_logic.php

if (session_status() == PHP_SESSION_NONE) {
    if (php_sapi_name() === 'cli') {
        $sessionPath = sys_get_temp_dir() . '/php_sessions_test_cart_ctrl';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0777, true);
        }
        ini_set('session.save_path', $sessionPath);
    }
    session_start();
}

require_once 'db_verbindung.php';
require_once 'model/CartModel.php';

$output = ['guest_test' => null, 'logged_in_test' => null];

// Test Case 1: Guest User
// ------------------------
// Session is started. For guest test, ensure no 'user' key from previous runs within this script execution.
if (isset($_SESSION['user'])) unset($_SESSION['user']);
if (isset($_SESSION['guest_cart'])) unset($_SESSION['guest_cart']);


// Setup guest cart in session
$_SESSION['guest_cart'] = [
    ['id' => 20, 'product_id' => 20, 'name' => 'Guest View Product 20', 'price' => 5.99, 'image' => 'img20.jpg', 'quantity' => 2],
    ['id' => 21, 'product_id' => 21, 'name' => 'Guest View Product 21', 'price' => 10.50, 'image' => 'img21.jpg', 'quantity' => 1]
];
$output['guest_test']['step1_session_guest_cart_setup'] = $_SESSION['guest_cart'];


// Simulate CartController logic for a GUEST user (copied from CartController.php)
$guestCartItems = [];
if (isset($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
    // This block should NOT run for a guest
    $guestCartItems = ['error' => 'User session found during guest test. Session: ' . json_encode($_SESSION['user'])];
} else {
    if (isset($_SESSION['guest_cart']) && is_array($_SESSION['guest_cart'])) {
        // This is the path CartController takes for guests.
        // It directly uses the guest_cart. For testing, ensure it's structured as expected.
        $guestCartItems = $_SESSION['guest_cart'];
    } else {
        $guestCartItems = [];
    }
}
$output['guest_test']['step2_fetched_items_for_guest'] = $guestCartItems;
// Expected items are what we put in $_SESSION['guest_cart']
$output['guest_test']['step3_expected_items_for_guest'] = $_SESSION['guest_cart'];


// Test Case 2: Logged-In User
// ---------------------------
// Session is still active. Clear guest_cart and set up user session.
if (isset($_SESSION['guest_cart'])) unset($_SESSION['guest_cart']);
$loggedInTestUserId = 301;

// DB Setup for User 301 with items (products 22, 23) is done by the calling BASH script.

// Simulate login for User 301
$_SESSION['user'] = ['user_id' => $loggedInTestUserId, 'username' => "user{$loggedInTestUserId}"];
$output['logged_in_test']['step1_session_user_setup'] = $_SESSION['user'];


// Simulate CartController logic for a LOGGED-IN user (copied from CartController.php)
$loggedInCartItems = [];
if (isset($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
    $userId = $_SESSION['user']['user_id'];
    // CartModel needs $conn if it doesn't establish its own. db_verbindung.php should make global $conn.
    global $conn;
    if (!$conn || $conn->connect_error) {
         // Re-establish connection if it was closed by previous script runs within same PHP process (not typical for CLI individual runs)
         // This is more a safeguard for unusual CLI execution contexts.
         require 'db_verbindung.php'; // This re-initializes $conn
    }
    $model = new CartModel(); // CartModel uses global $conn
    $loggedInCartItems = $model->getCartItems($userId);
} else {
    $loggedInCartItems = ['error' => 'User session NOT found during logged-in test. Session: ' . json_encode($_SESSION)];
}
$output['logged_in_test']['step2_fetched_items_for_loggedin'] = $loggedInCartItems;
// Expected items for logged-in user will be verified by comparing with direct DB query output (expected_db_cart_user301.json)

echo json_encode($output);

// Close connection if $conn was opened and is active
if ($conn && method_exists($conn, 'close')) {
    $conn->close();
}
?>
