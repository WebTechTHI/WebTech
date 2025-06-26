<?php
// API-Endpunkt zum Hinzufügen oder Aktualisieren eines Artikels im Warenkorb
// Dieser Endpunkt verarbeitet sowohl eingeloggte Nutzer (Datenbank-Logik) als auch Gäste (Cookie-Logik)

session_start();
header('Content-Type: application/json');

// Benötigte Dateien laden
require_once __DIR__ . '/../model/CartModel.php';

// Daten aus dem Request holen
$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['product_id']) || !isset($input['quantity'])) {
    echo json_encode(['status' => 'error', 'message' => 'Fehlende Daten.']);
    exit;
}
// Produkt-ID und Menge aus der Anfrage extrahieren
// Produkt-ID und Menge müssen als Integer verarbeitet werden
$pid = (int)$input['product_id'];
$qty = (int)$input['quantity']; // Menge kann +1, -1 oder eine Startmenge sein


$cartModel = new CartModel();

// 3. Prüfen: Ist der Nutzer eingeloggt?
if (isset($_SESSION['user']['user_id'])) {
    // ---- JA, EINGELOGGTER NUTZER (DATENBANK-LOGIK) ----
    $userId = $_SESSION['user']['user_id'];
    $cartModel->addOrUpdateProductInDb($userId, $pid, $qty);
    
} else {
    // ---- NEIN, GAST (COOKIE-LOGIK) ----
    // Prüfen, ob der Gast Cookies überhaupt akzeptiert hat
    if (!isset($_COOKIE['cookie_consent']) || $_COOKIE['cookie_consent'] !== 'accepted') {
        echo json_encode(['status' => 'error', 'message' => 'Bitte akzeptieren Sie die Cookies, um den Warenkorb zu nutzen.', 'needs_consent' => true]);
        exit;
    }

    $cookieName = 'mlr_cart';
    $cart = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];
    
    // Menge im Cookie aktualisieren
    if (isset($cart[$pid])) {
        $cart[$pid] += $qty;
    } else {
        $cart[$pid] = $qty;
    }
    
    // Wenn Menge unter 1 fällt, Artikel entfernen
    if ($cart[$pid] < 1) {
        unset($cart[$pid]);
    }

    // Cookie aktualisieren
    // Das Cookie wird für 30 Tage gültig sein
    setcookie($cookieName, json_encode($cart), time() + (86400 * 30), "/");
}

//  Erfolg melden
echo json_encode(['status' => 'success']);