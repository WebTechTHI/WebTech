<?php
// api/addToCartCookie.php

header('Content-Type: application/json');

// 1. Daten aus dem Request holen
$input = json_decode(file_get_contents('php://input'), true);
$pid = intval($input['product_id']);
$qty = intval($input['quantity']);
$cookieName = 'mlr_cart';

// 2. Bestehenden Warenkorb aus dem Cookie lesen
$cart = [];
if (isset($_COOKIE[$cookieName])) {
    // Cookie-Inhalt (JSON-String) in ein PHP-Array umwandeln
    $cart = json_decode($_COOKIE[$cookieName], true);
}

// 3. Menge aktualisieren oder neues Produkt hinzufügen
if (isset($cart[$pid])) {
    $cart[$pid] += $qty;
} else {
    $cart[$pid] = $qty;
}

// 4. Sicherstellen, dass die Menge nicht unter 1 fällt
if ($cart[$pid] < 1) {
    unset($cart[$pid]);
}

// 5. Das aktualisierte Array zurück in einen JSON-String umwandeln
$jsonCart = json_encode($cart);

// 6. Den Cookie setzen (Gültigkeit z.B. 30 Tage)
$expiry = time() + (86400 * 30); // 86400 = 1 Tag
setcookie($cookieName, $jsonCart, $expiry, "/");

// 7. Erfolg zurückmelden
echo json_encode(['status' => 'success']);