<?php
require_once __DIR__ . '/../model/CartModel.php';

class CartController {
    public function handleRequest() {
        // Session ist für den Warenkorb nicht mehr nötig, kann aber für Login etc. aktiv bleiben
        
        // 1) Hole Warenkorb-Daten aus dem Cookie
        $cookieName = 'mlr_cart';
        $cartFromCookie = [];
        if (isset($_COOKIE[$cookieName])) {
            $cartFromCookie = json_decode($_COOKIE[$cookieName], true);
        }

        // 2) Mit CartModel die Produktinfos aus DB laden (Name, Preis, Bild etc.)
        // Die Methode getProductsFromSession kann weiterverwendet werden, da sie nur ein Array erwartet!
        $model = new CartModel();
        $cartItems = $model->getProductsFromSession($cartFromCookie);

        // 3) Übergib an View
        include __DIR__ . '/../view/CartView.php';
    }
}