<?php
//RINOR STUBLLA 

require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// alle funktionen werden in categoryFunctions.php definiert
class CategoryModel
{
    public function getProducts($category, $orderBy, $direction)
    {
        
        return getProductsByCategory($GLOBALS['conn'], $category, $orderBy, $direction);
    }

    public function getCategoryInfo($category)
    {
        
        return getCategoryInfo($category);
    }

    public function getProductImages($productId)
    {
        
        return getProductImages($GLOBALS['conn'], $productId);
    }

    public function buildSpecifications($product)
    {
        return buildSpecifications($product);
    }
}
//RINOR STUBLLA ENDE

