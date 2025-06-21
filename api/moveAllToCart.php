<?php
session_start();

if (isset($_SESSION['wishlist']) && !empty($_SESSION['wishlist'])) {
    foreach ($_SESSION['wishlist'] as $pid => $dummy) {
        if (isset($_SESSION['cart'][$pid])) {
            $_SESSION['cart'][$pid] += 1;
        } else {
            $_SESSION['cart'][$pid] = 1;
        }
    }
    $_SESSION['wishlist'] = []; // alles entfernt
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'empty']);
}
