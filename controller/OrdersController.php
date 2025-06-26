<?php

require_once __DIR__ . '/../model/OrdersModel.php';

class OrdersController {

    public function handleRequest() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // --- SICHERHEIT: Ist der Nutzer eingeloggt? ---
        if (!isset($_SESSION['user']['user_id'])) {
            header('Location: /index.php?page=login');
            exit;
        }

        $userId = $_SESSION['user']['user_id'];
        
        // --- Lade die Bestell-Liste ---
        $model = new OrdersModel();
        // Wir rufen unsere neue Funktion auf, um die Liste zu holen.
        $orders = $model->getAllOrdersByUserId($userId);

    

        // --- Übergib die Daten an die View ---
        include __DIR__ . '/../view/OrdersView.php';
    }


}