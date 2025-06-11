<?php


function getProductbyId($conn, $productId)
{
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
        WHERE p.product_id = " .$productId ;

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null; 
}





function getCategoryDisplayName($category) {
    $map = [
        'PC' => 'Desktop-PCs',
        'Laptop' => 'Laptops',
        'Zubehör' => 'Zubehör',
        'Office-PC' => 'Office-PCs',
        'Gaming-PC'=> 'Gaming-PCs',
        'Gaming-Laptop' => 'Gaming-Laptops',  
        'Office-Laptop'=> 'Office-Laptops',
        'Monitor' => 'Monitore',
        'Maus'=> 'Mäuse',
        'Tastatur' => 'Tastaturen',
    ];
    return $map[$category] ?? $category;
}




function getProductImages($conn, $productId)
{
    $productId = (int) $productId;
    $sql = "SELECT file_path, sequence_no FROM image WHERE product_id = $productId ORDER BY sequence_no";

    $result = mysqli_query($conn, $sql);
    $images = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }

    return $images;
}


function formatPrice($price)
{
    $formatted = number_format($price, 2, ',', '.');
    if (substr($formatted, -3) === ',00') {
        return substr($formatted, 0, -3) . ',-';
    }
    return $formatted;
}





function buildSpecifications($product)
{
    $specs = [];
    if ($product['category_name'] === 'PC' || $product['category_name'] === 'Laptop') {

        if (!empty($product['processor_model'])) {
            $specs[] = $product['processor_model'];
        }
        if (!empty($product['gpu_model'])) {
            $specs[] = $product['gpu_model'];
        }
        if (!empty($product['ram_capacity'])) {
            $specs[] = $product['ram_capacity'] . ' GB ' . ($product['ram_type'] ?? 'RAM');
        }
        if (!empty($product['storage_capacity'])) {
            $specs[] = $product['storage_capacity'] . ' GB ' . ($product['storage_type'] ?? 'Storage');
        }
        if (!empty($product['display_size'])) {
            $specs[] = $product['display_size'] . '" Zoll';
            if (!empty($product['resolution'])) {
                $specs[] = $product['resolution'];
            }
        }
    }
    if ($product['category_name'] === 'PC') {
        if (!empty($product['os_name'])) {
            $specs[] = $product['os_name'] ;
        }
    }


    if ($product['subcategory_name'] === 'Monitor') {
        if (!empty($product['display_size'])) {
            $specs[] = $product['display_size'] . '" Zoll';
        }
        
        if (!empty($product['refresh_rate_hz'])) {
            $specs[] = $product['refresh_rate_hz'] . ' Hz';
        }
        if (!empty($product['panel_type'])) {
            $specs[] = $product['panel_type'];
        }

        if (!empty($product['resolution'])) {
            $specs[] = $product['resolution'];
        }

    }


    if ($product['subcategory_name'] === 'Maus' || $product['subcategory_name'] === 'Tastatur') {
        if (!empty($product['connectors_spec'])) {
            $specs[] = $product['connectors_spec'];
        }

         if (!empty($product['feature_spec'])) {
            $specs[] = $product['feature_spec'];
            
        }
    }

    return $specs;
}
