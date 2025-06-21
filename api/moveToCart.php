<?php
session_start();

$pid = intval($_POST['product_id'] ?? 0);

if ($pid > 0 && isset($_SESSION['wishlist'][$pid])) {
    // 1) Aus Wishlist löschen
    unset($_SESSION['wishlist'][$pid]);

    // 2) In Cart rein
    if (isset($_SESSION['cart'][$pid])) {
        $_SESSION['cart'][$pid] += 1;
    } else {
        $_SESSION['cart'][$pid] = 1;
    }

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
