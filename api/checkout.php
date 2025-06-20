<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
  echo json_encode(['status' => 'not_logged_in']);
  exit;
}

// Verbinde mit DB, importiere CartModel.php
require_once('../CartModel.php');

$cart = $_SESSION['cart'] ?? [];
$uid = $_SESSION['user_id'];

foreach ($cart as $pid => $qty) {
  CartModel::addOrUpdate($pid, $qty, $uid);
}

echo json_encode(['status' => 'ok']);
