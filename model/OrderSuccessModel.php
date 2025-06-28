<?php
//RINOR STUBLLA ENDE


require_once __DIR__ . '/../db_verbindung.php'; // Datenbankverbindung
class CheckoutModel
{
    public function getOrderById($orderId, $userId)
    {
        global $conn;

        // 1. Hole die Kopfdaten der Bestellung
        $sqlOrder = "SELECT o.*, s.status_name 
                    FROM orders o 
                    JOIN order_status s ON o.status_id = s.order_status_id 
                    WHERE o.order_id = ? AND o.user_id = ?";
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

    public function getStatus($status) {
    switch ($status) {
        case 'pending':
            return 'Ausstehend';
        case 'processing':
            return 'In Bearbeitung';
        case 'shipped':
            return 'Versandt';
        case 'delivered':
            return 'Zugestellt';
        case 'cancelled':
            return 'Storniert';
        case 'returned':
            return 'Zurückgegeben';
        case 'failed':
            return 'Fehlgeschlagen';
        default:
            return 'Ausstehend'; 
    }
}
}

//RINOR STUBLLA ENDE
