<?php
//RINOR STUBLLA


require_once __DIR__ . '/../db_verbindung.php';
require_once __DIR__ . '/../model/CartModel.php';
require_once __DIR__ . '/../model/CheckoutModel.php'; // Das neue Model

class CheckoutController
{

    public function handleRequest()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Zur SIcherheit ob der Nutzer eingeloggt ist
        if (!isset($_SESSION['user']['user_id'])) {
            $_SESSION['redirect_after_login'] = '/index.php?page=checkout';
            $_SESSION['login_redirect_message'] = "Bitte melden Sie sich an, um zur Kasse zu gehen.";
            header('Location: /index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user']['user_id'];
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartFromDb($userId);

        // Wenn der Warenkorb leer ist, zurückschicken
        if (empty($cartItems)) {
            header('Location: /index.php?page=cart');
            exit;
        }

        // BERECHNUNGEN FÜR DIE ANZEIGE 
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }
        $netto = $subTotal * 100 / 119;
        $tax = $subTotal - $netto;
        $shippingCost = ($subTotal > 29.99) ? 0 : 4.99;
        $total = $subTotal + $shippingCost;

        $hasValidShippingInfo = true;
        if (
            !isset($_SESSION['user']['richtiger_name'])
            || !isset($_SESSION['user']['straße'])
            || !isset($_SESSION['user']['plz'])
            || !isset($_SESSION['user']['stadt'])
            || !isset($_SESSION['user']['email'])
        ) {
            $hasValidShippingInfo = false;
        }

        if (isset($_POST['save_shipping_info'])) {
            require_once __DIR__ . '/../model/UserModel.php';
            $userModel = new UserModel();

            $name = trim($_POST['richtiger_name']);
            $straße = trim($_POST['straße']);
            $plz = trim($_POST['plz']);
            $stadt = trim($_POST['stadt']);
            $email = trim($_POST['email']);
            $userId = $_SESSION['user']['user_id'];

            $updateSuccess = $userModel->updateUserShippingInfo($userId, $name, $straße,$stadt, $plz, $email );

            if ($updateSuccess) {
                $_SESSION['user']['richtiger_name'] = $name;
                $_SESSION['user']['straße'] = $straße;
                $_SESSION['user']['plz'] = $plz;
                $_SESSION['user']['stadt'] = $stadt;
                $_SESSION['user']['email'] = $email;
                header('Location: /index.php?page=checkout');
                exit;
            } else {
                $error = "Ihre Daten konnten nicht gespeichert werden. Bitte versuchen Sie es erneut.";
            }
        }


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $checkoutModel = new CheckoutModel();
            $finalTotal = $total; // Der ursprüngliche Gesamtbetrag, wird geändert falls couponCode aktiv
            $coupon = null;

            // Den gesendeten Gutscheincode aus dem versteckten Feld holen
            $appliedCouponCode = $_POST['applied_coupon_code'] ?? null;

            // Gutschein serverseitig erneut validieren!
            if (!empty($appliedCouponCode)) {
                $coupon = $checkoutModel->getValidCouponByCode($appliedCouponCode);
                if ($coupon) {
                    // Gutschein ist gültig, berechne den neuen Gesamtbetrag
                    $percent = $coupon['percent'];
                    $finalTotal = $total * (1 - ($percent / 100));
                    $finalTotal = round($finalTotal, 2); // Auf 2 Nachkommastellen runden
                }
            }

            $user = $_SESSION['user'];
            $shippingAddress = ($user['richtiger_name'] ?? 'N/A') . "\n" .
                ($user['straße'] ?? 'N/A') . "\n" .
                ($user['plz'] ?? 'N/A') . ' ' . ($user['stadt'] ?? 'N/A') . "\n";

            // Die ID des Gutscheins (oder null) für die Datenbank vorbereiten
            $couponIdToSave = $coupon ? $coupon['coupon_id'] : null;

            // Wir übergeben den finalen Betrag UND die coupon_id an das Model
            $orderId = $checkoutModel->createOrder($userId, $cartItems, $finalTotal, $shippingAddress, $couponIdToSave);

            if ($orderId) {
                foreach ($cartItems as $cartItem) {
                    $checkoutModel->increaseSales($cartItem['product_id'], $cartItem['quantity']);
                }
                // Bestellung erfolgreich, jetzt den Warenkorb leeren
                $checkoutModel->clearUserCart($userId);
                // Zur "Danke für Ihre Bestellung"-Seite weiterleiten
                header('Location: /index.php?page=order_success&id=' . $orderId);
                exit;
            } else {
                // Fehler bei der Bestellung
                $error = "Bei Ihrer Bestellung ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.";
            }
        }


        include __DIR__ . '/../view/CheckoutView.php';
    }

    //RINOR STUBLLA ENDE

}