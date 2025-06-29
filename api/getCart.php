<?php
// === Laurin Schnitzer

// API-Endpunkt zum Abrufen des Warenkorbs
// Liefert entweder die Daten aus der Datenbank (eingeloggter Nutzer) oder aus dem Cookie (Gast)
// Die Daten werden als JSON zurückgegeben
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/CartModel.php';

$cartModel = new CartModel();
$cartItems = [];

// Prüfen, ob der Nutzer eingeloggt ist oder ob Cookies gelesen werden dürfen
// und ob ein Warenkorb-Cookie existiert
if (isset($_SESSION['user']['user_id'])) {
    // ---- EINGELOGGTER NUTZER (DATENBANK-LOGIK) ----
    $userId = $_SESSION['user']['user_id'];
    $cartItems = $cartModel->getCartFromDb($userId); // Holt alles aus der DB
    
} else {
    // ---- GAST (COOKIE-LOGIK) ----
    // Prüfen, ob überhaupt Cookies gelesen werden dürfen
    if (isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] === 'accepted' && isset($_COOKIE['mlr_cart'])) {
        $cookieCart = json_decode($_COOKIE['mlr_cart'], true);
        if (is_array($cookieCart) && !empty($cookieCart)) {
            // Holt die Produktdetails zu den IDs aus dem Cookie
            $cartItems = $cartModel->getProductsFromCookieData($cookieCart);
        }
    }
}

// Gibt die fertige Liste der Warenkorb-Artikel zurück
echo json_encode(['status' => 'success', 'cart' => $cartItems]);