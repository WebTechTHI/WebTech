<?php
require_once __DIR__ . '/../db_verbindung.php';
class OrdersModel {
     public function getAllOrdersByUserId($userId) {
        global $conn;

        $sql = "SELECT 
                o.order_id, 
                o.user_id, 
                o.order_date, 
                o.total_amount, 
                s.status_name
            FROM orders o
            JOIN order_status s ON o.status_id = s.order_status_id
            WHERE o.user_id = ? 
            ORDER BY o.order_date DESC"; // Neueste Bestellungen zuerst

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