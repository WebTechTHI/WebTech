<?php
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Bootstrap.php';

class HomeController extends Controller
{
    public function index(): void
    {
        global $conn;
        require_once 'categoryFunctions.php';
        $products = getProductsByCategory($conn, '', 'sales', 'desc');
        $this->render('home/index', ['products' => $products, 'conn' => $conn]);
    }
}
