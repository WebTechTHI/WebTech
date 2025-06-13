<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Bootstrap.php';

class CategoryController extends Controller
{
    public function index(): void
    {
        global $conn;
        require_once 'categoryFunctions.php';

        $category  = $_GET['category']  ?? 'alle';
        $orderBy   = $_GET['orderBy']   ?? 'id';
        $direction = $_GET['direction'] ?? 'asc';

        $products     = getProductsByCategory($conn, $category, $orderBy, $direction);
        $categoryInfo = getCategoryInfo($category);

        $this->render('category/index', [
            'conn'        => $conn,
            'categoryInfo'=> $categoryInfo,
            'products'    => $products,
            'category'    => $category,
            'orderBy'     => $orderBy,
            'direction'   => $direction
        ]);
    }
}
