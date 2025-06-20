<?php
require_once "model/RegistrationModel.php";
require_once "model/CartModel.php"; // Include CartModel

class RegistrationController
{
    public function handleRequest()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $fehlermeldung = null;
        $erfolgsmeldung = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // ==> Validierung in JavaScript
            $username = trim((string) $_POST["fromusernameregistration"]); // Ensure string casting for trim
            $password = $_POST["fromuserpasswordregistration"];

            $registrationModel = new RegistrationModel(); // Renamed to avoid conflict
            $result = $registrationModel->registerUser($username, $password);

            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];
            } else {
                // Registration successful, new user_id is in $result["success"]
                // Populate user session
                $_SESSION['user'] = $registrationModel->getUserData($result["success"]);

                // Merge guest cart into DB cart
                if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
                    $cartModel = new CartModel();
                    // Ensure user_id is correctly fetched for the new user
                    // $_SESSION['user']['user_id'] should be populated by getUserData()
                    if (isset($_SESSION['user']['user_id'])) {
                        $userId = $_SESSION['user']['user_id'];

                        foreach ($_SESSION['guest_cart'] as $item) {
                            if (isset($item['id']) && isset($item['quantity'])) {
                                // Quantities from guest cart should be positive.
                                // The CartModel's addItemOrUpdateQuantity method also checks this.
                                if ($item['quantity'] > 0) {
                                    $merged = $cartModel->addItemOrUpdateQuantity($userId, $item['id'], $item['quantity']);
                                    if (!$merged) {
                                        // Optional: Log error if merging a specific item failed
                                        error_log("Failed to merge item ID {$item['id']} for new user ID {$userId} during registration.");
                                    }
                                }
                            }
                        }
                        // Clear the guest cart after attempting to merge
                        unset($_SESSION['guest_cart']);
                    } else {
                        // This case should ideally not happen if getUserData works correctly
                        error_log("User ID not found in session after registration for user: " . $result["success"]);
                    }
                }

                $erfolgsmeldung = "Registrierung hat geklappt! Benutzer ID ist:  " . htmlspecialchars($result['success']);
            }
        }
        require "view/RegistrationView.php";
    }
}