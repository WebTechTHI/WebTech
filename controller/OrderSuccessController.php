<?php
//RINOR STUBLLA
require_once 'model/OrderSuccessModel.php'; // Das neue Model
class OrderSuccesController
{
    public function handleRequest()
    {

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            // Keine ID oder ungültige ID -> zur Startseite schicken
            header('Location: /index.php');
            exit;
        }

        $orderId = (int) $_GET['id'];
        $userId = $_SESSION['user']['user_id'];

        // --- Lade die Bestelldaten ---
        $model = new CheckoutModel();
        // Die neue Funktion prüft automatisch, ob die Bestellung zum Nutzer gehört.
        $order = $model->getOrderById($orderId, $userId);

        if (!$order) {
            // Bestellung nicht gefunden oder keine Berechtigung
            // Zeige eine generische Fehlerseite oder leite um.
            echo "<h1>Bestellung nicht gefunden.</h1>";
            exit;
        }

        $orderStatus = $model->getStatus($order['status_name']);
        // --- Übergib die Daten an die View ---
        include __DIR__ . '/../view/OrderSuccessView.php';
    }
}