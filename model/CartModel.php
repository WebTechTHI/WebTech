<?php
require_once __DIR__ . '/../db_verbindung.php';

class CartModel {
    public function getCartItems($userId) {
        global $conn;
        $sql = "
            SELECT 
                c.quantity, 
                p.product_id, p.name, p.price,
                (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
            FROM cart c
            JOIN product p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }

        return $items;

    }
}
