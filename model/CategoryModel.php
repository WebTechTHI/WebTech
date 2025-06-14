<?php
require_once 'db_verbindung.php';

class CategoryModel
{
    public function getProducts($category, $orderBy, $direction)
    {
        require_once 'categoryFunctions.php';
        return getProductsByCategory($GLOBALS['conn'], $category, $orderBy, $direction);
    }

    public function getCategoryInfo($category)
    {
        require_once 'categoryFunctions.php';
        return getCategoryInfo($category);
    }

    public function getProductImages($productId)
    {
        require_once 'categoryFunctions.php';
        return getProductImages($GLOBALS['conn'], $productId);
    }

    public function buildSpecifications($product)
    {
        require_once 'categoryFunctions.php';
        return buildSpecifications($product);
    }
}
