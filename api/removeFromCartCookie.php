<?php
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