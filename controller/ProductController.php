<?php
require_once __DIR__ . '/../model/ProductModel.php';

class ProductController
{
    public function handleRequest()
    {
        //url aus der url holen zum beispiel /?page=product&id=1
        $id = (int) ($_GET['id'] ?? 1);

        $model = new ProductModel();
        $product = $model->getProductById($id);
        // Wenn Produkt mit dieser ID nicht gefunden, dann Fehleranzeige
        if (!$product) {
            echo '<h1>Produkt nicht gefunden</h1>';
            return;
        }

        //wandele id in 4-stellige Zahl um, damit es immer gleich aussieht
        // z.B. 1 wird zu 0001, 12 zu 0012
        $formattedId = sprintf('%04d', $product['product_id']);

        // ============ Bilder, Specs, Lieferumfang, und ähnliche Produkte =============
        //Hole alle Bilder für das Produkt 
        $product['images'] = $model->getProductImages($id);
        $firstImage = $product['images'][0]['file_path'];
        $product['specs'] = $model->buildSpecifications($product);
        $product['lieferumfang'] = $model->getLieferumfang($product);
        $relatedRaw = $model->getRelatedProducts($product['category_name'],$id);

        // relatedRaw ist ein Array mit den ähnlichen Produkten, die in der gleichen Kategorie sind
        // und nicht das aktuelle Produkt sind. Wir holen die Bilder für jedes ähnliche Produkt.
        $related = [];
        foreach ($relatedRaw as $r) {
            $r['images'] = $model->getProductImages($r['product_id']);
            $related[] = $r;
        }
        // ============ Bilder, Specs, Lieferumfang, und ähnliche Produkte  ENDE =============


        // je nachdem was in der Datenbank steht bei "stock_status", wird der Status des Produktes gesetzt
        $statusText = [
            'green' => 'Auf Lager, Lieferzeit 1-3 Werktage',
            'yellow' => 'Wenige verfügbar, Lieferzeit 4-7 Werktage',
            'red' => 'Zurzeit nicht verfügbar'
        ];

        include __DIR__ . '/../view/ProductView.php';
    }
}
