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

    public function addItemOrUpdateQuantity($userId, $productId, $quantityToAdd) {
        global $conn; // Use the global connection variable from db_verbindung.php

        // Ensure quantity to add is positive, otherwise, this operation might not make sense
        // or should be handled by a separate "removeItem" or "setQuantity" method.
        if ($quantityToAdd <= 0) {
            // Optionally, log this attempt or return a specific status/error
            return false;
        }

        $productId = (int)$productId;
        $userId = (int)$userId;
        $quantityToAdd = (int)$quantityToAdd;

        // Check if the item already exists in the cart
        $sqlCheck = "SELECT quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmtCheck = $conn->prepare($sqlCheck);
        if (!$stmtCheck) {
            error_log("Prepare failed (check): " . $conn->error);
            return false;
        }
        $stmtCheck->bind_param("ii", $userId, $productId);
        $stmtCheck->execute();
        $resultCheck = $stmtCheck->get_result();

        if ($row = $resultCheck->fetch_assoc()) {
            // Item exists, update quantity
            $newQuantity = $row['quantity'] + $quantityToAdd;
            $sqlUpdate = "UPDATE cart SET quantity = ? WHERE user_id = ? AND product_id = ?";
            $stmtUpdate = $conn->prepare($sqlUpdate);
            if (!$stmtUpdate) {
                error_log("Prepare failed (update): " . $conn->error);
                $stmtCheck->close();
                return false;
            }
            $stmtUpdate->bind_param("iii", $newQuantity, $userId, $productId);
            $success = $stmtUpdate->execute();
            if (!$success) {
                error_log("Execute failed (update): " . $stmtUpdate->error);
            }
            $stmtUpdate->close();
            $stmtCheck->close();
            return $success;
        } else {
            // Item does not exist, insert new item
            $sqlInsert = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $stmtInsert = $conn->prepare($sqlInsert);
            if (!$stmtInsert) {
                error_log("Prepare failed (insert): " . $conn->error);
                $stmtCheck->close();
                return false;
            }
            $stmtInsert->bind_param("iii", $userId, $productId, $quantityToAdd);
            $success = $stmtInsert->execute();
            if (!$success) {
                error_log("Execute failed (insert): " . $stmtInsert->error);
            }
            $stmtInsert->close();
            $stmtCheck->close();
            return $success;
        }
    }
}
