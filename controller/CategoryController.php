<?php require_once 'model/CategoryModel.php';
class CategoryController
{
    public function handleRequest()
    {
        $category = $_GET['category'] ?? 'alle';
        $orderBy = $_GET['orderBy'] ?? 'id';
        $direction = $_GET['direction'] ?? 'asc';
        $model = new CategoryModel();
        // 1) rohe Produkte laden 
        $productsRaw = $model->getProducts($category, $orderBy, $direction);
        $categoryInfo = $model->getCategoryInfo($category); // 2) für jedes Produkt Bilder & Specs vorbereiten 
        $products = [];
        foreach ($productsRaw as $p) {
            $p['images'] = $model->getProductImages($p['product_id']);
            $p['specs'] = $model->buildSpecifications($p);
            $products[] = $p;
        }
        // 3) Filter-Daten (falls du sie brauchst) 
        $filters = [];
        foreach ($productsRaw as $p) {
            $filters = generateFilter($filters, $p);
        } // an die View übergeben: 
        include __DIR__ . '/../view/CategoryView.php';
    }
}