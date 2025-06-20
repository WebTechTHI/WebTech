<?php
require_once __DIR__ . '/../model/CartModel.php';

class CartController {
    public function handleRequest() {
        // Ensure session is started, even if potentially handled globally.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $cartItems = []; // Initialize $cartItems to an empty array

        // Check if user is logged in
        if (isset($_SESSION['user']) && isset($_SESSION['user']['user_id'])) {
            // User is logged in, fetch cart from database
            $userId = $_SESSION['user']['user_id'];
            $model = new CartModel(); // Consider instantiating CartModel only if needed
            $cartItems = $model->getCartItems($userId);
        } else {
            // User is a guest, fetch cart from session
            if (isset($_SESSION['guest_cart']) && is_array($_SESSION['guest_cart'])) {
                // Guest cart items are expected to have: id, name, price, image, quantity
                // This structure should be consistent with what CartModel::getCartItems returns
                // and what api/addToCart.php sets for guests.
                $cartItems = $_SESSION['guest_cart'];

                // Optional: Ensure data types, though api/addToCart.php and api/getCart.php should handle this.
                // For example, ensuring price is float and quantity is int.
                // This was more critical in api/getCart.php; here we trust the session data structure.
                // Example:
                // foreach ($cartItems as $key => $item) {
                //     $cartItems[$key]['price'] = (float)($item['price'] ?? 0);
                //     $cartItems[$key]['quantity'] = (int)($item['quantity'] ?? 0);
                //     $cartItems[$key]['id'] = (int)($item['id'] ?? 0);
                // }
            } else {
                // No guest cart in session, or it's not an array
                $cartItems = [];
            }
        }

        include __DIR__ . '/../view/CartView.php';
    }
}
// The authentication redirect has been removed to allow guests.
// The comment "// Sicher prüfen // Du hast nur $_SESSION['user'], keine user_id extra" seems like an old note.
// $_SESSION['user']['user_id'] is the standard way to check for user_id as implemented.