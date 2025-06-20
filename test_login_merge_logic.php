<?php
// test_login_merge_logic.php

// Ensure session is started first thing.
// Crucially, this script will handle its own session state.
if (session_status() == PHP_SESSION_NONE) {
    if (php_sapi_name() === 'cli') {
        // Ensure a consistent save path for CLI test runs
        $sessionPath = sys_get_temp_dir() . '/php_sessions_test_merge';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0777, true);
        }
        ini_set('session.save_path', $sessionPath);
    }
    session_start();
}


// 0. Include necessary models and connection
require_once 'db_verbindung.php';
require_once 'model/CartModel.php';
require_once 'model/LoginModel.php'; // For simulating user data fetch if needed by LoginController logic

$testUserId = 101; // User ID for testing
$output = ['status' => 'running', 'test_user_id' => $testUserId];

// 1. Initialize: Clear previous session state for this test run
$_SESSION = [];
$output['step1_session_cleared'] = true;

// Database setup (user, products, initial user cart) is expected to be done by the calling bash script.
// This script assumes that:
// - User $testUserId exists (e.g., created by setup_db_user.php)
// - Products 7, 8, 9 exist (e.g., created by setup_db_product.php)
// - User $testUserId has an initial cart in DB: Product 8 (qty 3), Product 9 (qty 1) (via setup_db_cart.php)

// 2. Setup Guest Cart in $_SESSION
$_SESSION['guest_cart'] = [
    ['id' => 7, 'product_id' => 7, 'name' => 'Guest Product 7', 'price' => 10.00, 'image' => 'img7.jpg', 'quantity' => 2],
    ['id' => 8, 'product_id' => 8, 'name' => 'Shared Product 8', 'price' => 20.00, 'image' => 'img8.jpg', 'quantity' => 1]
];
$output['step2_guest_cart_setup'] = $_SESSION['guest_cart'];

// 3. Simulate Successful Login (set $_SESSION['user'])
// This simulates what LoginController would do after successful credential check.
// We use LoginModel to fetch user data to make it more realistic.
$loginModel = new LoginModel();
$_SESSION['user'] = $loginModel->getUserData($testUserId); // Fetches from DB

if (empty($_SESSION['user'])) {
    $output['status'] = 'error';
    $output['error_message'] = "Failed to fetch user data for user_id: {$testUserId}. Ensure user exists.";
    echo json_encode($output);
    exit(1);
}
// Ensure user_id is part of the session structure as expected by merge logic
if (!isset($_SESSION['user']['user_id'])) {
     // If getUserData returns user data but not in ['user_id'] structure, adjust or mock
    $_SESSION['user']['user_id'] = $testUserId; // Fallback if structure is different
}

$output['step3_user_session_set'] = $_SESSION['user'];


// 4. Execute the Merge Logic (adapted from LoginController.php)
if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart']) && isset($_SESSION['user']['user_id'])) {
    $cartModel = new CartModel(); // Assumes $conn is globally available or CartModel handles its own connection
    $userIdFromSession = $_SESSION['user']['user_id'];
    $output['step4_merge_started_for_user'] = $userIdFromSession;
    $output['step4_merging_items_details'] = [];

    foreach ($_SESSION['guest_cart'] as $item) {
        if (isset($item['id']) && isset($item['quantity']) && $item['quantity'] > 0) {
            $merged_status = $cartModel->addItemOrUpdateQuantity($userIdFromSession, (int)$item['id'], (int)$item['quantity']);
            $output['step4_merging_items_details'][] = ['item_id' => $item['id'], 'guest_qty' => $item['quantity'], 'merged_db_status' => $merged_status];
        }
    }
    unset($_SESSION['guest_cart']);
    $output['step4_guest_cart_cleared_from_session'] = !isset($_SESSION['guest_cart']);
} else {
    $output['step4_merge_skipped_or_condition_fail'] = true;
    $output['debug_conditions'] = [
        'guest_cart_isset' => isset($_SESSION['guest_cart']),
        'guest_cart_empty' => empty($_SESSION['guest_cart'] ?? []),
        'user_id_isset' => isset($_SESSION['user']['user_id'])
    ];
}

$output['status'] = 'completed';
$output['final_session_state_after_merge'] = $_SESSION;
echo json_encode($output);
?>
