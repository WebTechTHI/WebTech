<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Bootstrap.php';

class ProductController extends Controller
{
    public function show(): void
    {
        global $conn;
        require_once 'productFunctions.php';

        $productId = $_GET['id'] ?? '1';
        $product = getProductById($conn, $productId);
        if (!$product) {
            echo '<h1>Produkt nicht gefunden</h1>';
            return;
        }

        $images         = getProductImages($conn, $productId);
        $firstImage     = $images[0]['file_path'] ?? 'assets/images/placeholder.png';
        $specs          = buildSpecifications($product);
        $relatedProducts= getRelatedProducts($conn, $product['category_name'], $productId);

        $this->render('product/index', [
            'conn'           => $conn,
            'product'        => $product,
            'productId'      => $productId,
            'images'         => $images,
            'firstImage'     => $firstImage,
            'specs'          => $specs,
            'relatedProducts'=> $relatedProducts
        ]);
    }
}
