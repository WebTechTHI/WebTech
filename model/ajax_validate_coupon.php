<?php
//RINOR STUBLLA


header('Content-Type: application/json');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../db_verbindung.php';
require_once  'CheckoutModel.php';

// Nur POST-Anfragen erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Ungültige Anfrage.']);
    exit;
}

// Den Code aus dem Body der Anfrage holen
$requestBody = json_decode(file_get_contents('php://input'), true);
$code = $requestBody['code'] ?? '';

if (empty($code)) {
    echo json_encode(['success' => false, 'message' => 'Bitte einen Code eingeben.']);
    exit;
}

$checkoutModel = new CheckoutModel();
$coupon = $checkoutModel->getValidCouponByCode($code);

$response = [];

if ($coupon) {
    // Gutschein ist gültig:
    $response = [
        'success' => true,
        'message' => 'Gutschein erfolgreich angewendet!',
        'percent' => (int)$coupon['percent'],
        'code'    => $coupon['coupon_code']
    ];
} else {
    // Gutschein ist ungültig oder abgelaufen:
    $response = [
        'success' => false,
        'message' => 'Dieser Code ist ungültig oder abgelaufen.'
    ];
}

// Die Antwort als JSON ausgeben
echo json_encode($response);
exit;