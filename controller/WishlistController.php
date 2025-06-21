<?php
require_once __DIR__ . '/../model/WishlistModel.php';

class WishlistController {
    public function handleRequest() {
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }

        $model = new WishlistModel();
        $wishlistItems = $model->getProductsFromSession($_SESSION['wishlist']);

        include __DIR__ . '/../view/WishlistView.php';
    }
}
