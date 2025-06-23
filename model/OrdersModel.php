<?php
require_once __DIR__ . '/../db_verbindung.php';
class OrdersModel {
     public function getAllOrdersByUserId($userId) {
        global $conn;

        $sql = "SELECT order_id, user_id, order_date, status, total_amount 
                FROM orders 
                WHERE user_id = ? 
                ORDER BY order_date DESC"; // Neueste Bestellungen zuerst

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }

        return $orders;
    }

    
}