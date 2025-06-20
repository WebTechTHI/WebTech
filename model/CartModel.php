<?php
require_once __DIR__ . '/../db_verbindung.php';

class CartModel {

    // ✅ 1) Für eingeloggte User (optional, nur für Checkout-Merge)
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

    // ✅ 2) NEU: Session-Warenkorb verarbeiten, um View zu befüllen
    public function getProductsFromSession($cart) {
        global $conn;
        $products = [];

        foreach ($cart as $pid => $qty) {
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
                $row['quantity'] = $qty;
                $products[] = $row;
            }
        }

        return $products;
    }

    // ✅ 3) NEU: Für Checkout — Session-Warenkorb in DB mergen
    public static function addOrUpdate($pid, $qty, $uid) {
        global $conn;

        // Prüfen ob schon im Warenkorb
        $sql = "SELECT id FROM cart WHERE product_id = ? AND user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $pid, $uid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Update Menge
            $sql = "UPDATE cart SET quantity = quantity + ? WHERE product_id = ? AND user_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $qty, $pid, $uid);
            $stmt->execute();
        } else {
            // Neu einfügen
            $sql = "INSERT INTO cart (product_id, quantity, user_id) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('iii', $pid, $qty, $uid);
            $stmt->execute();
        }
    }

}
