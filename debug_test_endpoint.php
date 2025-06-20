<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once 'db_verbindung.php'; // For CartModel if needed
require_once 'model/CartModel.php';   // For direct model calls
require_once 'model/LoginModel.php';  // For fetching user data

header('Content-Type: application/json');
$response = ['status' => 'error', 'message' => 'Invalid action.'];

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'clear_session':
            $_SESSION = [];
            session_destroy();
            // Re-initialize session for subsequent operations if any
            if (session_status() == PHP_SESSION_NONE) {
                 session_start();
            }
            $response = ['status' => 'success', 'message' => 'Session cleared.'];
            break;

        case 'add_to_guest_cart':
            if (!isset($_SESSION['guest_cart'])) {
                $_SESSION['guest_cart'] = [];
            }
            $item = [
                'id' => (int)$_GET['product_id'],
                'name' => $_GET['name'] ?? 'Test Product',
                'price' => (float)($_GET['price'] ?? 0.0),
                'image' => $_GET['image'] ?? 'test.jpg',
                'quantity' => (int)($_GET['quantity'] ?? 1)
            ];

            $found = false;
            foreach ($_SESSION['guest_cart'] as $key => $cartItem) {
                if ($cartItem['id'] == $item['id']) {
                    // As per api/addToCart.php for single guest item: quantity is added
                    $_SESSION['guest_cart'][$key]['quantity'] += $item['quantity'];
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $_SESSION['guest_cart'][] = $item;
            }
            $_SESSION['guest_cart'] = array_values($_SESSION['guest_cart']); // Re-index
            $response = ['status' => 'success', 'guest_cart' => $_SESSION['guest_cart']];
            break;

        case 'simulate_login':
            // This simulates a successful login and triggers cart merging.
            if (isset($_GET['user_id'])) {
                $userId = (int)$_GET['user_id'];
                $loginModel = new LoginModel(); // Assuming LoginModel can fetch user data
                $_SESSION['user'] = $loginModel->getUserData($userId); // Fetch actual user data

                if (!$_SESSION['user']) {
                     $response = ['status' => 'error', 'message' => "User data not found for user_id: $userId."];
                     break;
                }

                // Cart Merging Logic (simplified from LoginController)
                if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
                    $cartModel = new CartModel();
                    foreach ($_SESSION['guest_cart'] as $guest_item) {
                        if (isset($guest_item['id']) && isset($guest_item['quantity']) && $guest_item['quantity'] > 0) {
                            $cartModel->addItemOrUpdateQuantity($userId, $guest_item['id'], $guest_item['quantity']);
                        }
                    }
                    unset($_SESSION['guest_cart']);
                }
                $response = ['status' => 'success', 'message' => "User $userId logged in, cart merged."];
            } else {
                $response = ['status' => 'error', 'message' => 'user_id not provided for login.'];
            }
            break;

        case 'simulate_registration_merge':
            // This simulates the merge part of registration
            if (isset($_GET['user_id']) && isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
                $userId = (int)$_GET['user_id'];
                $cartModel = new CartModel();
                $mergedItems = [];
                foreach ($_SESSION['guest_cart'] as $guest_item) {
                    if (isset($guest_item['id']) && isset($guest_item['quantity']) && $guest_item['quantity'] > 0) {
                        $cartModel->addItemOrUpdateQuantity($userId, $guest_item['id'], $guest_item['quantity']);
                        $mergedItems[] = $guest_item;
                    }
                }
                // In a real registration, guest_cart would be unset *after* user session is also established.
                // We are only testing the merge part here.
                // unset($_SESSION['guest_cart']);
                $response = ['status' => 'success', 'message' => "Cart merged for new user $userId.", "merged_items" => $mergedItems];
            } else {
                $response = ['status' => 'error', 'message' => 'user_id or guest_cart not available for registration merge.'];
            }
            break;

        default:
            $response = ['status' => 'error', 'message' => 'Unknown action for debug_test_endpoint.'];
            break;
    }
}

echo json_encode($response);
?>
