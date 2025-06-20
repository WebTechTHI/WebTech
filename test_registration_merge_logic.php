<?php
// test_registration_merge_logic.php

if (session_status() == PHP_SESSION_NONE) {
    if (php_sapi_name() === 'cli') {
        $sessionPath = sys_get_temp_dir() . '/php_sessions_test_reg_merge';
        if (!is_dir($sessionPath)) {
            mkdir($sessionPath, 0777, true);
        }
        ini_set('session.save_path', $sessionPath);
    }
    session_start();
}

require_once 'db_verbindung.php';
require_once 'model/CartModel.php';
// RegistrationModel is not strictly required here as we manually insert the user for test isolation,
// but in a full controller test, it would be used.
// require_once 'model/RegistrationModel.php';

global $conn; // Needed for manual user insertion

$newUserId = 201;
$newUserEmail = "newuser{$newUserId}@example.com";
$newUserUsername = "newuser{$newUserId}";
$newUserPassword = "password123";

$output = ['status' => 'running', 'test_user_id' => $newUserId];

// 1. Initialize: Clear previous session.
// (Bash will call delete_db_user.php $newUserId as a pre-cleanup step)
// (Bash will call setup_db_product.php for products if needed)
$_SESSION = [];
$output['step1_session_cleared'] = true;

// 2. Setup Guest Cart in $_SESSION
$_SESSION['guest_cart'] = [
    ['id' => 10, 'product_id' => 10, 'name' => 'Guest Product 10', 'price' => 15.00, 'image' => 'img10.jpg', 'quantity' => 1],
    ['id' => 11, 'product_id' => 11, 'name' => 'Guest Product 11', 'price' => 25.00, 'image' => 'img11.jpg', 'quantity' => 3]
];
$output['step2_guest_cart_setup'] = $_SESSION['guest_cart'];

// 3. Simulate Successful Registration (Create user and set $_SESSION['user'])
// Manual user insertion for test isolation & to control user_id for the test:
$passwordHash = password_hash($newUserPassword, PASSWORD_DEFAULT);
// Ensure the INSERT matches the user table schema (user_id, email, username, password)
// This was determined/debugged in previous login test.
$stmt_insert_user = $conn->prepare("INSERT INTO user (user_id, email, username, password) VALUES (?, ?, ?, ?)");
if (!$stmt_insert_user) {
    $output['status'] = 'error';
    $output['error_message'] = "DB Prepare failed (insert user): " . $conn->error;
    echo json_encode($output);
    exit(1);
}
$stmt_insert_user->bind_param("isss", $newUserId, $newUserEmail, $newUserUsername, $passwordHash);
$user_created_successfully = $stmt_insert_user->execute();
$stmt_insert_user->close();

if (!$user_created_successfully) {
    $output['status'] = 'error';
    // It's possible the user exists if pre-cleanup failed or was skipped.
    // Check if user exists to provide a more specific message.
    $stmt_check = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
    $stmt_check->bind_param("i", $newUserId);
    $stmt_check->execute();
    $user_exists = $stmt_check->get_result()->num_rows > 0;
    $stmt_check->close();
    if ($user_exists) {
        $output['error_message'] = "Test user {$newUserId} already existed. Pre-cleanup might be needed or failed.";
    } else {
        $output['error_message'] = "Failed to create test user {$newUserId} directly. DB Error: " . $conn->error;
    }
    echo json_encode($output);
    exit(1);
}
$output['step3_user_created_in_db'] = true;

// Simulate session population as RegistrationController would do:
$_SESSION['user'] = [
    'user_id' => $newUserId,
    'username' => $newUserUsername,
    // Other fields like email might be added by RegistrationModel->getUserData()
    'email' => $newUserEmail
];
$output['step3_user_session_set'] = $_SESSION['user'];


// 4. Execute the Merge Logic (adapted from RegistrationController.php)
if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart']) && isset($_SESSION['user']['user_id'])) {
    $cartModel = new CartModel();
    $userId = $_SESSION['user']['user_id'];
    $output['step4_merge_started_for_user'] = $userId;
    $output['step4_merging_items_details'] = [];

    foreach ($_SESSION['guest_cart'] as $item) {
        if (isset($item['id']) && isset($item['quantity']) && $item['quantity'] > 0) {
            $merged_status = $cartModel->addItemOrUpdateQuantity($userId, (int)$item['id'], (int)$item['quantity']);
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

if ($conn) $conn->close();
?>
