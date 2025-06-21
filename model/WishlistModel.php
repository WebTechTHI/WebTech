<?php
require_once __DIR__ . '/../db_verbindung.php';

class WishlistModel {

    // Holt Produktdetails aus DB basierend auf Session-Wishlist
    public function getProductsFromSession($wishlist) {
        global $conn;
        $products = [];

        foreach ($wishlist as $pid => $dummy) {
            $sql = "
                SELECT 
                    p.product_id, p.name, p.price,
                    (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
                FROM product p
                WHERE p.product_id = ?
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $pid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                // Für Wishlist brauchst du keine Menge — nur Produkt
                $products[] = $row;
            }
        }

        return $products;
    }

    // (Optional) Produkt zur Session-Wishlist hinzufügen
    public static function addProduct($pid) {
        if (!isset($_SESSION['wishlist'])) {
            $_SESSION['wishlist'] = [];
        }
        $_SESSION['wishlist'][$pid] = true;
    }

    // (Optional) Produkt aus Session-Wishlist entfernen
    public static function removeProduct($pid) {
        if (isset($_SESSION['wishlist'][$pid])) {
            unset($_SESSION['wishlist'][$pid]);
        }
    }
}
