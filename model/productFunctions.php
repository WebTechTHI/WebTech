<?php
//RINOR STUBLLA 


// Hole das Produkt anhand der ID aus der Datenbank
function getProductbyId($conn, $productId)
{
    $sql = "
        SELECT 
            p.product_id, p.name, p.short_description, p.price, p.old_price, p.sale, p.stock_status, p.alt_text, p.description, p.sales,
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
        WHERE p.product_id = " . $productId;

    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    }

    return null;
}







// Funktion um den Breadcrumb für die Produktseite zu generieren
function getCategoryDisplayName($category)
{
    $map = [
        'PC' => 'Desktop-PCs',
        'Laptop' => 'Laptops',
        'Zubehör' => 'Zubehör',
        'Office-PC' => 'Office-PCs',
        'Gaming-PC' => 'Gaming-PCs',
        'Gaming-Laptop' => 'Gaming-Laptops',
        'Office-Laptop' => 'Office-Laptops',
        'Monitor' => 'Monitore',
        'Maus' => 'Mäuse',
        'Tastatur' => 'Tastaturen',
    ];
    return $map[$category] ?? $category;
}






// Funktion um die Bilder für das Produkt zu holen
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




// Funktion um den Preis zu formatieren
// z.B. 1234.56 wird zu 1.234,56 und 1234.00 wird zu 1.234,-
function formatPrice($price)
{
    $formatted = number_format($price, 2, ',', '.');
    if (substr($formatted, -3) === ',00') {
        return substr($formatted, 0, -3) . ',-';
    }
    return $formatted;
}





// Funktion um die Spezifikationen des Produkts zu bauen
// Diese Funktion nimmt ein Produkt-Array und baut ein assoziatives Array mit den Spezifikationen
// Die Spezifikationen werden nach Kategorien gruppiert, z.B. 'Prozessor', 'Grafikkarte', 'Arbeitsspeicher' usw.
// Wenn eine Spezifikation nicht vorhanden ist, wird sie nicht hinzugefügt
function buildSpecifications($product)
{
    $specs = [];


    if (!empty($product['processor_model'])) {
        $specs['Prozessor'][] = $product['processor_model'];
    }
    if (!empty($product['gpu_model'])) {
        $specs['Grafikkarte'][] = $product['gpu_model'];
    }
    if (!empty($product['ram_capacity'])) {
        $specs['Arbeitsspeicher'][] = $product['ram_capacity'] . ' GB ' . ($product['ram_type'] ?? 'RAM');
    }
    if (!empty($product['storage_capacity'])) {
        $specs["Speicher"][] = $product['storage_capacity'] . ' GB ' . ($product['storage_type'] ?? 'Storage');
    }
    if (!empty($product['display_size'])) {
        $specs['Bildschirmgröße'][] = $product['display_size'] . '" Zoll';
        if (!empty($product['resolution'])) {
            $specs['Auflösung'][] = $product['resolution'];
        }
    }


    if (!empty($product['os_name'])) {
        $specs['Betriebssystem'][] = $product['os_name'];
    }






    if (!empty($product['refresh_rate_hz'])) {
        $specs['Bildwiederholfrequenz'][] = $product['refresh_rate_hz'] . ' Hz';
    }
    if (!empty($product['panel_type'])) {
        $specs["Paneltyp"][] = $product['panel_type'];
    }







    if (!empty($product['connectors_spec'])) {
        $specs['Anschlüsse'][] = $product['connectors_spec'];
    }

    if (!empty($product['feature_spec'])) {
        $specs['Ausstattungsmerkmale'][] = $product['feature_spec'];

    }


    return $specs;
}






// Funktion um den Lieferumfang des Produkts zu bestimmen
function getLieferumfang($product)
{
    if ($product['category_name'] === 'PC' || $product['category_name'] === 'Laptop' || $product['subcategory_name'] === 'Monitor') {
        return '<li> ' . $product['name'] . '</li>
                    <li>Netzkabel</li>
                    <li>Handbuch und Treiber-CD</li>';
    } else {
        return '<li>' . $product['name'] . '</li>
                <li>Handbuch</li>';
    }
}





// Funktion um ähnliche Produkte aus der gleichen Kategorie zu holen damit unten auf der Produktseite ähnliche Produkte angezeigt werden
function getRelatedProducts($conn, $category_name, $current_product_id)
{
    $category_name = mysqli_real_escape_string($conn, $category_name);
    $current_product_id = (int) $current_product_id;

    // Ruft 10 zufällige Produkte aus derselben Unterkategorie ab (außer dem aktuellen)
    $sql = "
       SELECT p.*
FROM product p
JOIN subcategory sc ON p.subcategory_id = sc.subcategory_id
JOIN category c     ON sc.category_id   = c.category_id
WHERE c.name = '$category_name'
  AND p.product_id != $current_product_id
ORDER BY RAND()
LIMIT 10

    ";

    $result = mysqli_query($conn, $sql);
    $products = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $products[] = $row;
    }
    return $products;
}






// Funktion um die Anzahl der Produkte im Sale zu zählen
// Diese Funktion zählt die Anzahl der Produkte, die im Sale sind (sale = 1)
function getSaleCount() {
    global $conn;
    $sql = "SELECT sale FROM product";
    $result = $conn->query($sql);

    $saleCount = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row['sale'] == 1) {
                $saleCount++;
            }
        }
    }
    return $saleCount;
}

//RINOR STUBLLA ENDE


