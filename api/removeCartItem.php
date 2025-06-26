<?php
// API-Endpunkt zum Entfernen eines Artikels aus dem Warenkorb
// Entfernt entweder den Artikel aus der Datenbank (eingeloggter Nutzer) oder aus dem Cookie (Gast)

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/CartModel.php';
// Prüfen, ob die Anfrage ein JSON-Body enthält
$input = json_decode(file_get_contents('php://input'), true);
//
if (!isset($input['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID fehlt.']);
    exit;
}
// Produkt-ID aus der Anfrage extrahieren
$pid = (int)$input['product_id'];

$cartModel = new CartModel();
// Prüfen, ob der Nutzer eingeloggt ist oder ob Cookies gelesen werden dürfen
// und ob ein Warenkorb-Cookie existiert    
if (isset($_SESSION['user']['user_id'])) {
    // ---- EINGELOGGTER NUTZER (DATENBANK-LOGIK) ----
    $userId = $_SESSION['user']['user_id'];
    $cartModel->removeProductFromDb($userId, $pid); // Funktion aus dem Model nutzen
    
} else {
    // ---- GAST (COOKIE-LOGIK) ----
    if (!isset($_COOKIE['cookie_consent']) || $_COOKIE['cookie_consent'] !== 'accepted') {
        echo json_encode(['status' => 'error', 'message' => 'Bitte Cookies akzeptieren.', 'needs_consent' => true]);
        exit;
    }
    // Prüfen, ob überhaupt ein Warenkorb-Cookie existiert
    $cookieName = 'mlr_cart';
    // Wenn das Cookie existiert, laden wir den Warenkorb
    // und entfernen das Produkt, falls es vorhanden ist
    $cart = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];


    if (isset($cart[$pid])) {
        unset($cart[$pid]);
    }
    // Speichern des aktualisierten Warenkorbs im Cookie
    // Das Cookie wird für 30 Tage gültig sein
    setcookie($cookieName, json_encode($cart), time() + (86400 * 30), "/");
}
// Erfolgreiche Antwort zurückgeben
echo json_encode(['status' => 'success']);