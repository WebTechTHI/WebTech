<?php 
//RINOR STUBLLA 

require_once 'model/CategoryModel.php';
class CategoryController
{
    public function handleRequest()
    {
        // Parameter auslesen aus der URL 
        $category = $_GET['category'] ?? 'alle';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $direction = $_GET['direction'] ?? 'asc';
        $model = new CategoryModel();


        // rohe Produkte laden 
        $productsRaw = $model->getProducts($category, $orderBy, $direction);
        //  Kategorie-Info laden aus der JSON-Datei /assets/json/categoryInfo.json
        $categoryInfo = $model->getCategoryInfo($category); 
        
        //  für jedes Produkt Bilder & Specs vorbereiten 
        //  (hier wird die Datenbank abgefragt)
        $products = [];
        foreach ($productsRaw as $p) {
            $p['images'] = $model->getProductImages($p['product_id']);
            $p['specs'] = $model->buildSpecifications($p);
            // 
            $products[] = $p;
        }
        
        //  Filter-Daten um die Sidebar zu füllen 
        $filters = [];
        foreach ($productsRaw as $p) {
            $filters = generateFilter($filters, $p);

        } 
        // an die View übergeben: 
        include __DIR__ . '/../view/CategoryView.php';
    }
//RINOR STUBLLA ENDE

}