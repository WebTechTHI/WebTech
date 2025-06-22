<?php
// model/CheckoutModel.php

class CheckoutModel {

    /**
     * Erstellt eine neue Bestellung in der Datenbank.
     * Verwendet eine Transaktion, um sicherzustellen, dass alles oder nichts gespeichert wird.
     *
     * @param int $userId Die ID des Nutzers.
     * @param array $cartItems Die Artikel aus dem Warenkorb.
     * @param float $totalAmount Der Gesamtbetrag der Bestellung.
     * @param string $shippingAddress Die Lieferadresse.
     * @return int|false Die neue Order-ID bei Erfolg, sonst false.
     */
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
}