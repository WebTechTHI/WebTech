<?php
require_once 'db_verbindung.php';
global $conn;

$default_products_json = '[
    {"id":7,"name":"Guest Product 7","price":10.00,"description":"Desc 7"},
    {"id":8,"name":"Shared Product 8","price":20.00,"description":"Desc 8"},
    {"id":9,"name":"User Product 9","price":30.00,"description":"Desc 9"}
]';

$products_json = isset($argv[1]) ? $argv[1] : $default_products_json;
$products = json_decode($products_json, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Invalid JSON provided for products: " . json_last_error_msg() . "\n");
}

foreach ($products as $p) {
    $stmt_check = $conn->prepare("SELECT product_id FROM product WHERE product_id = ?");
    if (!$stmt_check) { die("Prepare failed (check product): " . $conn->error . "\n"); }
    $stmt_check->bind_param("i", $p['id']);
    $stmt_check->execute();
    $result = $stmt_check->get_result();
    $stmt_check->close();

    if ($result->num_rows === 0) {
        // Adding 'subcategory_id' field, defaulting to 1.
        // INSERT: product_id, name, price, description, sale, subcategory_id
        $stmt_insert = $conn->prepare("INSERT INTO product (product_id, name, price, description, sale, subcategory_id) VALUES (?, ?, ?, ?, ?, ?)");
        if (!$stmt_insert) { die("Prepare failed (insert product): " . $conn->error . "\n"); }

        $name = $p['name'] ?? ('Product ' . $p['id']);
        $price = $p['price'] ?? 0.0;
        $description = $p['description'] ?? ('Description for ' . $p['id']);
        $sale_value = $p['sale'] ?? 0;
        $subcategory_id_value = $p['subcategory_id'] ?? 1; // Default subcategory_id to 1

        $stmt_insert->bind_param("isdsii", $p['id'], $name, $price, $description, $sale_value, $subcategory_id_value);
        if ($stmt_insert->execute()) {
            echo "Product {$p['id']} ({$name}) created with sale {$sale_value}, subcategory {$subcategory_id_value}.\n";
        } else {
            echo "Failed to create product {$p['id']}: " . $stmt_insert->error . "\n";
        }
        $stmt_insert->close();
    } else {
        echo "Product {$p['id']} already exists.\n";
    }
}
if ($conn) $conn->close();
?>
