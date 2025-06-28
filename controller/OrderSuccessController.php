<?php
//RINOR STUBLLA
require_once 'model/OrderSuccessModel.php'; 
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

        // Bestelldaten laden
        $model = new CheckoutModel();
        //Bestellung Holen anhand der IDs
        $order = $model->getOrderById($orderId, $userId);

        if (!$order) {
            // Bestellung nicht gefunden oder keine Berechtigung
            echo "<h1>Bestellung nicht gefunden.</h1>";
            exit;
        }

   
        //falls ein Coupon existiert, dann alle infos über den Coupon holen
        if (isset($order['coupon_id']) && $order['coupon_id'] != null){
            $coupon = $model->getCoupon($order['coupon_id']);
        }

        //status holen für bestellung (z.B ausstehend)
        $orderStatus = $model->getStatus($order['status_name']);
       
        include __DIR__ . '/../view/OrderSuccessView.php';
    }
}