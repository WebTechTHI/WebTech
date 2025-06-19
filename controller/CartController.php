<?php
require_once __DIR__ . '/../model/CartModel.php';

class CartController {
    public function handleRequest() {
        // Kein session_start(), läuft global über index.php

        // Korrekt prüfen: User eingeloggt & user_id vorhanden
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['user_id'])) {
            header('Location: /index.php?page=login');
            exit;
        }

        // user_id sauber lesen:
        $userId = $_SESSION['user']['user_id'];

        $model = new CartModel();
        $cartItems = $model->getCartItems($userId);

        include __DIR__ . '/../view/CartView.php';
    }
}


// Sicher prüfen    // Du hast nur $_SESSION['user'], keine user_id extra