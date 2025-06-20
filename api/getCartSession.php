<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['cart'])) {
  echo json_encode(['status' => 'success', 'cart' => $_SESSION['cart']]);
} else {
  echo json_encode(['status' => 'success', 'cart' => []]);
}
