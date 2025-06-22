<?php
// /api/removeCartItem.php

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../model/CartModel.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['product_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Product ID fehlt.']);
    exit;
}
$pid = (int)$input['product_id'];

$cartModel = new CartModel();

if (isset($_SESSION['user']['user_id'])) {
    // ---- EINGELOGGTER NUTZER (DATENBANK-LOGIK) ----
    $userId = $_SESSION['user']['user_id'];
    $cartModel->removeProductFromDb($userId, $pid); // Neue Funktion aus dem Model nutzen
    
} else {
    // ---- GAST (COOKIE-LOGIK) ----
    if (!isset($_COOKIE['cookie_consent']) || $_COOKIE['cookie_consent'] !== 'accepted') {
        echo json_encode(['status' => 'error', 'message' => 'Bitte Cookies akzeptieren.', 'needs_consent' => true]);
        exit;
    }

    $cookieName = 'mlr_cart';
    $cart = isset($_COOKIE[$cookieName]) ? json_decode($_COOKIE[$cookieName], true) : [];

    if (isset($cart[$pid])) {
        unset($cart[$pid]);
    }

    setcookie($cookieName, json_encode($cart), time() + (86400 * 30), "/");
}

echo json_encode(['status' => 'success']);