<?php
require_once 'db_verbindung.php';
global $conn;
$userId = isset($argv[1]) ? (int)$argv[1] : null;

if (!$userId) {
    echo "User ID required for deletion. Usage: php delete_db_user.php <userId>\n";
    exit(1);
}

// Delete cart items for the user first to avoid foreign key issues if cart references user
$stmt_cart = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
if (!$stmt_cart) { die("Prepare failed (delete cart): " . $conn->error . "\n"); }
$stmt_cart->bind_param("i", $userId);
if ($stmt_cart->execute()) {
    echo "Deleted cart items for user {$userId} (if any).\n";
} else {
    echo "Error deleting cart items for user {$userId}: " . $stmt_cart->error . "\n";
}
$stmt_cart->close();

// Then delete the user
$stmt_user = $conn->prepare("DELETE FROM user WHERE user_id = ?");
if (!$stmt_user) { die("Prepare failed (delete user): " . $conn->error . "\n"); }
$stmt_user->bind_param("i", $userId);

if ($stmt_user->execute()) {
    if ($stmt_user->affected_rows > 0) {
        echo "User {$userId} deleted successfully.\n";
    } else {
        echo "User {$userId} not found.\n";
    }
} else {
    echo "Error deleting user {$userId}: " . $stmt_user->error . "\n";
}
$stmt_user->close();
if ($conn) $conn->close();
?>
