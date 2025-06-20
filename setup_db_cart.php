<?php
require_once 'db_verbindung.php';
global $conn;

$userId = isset($argv[1]) ? (int)$argv[1] : null;
// Expecting JSON like '[{"product_id": 8, "quantity": 3}, ...]'
$cartDataJson = isset($argv[2]) ? $argv[2] : '[]';
$cartData = json_decode($cartDataJson, true);

if (!$userId || json_last_error() !== JSON_ERROR_NONE) {
    echo "User ID and valid cart JSON data required. Usage: php setup_db_cart.php <userId> '[{\"product_id\":ID, \"quantity\":QTY}]'\n";
    if(json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON Error: " . json_last_error_msg() . "\n";
    }
    exit(1);
}

// Clear existing cart for this user
$stmt_delete = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
if (!$stmt_delete) { die("Prepare failed (delete cart): " . $conn->error . "\n"); }
$stmt_delete->bind_param("i", $userId);
$stmt_delete->execute();
echo "Cleared existing cart for user {$userId}.\n";
$stmt_delete->close();

// Add new cart items
if (!empty($cartData)) {
    $stmt_insert = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    if (!$stmt_insert) { die("Prepare failed (insert cart item): " . $conn->error . "\n"); }
    foreach ($cartData as $item) {
        if (!isset($item['product_id']) || !isset($item['quantity'])) {
            echo "Skipping invalid cart item: " . json_encode($item) . "\n";
            continue;
        }
        $stmt_insert->bind_param("iii", $userId, $item['product_id'], $item['quantity']);
        if ($stmt_insert->execute()) {
            echo "Added product {$item['product_id']} (qty {$item['quantity']}) for user {$userId}.\n";
        } else {
            echo "Failed to add product {$item['product_id']} for user {$userId}: " . $stmt_insert->error . "\n";
        }
    }
    $stmt_insert->close();
}
if ($conn) $conn->close();
?>
