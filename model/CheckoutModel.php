<?php
// model/CheckoutModel.php

require_once __DIR__ . '/../db_verbindung.php'; // Datenbankverbindung
class CheckoutModel {

    public function createOrder($userId, $cartItems, $totalAmount, $shippingAddress) {
        global $conn;

        // Starte eine Transaktion
        $conn->begin_transaction();

        try {
            // 1. Bestellung in die 'orders'-Tabelle einfügen
            // Das SQL-Statement ist jetzt an deine Tabellenstruktur angepasst
            $sqlOrder = "INSERT INTO orders (user_id, total_amount, shipping_address) VALUES (?, ?, ?)";
            $stmtOrder = $conn->prepare($sqlOrder);
            if ($stmtOrder === false) {
                throw new Exception("SQL-Fehler beim Vorbereiten der Order: " . $conn->error);
            }
            // 'ids' -> i = integer (userId), d = double (totalAmount), s = string (shippingAddress)
            $stmtOrder->bind_param('ids', $userId, $totalAmount, $shippingAddress);
            $stmtOrder->execute();
            
            // Die ID der gerade erstellten Bestellung holen
            $orderId = $conn->insert_id;
            echo "<script>console.log('Bestell-ID: " . $orderId . "');</script>";
            if ($orderId === 0) {
                 throw new Exception("Konnte keine Order-ID erhalten.");
            }

            // 2. Jeden Artikel aus dem Warenkorb in die 'order_items'-Tabelle einfügen
            $sqlItem = "INSERT INTO order_items (order_id, product_id, quantity, price_per_item) VALUES (?, ?, ?, ?)";
            $stmtItem = $conn->prepare($sqlItem);
             if ($stmtItem === false) {
                throw new Exception("SQL-Fehler beim Vorbereiten der Order-Items: " . $conn->error);
            }

            foreach ($cartItems as $item) {
                // 'iiid' -> i = integer, i = integer, i = integer, d = double
                $stmtItem->bind_param('iiid', $orderId, $item['product_id'], $item['quantity'], $item['price']);
                $stmtItem->execute();
            }

            // Wenn alles gut ging, die Änderungen permanent machen
            $conn->commit();
            return $orderId;

        } catch (Exception $e) {
            // Wenn ein Fehler auftrat, alle Änderungen rückgängig machen
            $conn->rollback();
            // Optional: Fehler für dich als Entwickler loggen
            error_log("Bestellfehler: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Leert den Warenkorb eines Nutzers nach erfolgreicher Bestellung.
     */
    public function clearUserCart($userId) {
        global $conn;
        $sql = "DELETE FROM cart WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
    }



    public function getOrderById($orderId, $userId) {
        global $conn;

        // 1. Hole die Kopfdaten der Bestellung
        $sqlOrder = "SELECT * FROM orders WHERE order_id = ? AND user_id = ?";
        $stmtOrder = $conn->prepare($sqlOrder);
        $stmtOrder->bind_param('ii', $orderId, $userId);
        $stmtOrder->execute();
        $resultOrder = $stmtOrder->get_result();
        
        $order = $resultOrder->fetch_assoc();

        if (!$order) {
            // Bestellung nicht gefunden oder gehört nicht diesem Nutzer
            return null;
        }

        // 2. Hole die zugehörigen Artikel
        $sqlItems = "
            SELECT oi.*, p.name, (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
            FROM order_items oi
            JOIN product p ON oi.product_id = p.product_id
            WHERE oi.order_id = ?
        ";
        $stmtItems = $conn->prepare($sqlItems);
        $stmtItems->bind_param('i', $orderId);
        $stmtItems->execute();
        $resultItems = $stmtItems->get_result();

        $items = [];
        while ($row = $resultItems->fetch_assoc()) {
            $items[] = $row;
        }

        // Füge die Artikel-Liste zum Bestell-Array hinzu
        $order['items'] = $items;

        return $order;
    }
}