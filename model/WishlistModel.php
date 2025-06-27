<?php
require_once __DIR__ . '/../db_verbindung.php';

class WishlistModel
{

    /**
     * Holt Produktdetails aus DB basierend auf einer Liste von Produkt-IDs (z.B. aus Cookie)
     */
    public function getProductsByIds(array $wishlist)
    {
        global $conn;
        $products = [];

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

        return $products;
    }

}
