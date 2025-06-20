<?php
// /api/cart/update.php
session_start();

header('Content-Type: application/json');

// Nur POST-Requests erlauben
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// JSON Input lesen
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['product_id']) || !isset($input['quantity'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ungültige Parameter']);
    exit;
}

$productId = (int)$input['product_id'];
$quantity = (int)$input['quantity'];

// Validierung
if ($productId <= 0 || $quantity <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Ungültige Produkt-ID oder Menge']);
    exit;
}

// Session Cart initialisieren falls nicht vorhanden
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

try {
    // Produkt in Session Cart aktualisieren
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['product_id'] == $productId) {
            $item['quantity'] = $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        echo json_encode(['success' => false, 'message' => 'Produkt nicht im Warenkorb gefunden']);
        exit;
    }
    
    // Cart-Daten für Response berechnen
    require_once __DIR__ . '/../../model/CartModel.php';
    $cartModel = new CartModel();
    $cartItems = $cartModel->getProductsFromSession($_SESSION['cart']);
    
    // Berechne Summen
    $subtotal = 0;
    $itemTotal = 0;
    
    foreach ($cartItems as $item) {
        $lineTotal = $item['price'] * $item['quantity'];
        $subtotal += $lineTotal;
        
        if ($item['product_id'] == $productId) {
            $itemTotal = $lineTotal;
        }
    }
    
    // Versandkosten
    $shipping = $subtotal > 29.99 ? 0 : 4.99;
    $total = $subtotal + $shipping;
    
    // Netto-Beträge (ohne MwSt.)
    $subtotalNet = $subtotal / 1.19;
    $vat = $total * 0.19;
    
    $response = [
        'success' => true,
        'item_total' => $itemTotal,
        'cart_summary' => [
            'subtotal' => $subtotalNet,
            'shipping' => $shipping,
            'vat' => $vat,
            'total' => $total
        ]
    ];
    
    echo json_encode($response);
    
} catch (Exception $e) {
    error_log('Cart update error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ein Fehler ist aufgetreten']);
}
?>