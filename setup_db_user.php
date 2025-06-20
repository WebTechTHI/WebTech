<?php
require_once 'db_verbindung.php'; // Assumes DB connection
global $conn;
$userId = isset($argv[1]) ? (int)$argv[1] : 101; // Default or from arg
$email = "testuser{$userId}@example.com";
$username = "testuser{$userId}";
$passwordHash = password_hash("password123", PASSWORD_DEFAULT);
// Assuming your 'user' table has columns like 'firstname', 'lastname'
// If not, you'll need to adjust the INSERT statement.
// Based on previous errors, 'password_hash' was an issue, it should be 'password'.
// Also, 'is_admin' and 'created_at' might not exist or have defaults.
// Let's try a minimal set of columns known from LoginModel and RegistrationModel context.
// user_id, username, password, email
// firstname, lastname might be in user_profiles or similar, or nullable.

// Check if user exists
$stmt_check = $conn->prepare("SELECT user_id FROM user WHERE user_id = ?");
if (!$stmt_check) { die("Prepare failed (check user): " . $conn->error . "\n"); }
$stmt_check->bind_param("i", $userId);
$stmt_check->execute();
$result = $stmt_check->get_result();
$stmt_check->close();

if ($result->num_rows === 0) {
    // User does not exist, attempt to insert
    // Adjust SQL to match your actual 'user' table schema.
    // This schema is a guess: user_id, email, username, password
    $stmt_insert = $conn->prepare("INSERT INTO user (user_id, email, username, password) VALUES (?, ?, ?, ?)");
    if (!$stmt_insert) { die("Prepare failed (insert user): " . $conn->error . "\n"); }

    $stmt_insert->bind_param("isss", $userId, $email, $username, $passwordHash);
    if ($stmt_insert->execute()) {
        echo "User {$userId} created.\n";
    } else {
        // If it fails, it might be due to other constraints (e.g. email unique and already exists)
        echo "Failed to create user {$userId}: " . $stmt_insert->error . "\n";
    }
    $stmt_insert->close();
} else {
    echo "User {$userId} already exists.\n";
}
if ($conn) $conn->close();
?>
