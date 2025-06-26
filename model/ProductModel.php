<?php
require_once __DIR__ . '/../db_verbindung.php';
require_once  'productFunctions.php';

// alle Funktionen werden in der Datei productFunctions.php definiert
class ProductModel
{
    public function getProductById(int $id): ?array
    {
        return getProductById($GLOBALS['conn'], $id);
    }

    public function getProductImages(int $id): array
    {
        return getProductImages($GLOBALS['conn'], $id);
    }

    public function buildSpecifications(array $product): array
    {
        return buildSpecifications($product);
    }

    public function getLieferumfang(array $product): string
    {
        return getLieferumfang($product);
    }

    public function getRelatedProducts(string $category, int $currentId): array
    {
        return getRelatedProducts($GLOBALS['conn'], $category, $currentId);
    }
}
