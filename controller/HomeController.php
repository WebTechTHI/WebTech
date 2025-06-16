<?php
require_once "model/HomeModel.php";
class HomeController
{
    public function handleRequest()
    {

        $model = new HomeModel();

        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];

        }
        
        //produktinformationen laden
        $productsRaw = $model -> getBestsellers(20);
        
        $products = [];
        foreach ($productsRaw as $p) {
            $p['images'] = $model->getProductImages($p['product_id']);
            $p['specs']  = $model->buildSpecifications($p);
            $products[]  = $p;
        }


        require "view/HomeView.php";
        exit;
    }
}