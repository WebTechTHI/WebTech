<?php


// Produkt-Klasse für Datenbankoperationen
function getProductsByCategory($conn, $category) {
    $sql = "
        SELECT 
            p.product_id, p.name, p.short_description, p.price, p.sale, p.alt_text, p.description,
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
    ";

    $category = strtolower($category);

    switch($category) {
        case 'angebote':
        case 'deals':
            $sql .= " WHERE p.sale = 1";
            break;
        case 'pc':
        case 'desktop-pc':
            $sql .= " WHERE c.name = 'PC'";
            break;
        case 'laptop':
        case 'laptops':
            $sql .= " WHERE c.name = 'Laptop'";
            break;
        case 'zubehör':
        case 'zubehoer':
            $sql .= " WHERE c.name = 'Zubehör'";
            break;
        case 'gaming-pc':
        case 'gamingpc':
            $sql .= " WHERE sc.name = 'Gaming-PC'";
            break;
        case 'office-pc':
        case 'officepc':
            $sql .= " WHERE sc.name = 'Office-PC'";
            break;
        case 'gaming-laptop':
        case 'gaminglaptop':
            $sql .= " WHERE sc.name = 'Gaming-Laptop'";
            break;
        case 'office-laptop':
        case 'officelaptop':
            $sql .= " WHERE sc.name = 'Office-Laptop'";
            break;
        case 'monitor':
        case 'monitore':
            $sql .= " WHERE sc.name = 'Monitor'";
            break;
        case 'maus':
        case 'mäuse':
            $sql .= " WHERE sc.name = 'Maus'";
            break;
        case 'tastatur':
        case 'tastaturen':
            $sql .= " WHERE sc.name = 'Tastatur'";
            break;
    }

    $sql .= " ORDER BY p.name";

    $result = mysqli_query($conn, $sql);

    if (!$result) {
        die("Fehler bei der Abfrage: " . mysqli_error($conn));
    }

    $products = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }

    return $products;
}






    
 function getProductImages($conn, $productId) {
    $productId = (int)$productId;
    $sql = "SELECT file_path, sequence_no FROM image WHERE product_id = $productId ORDER BY sequence_no";

    $result = mysqli_query($conn, $sql);
    $images = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $images[] = $row;
    }

    return $images;
}






    
   function getCategoryInfo($category) {
     $json = file_get_contents('assets/json/produktBeschreibung.json');

    // In ein Array umwandeln
    $data = json_decode($json, true);

    // Sicherheitscheck: Kleinbuchstaben
    $category = strtolower($category);

    // Wenn Kategorie existiert, gib sie zurück
    if (isset($data[$category])) {
        return $data[$category];
    } else {
        // Fallback, falls Kategorie nicht existiert
        return 
            $data['alle'];
        ;
    }
}

// Hilfsfunktion für Preis-Formatierung
function formatPrice($price) {
    $formatted = number_format($price, 2, ',', '.');
    if (substr($formatted, -3) === ',00') {
        return substr($formatted, 0, -3) . ',-';
    }
    return $formatted;
}

// Hilfsfunktion für Spezifikationen
function buildSpecifications($product) {
    $specs = [];
    
    if (!empty($product['processor_model'])) {
        $specs[] = $product['processor_model'];
    }
    if (!empty($product['gpu_model']) && !$product['gpu_integrated']) {
        $specs[] = $product['gpu_model'];
    }
    if (!empty($product['ram_capacity'])) {
        $specs[] = $product['ram_capacity'] . ' GB ' . ($product['ram_type'] ?? 'RAM');
    }
    if (!empty($product['storage_capacity'])) {
        $specs[] = $product['storage_capacity'] . ' GB ' . ($product['storage_type'] ?? 'Storage');
    }
    if (!empty($product['display_size'])) {
        $specs[] = $product['display_size'] . '"';
        if (!empty($product['resolution'])) {
            $specs[] = $product['resolution'];
        }
    }
    
    return $specs;
}

