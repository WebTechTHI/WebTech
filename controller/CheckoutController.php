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

        // --- SICHERHEIT: Ist der Nutzer eingeloggt? ---
        if (!isset($_SESSION['user']['user_id'])) {
            $_SESSION['redirect_after_login'] = '/index.php?page=checkout';
            $_SESSION['login_redirect_message'] = "Bitte melden Sie sich an, um zur Kasse zu gehen.";
            header('Location: /index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user']['user_id'];
        $cartModel = new CartModel();
        $cartItems = $cartModel->getCartFromDb($userId);

        // --- Wenn der Warenkorb leer ist, zurückschicken ---
        if (empty($cartItems)) {
            header('Location: /index.php?page=cart');
            exit;
        }

        // --- BERECHNUNGEN FÜR DIE ANZEIGE ---
        $subTotal = 0;
        foreach ($cartItems as $item) {
            $subTotal += $item['price'] * $item['quantity'];
        }
        $netto = $subTotal * 100 / 119;
        $tax = $subTotal - $netto;
        $shippingCost = ($subTotal > 29.99) ? 0 : 4.99;
        $total = $subTotal + $shippingCost;



        // --- ANFRAGE VERARBEITEN ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // --- BESTELLUNG AUFGEBEN ---

            // HIER ERSTELLEN WIR DIE LIEFERADRESSE AUS DEINEN VORHANDENEN DATEN
            $user = $_SESSION['user'];

          
            $shippingAddress = ($user['richtiger_name'] ?? 'N/A') . "\n" .
                ($user['straße'] ?? 'N/A') . "\n" .
                ($user['plz'] ?? 'N/A') . ' ' . ($user['stadt'] ?? 'N/A') . "\n";



            $checkoutModel = new CheckoutModel();

            // Wir übergeben die erstellte Adresse an das Model
            $orderId = $checkoutModel->createOrder($userId, $cartItems, $total, $shippingAddress);

            if ($orderId) {
                // Bestellung erfolgreich, jetzt den Warenkorb leeren
                $checkoutModel->clearUserCart($userId);

                // Zur "Danke"-Seite weiterleiten
                header('Location: /index.php?page=order_success&id=' . $orderId);
                exit;
            } else {
                // Fehler bei der Bestellung
                $error = "Bei Ihrer Bestellung ist ein Fehler aufgetreten. Bitte versuchen Sie es erneut.";
            }
        }

        // --- SEITE ANZEIGEN (GET-Request oder Fehler beim POST) ---
        include __DIR__ . '/../view/CheckoutView.php';
    }

//RINOR STUBLLA ENDE

}