<?php
require_once __DIR__ . '/../model/WishlistModel.php'; // Pfad ggf. anpassen
//LAURIN SCHNITZER

class WishlistController
{
    public function handleRequest()
    {
        // 1) Cookie auslesen
        $wishlist = [];
        if (!empty($_COOKIE['wishlist'])) {
            $decoded = json_decode($_COOKIE['wishlist'], true);
            if (is_array($decoded)) {
                $wishlist = $decoded;
            }
        }

        // 2) Produkte holen über das Model
        $model = new WishlistModel();
        $wishlistItems = $model->getProductsByIds($wishlist);

        // 3) Weiter an View
        include __DIR__ . '/../view/WishlistView.php';
    }
}
