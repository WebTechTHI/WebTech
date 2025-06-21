<?php
// Dies kommt ganz an den Anfang der Datei

// Prüfen, ob der Nutzer die Cookies nicht akzeptiert hat
if (!isset($_COOKIE['cookie_consent']) || $_COOKIE['cookie_consent'] !== 'accepted') {
    // Wenn keine Zustimmung vorliegt, einen Fehler zurückgeben
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'error',
        'message' => 'Bitte akzeptieren Sie die Cookies, um den Warenkorb zu nutzen.',
        'needs_consent' => true // Ein Flag, das wir im JS nutzen können
    ]);
    exit; // Wichtig: Skriptausführung hier beenden!
}
// api/removeFromCartCookie.php

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);
$cookieName = 'mlr_cart';

if (!isset($input['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID fehlt.']);
    exit;
}

$pid = intval($input['product_id']);

// Bestehenden Warenkorb aus dem Cookie lesen
$cart = [];
if (isset($_COOKIE[$cookieName])) {
    $cart = json_decode($_COOKIE[$cookieName], true);
}

// Produkt entfernen
if (isset($cart[$pid])) {
    unset($cart[$pid]);
}

// Das aktualisierte Array zurück in einen JSON-String umwandeln
$jsonCart = json_encode($cart);

// Den Cookie neu setzen
$expiry = time() + (86400 * 30);
setcookie($cookieName, $jsonCart, $expiry, "/");

echo json_encode(['status' => 'success', 'message' => 'Produkt entfernt.']);