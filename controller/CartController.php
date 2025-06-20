<?php
require_once __DIR__ . '/../model/CartModel.php';

class CartController {
    public function handleRequest() {
        // Session global aktiv (kommt über index.php)

        // 1) Hole Session-Warenkorb (egal ob eingeloggt oder nicht)
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }

        // 2) Mit CartModel die Produktinfos aus DB laden (Name, Preis, Bild etc.)
        $model = new CartModel();
        $cartItems = $model->getProductsFromSession($_SESSION['cart']);

        // 3) Übergib an View
        include __DIR__ . '/../view/CartView.php';
    }
}
