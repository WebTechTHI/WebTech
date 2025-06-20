<?php
require_once "model/LoginModel.php";
require_once "model/CartModel.php"; // Include CartModel

class LoginController
{
    public function handleRequest()
    {
        // Ensure session is started. If it's started globally (e.g. in a bootstrap file or main controller), this might not be strictly needed here.
        // However, it's good practice if this controller can be hit directly or if global session start isn't guaranteed.
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $fehlermeldung = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["variablefromusername"]);
            $password = $_POST["variableformpassword"];

            $loginModel = new LoginModel(); // Renamed to avoid conflict with $model if CartModel is also named $model
            $result = $loginModel->checkLogin($username, $password);

            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];
            } else {
                // Login successful, populate user session
                $_SESSION['user'] = $loginModel->getUserData($result["user_id"]);

                // Merge guest cart into DB cart
                if (isset($_SESSION['guest_cart']) && !empty($_SESSION['guest_cart'])) {
                    $cartModel = new CartModel();
                    $userId = $_SESSION['user']['user_id'];

                    foreach ($_SESSION['guest_cart'] as $item) {
                        if (isset($item['id']) && isset($item['quantity'])) {
                            // Quantities from guest cart should be positive, CartModel method also checks this.
                            if ($item['quantity'] > 0) {
                                $merged = $cartModel->addItemOrUpdateQuantity($userId, $item['id'], $item['quantity']);
                                if (!$merged) {
                                    // Optional: Log error if merging a specific item failed
                                    error_log("Failed to merge item ID {$item['id']} for user ID {$userId}.");
                                }
                            }
                        }
                    }
                    // Clear the guest cart after attempting to merge
                    unset($_SESSION['guest_cart']);
                }

                $_SESSION["erfolgsmeldung"] = "Willkommen, " . htmlspecialchars($result['username']) . "!<br>Ihre Benutzer ID lautet: " . $result['user_id'];

                header("Location: /index.php?page=user");
                exit;
            }
        }

        require "view/LoginView.php";
    }
}