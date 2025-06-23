<?php
require_once __DIR__ . '/../db_verbindung.php';

class WishlistController {
    public function handleRequest() {
        global $conn; // Datenbankverbindung sicher global verfügbar machen

        // 1) Wishlist-IDs aus Cookie lesen
        $wishlist = [];
        if (!empty($_COOKIE['wishlist'])) {
            $decoded = json_decode($_COOKIE['wishlist'], true);
            if (is_array($decoded)) {
                $wishlist = $decoded;
            }
        }

        $products = [];

        // 2) Wenn IDs vorhanden sind: Produkte laden
        if (!empty($wishlist)) {
            foreach ($wishlist as $pid) {
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
                    $products[] = $row;
                }

                $stmt->close();
            }
        }

        // 3) Ergebnis ins View geben
        $wishlistItems = $products;
        include __DIR__ . '/../view/WishlistView.php';
    }
}
