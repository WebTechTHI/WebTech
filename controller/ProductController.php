<?php
require_once __DIR__ . '/../model/ProductModel.php';

class ProductController
{
    public function handleRequest()
    {
        $id = (int)($_GET['id'] ?? 1);

        $model    = new ProductModel();
        $product  = $model->getProductById($id);
        if (!$product) {
            echo '<h1>Produkt nicht gefunden</h1>';
            return;
        }

        $formattedId = sprintf('%04d', $product['product_id']);

        // Bilder, Specs, Lieferumfang, Related
        $product['images']          = $model->getProductImages($id);
        $firstImage = $product['images'][0]['file_path'] ;
        $product['specs']           = $model->buildSpecifications($product);
        $product['lieferumfang']    = $model->getLieferumfang($product);
        $relatedRaw                  = $model->getRelatedProducts(
                                          $product['category_name'],
                                          $id
                                        );
        // auch hier evtl. images & specs für related, falls nötig
        $related = [];
        foreach ($relatedRaw as $r) {
            $r['images'] = $model->getProductImages($r['product_id']);
            $related[]   = $r;
        }

        include __DIR__ . '/../view/ProductView.php';
    }
}
