<?php
//MICHAEL PIETSCH

require_once "db_verbindung.php";
require_once "categoryFunctions.php";
class HomeModel
{

    public function getBestsellers($limit)
    {
        $conn = $GLOBALS['conn'];
        $products = [];
        $sql = "
        SELECT 
            p.product_id, p.name, p.short_description, p.price, p.sale, p.alt_text, p.description, p.sales,
            c.name as category_name, sc.name as subcategory_name,
            proc.model as processor_model, proc.brand as processor_brand, proc.cores as processor_cores, proc.base_clock_ghz as processor_clock,
            gpu.model as gpu_model, gpu.brand as gpu_brand, gpu.vram_gb as gpu_vram, gpu.integrated as gpu_integrated,
            ram.capacity_gb as ram_capacity, ram.ram_type,
            storage.capacity_gb as storage_capacity, storage.storage_type,
            display.size_inch as display_size, display.resolution, display.refresh_rate_hz, display.panel_type,
            os.name as os_name,
            network.spec as network_spec,
            connectors.spec as connectors_spec,
            feature.spec as feature_spec
        FROM product p
        LEFT JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
        LEFT JOIN category c ON sc.category_id = c.category_id
        LEFT JOIN processor proc ON p.cpu_id = proc.cpu_id
        LEFT JOIN gpu ON p.gpu_id = gpu.gpu_id
        LEFT JOIN ram ON p.ram_id = ram.ram_id
        LEFT JOIN storage ON p.storage_id = storage.storage_id
        LEFT JOIN display ON p.display_id = display.display_id
        LEFT JOIN operating_system os ON p.os_id = os.os_id
        LEFT JOIN network ON p.network_id = network.network_id
        LEFT JOIN connectors ON p.connectors_id = connectors.connectors_id
        LEFT JOIN feature ON p.feature_id = feature.feature_id
        ORDER BY p.sales DESC LIMIT " . $limit;

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die("Fehler bei der Abfrage: " . mysqli_error($conn));
        }


        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        return $products;
    }

    
    public function getProductImages($productId)
    {
        $conn = $GLOBALS['conn'];
        return getProductImages($conn, $productId);
    }


    public function formatPrice($price)
    {
        return formatPrice($price);
    }


    public function buildSpecifications($product)
    {
        return buildSpecifications($product);
    }

//MICHAEL PIETSCH ENDE

}