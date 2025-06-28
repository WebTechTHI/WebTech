<?php
//LAURIN SCHNITZER


//getProductsByCategory() kombiniert über LEFT JOINs alle technischen Produktdaten
//  und filtert dann dynamisch je nach URL-Parametern. Die Sortierung wird ebenfalls anhand von orderBy und direction festgelegt.
function getProductsByCategory($conn, $category, $orderBy, $direction, $filters = [])
{
    $sql = "
        SELECT 
            p.product_id, p.name, p.short_description, p.price, p.old_price, p.sale, p.alt_text, p.description, p.sales,
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
    // Warum LEFT JOIN?
    //Damit auch unvollständige Produkte (z. B. ohne GPU) angezeigt werden.
    //Sonst würden sie bei einem normalen INNER JOIN komplett rausfliegen.
    //Haupttabelle ist product → alle anderen Tabellen werden drangejoined.




    // Kategorie name wird in kleinbuchstaben umgewandelt, um die Abfrage zu vereinheitlichen
    $category = strtolower($category);
    // je nachdem übergebenen Kategorie-Parameter wird die WHERE-Klausel angepasst
    switch ($category) {
        case 'angebote':
            $sql .= " WHERE p.sale = 1";
            break;
        case 'pc':
            $sql .= " WHERE c.name = 'PC'";
            break;
        case 'laptop':
            $sql .= " WHERE c.name = 'Laptop'";
            break;
        case 'zubehör':
            $sql .= " WHERE c.name = 'Zubehör'";
            break;
        case 'gamingpc':
            $sql .= " WHERE sc.name = 'Gaming-PC'";
            break;
        case 'officepc':
            $sql .= " WHERE sc.name = 'Office-PC'";
            break;
        case 'gaminglaptop':
            $sql .= " WHERE sc.name = 'Gaming-Laptop'";
            break;
        case 'officelaptop':
            $sql .= " WHERE sc.name = 'Office-Laptop'";
            break;
        case 'monitor':
            $sql .= " WHERE sc.name = 'Monitor'";
            break;
        case 'maus':
            $sql .= " WHERE sc.name = 'Maus'";
            break;
        case 'tastatur':
            $sql .= " WHERE sc.name = 'Tastatur'";
            break;
    }

    //Filter hier sind erst wichtig wenn auf der Seite Filter ausgewählt wurden um die mit ajax zu laden, dann wird die funktion mit den Filtern aufgerufen
    // Filter berücksichtigen
    if (!empty($filters)) {

        //Schreibweise um schlüssel und dazugehörigen wert (key, values) gleichzeitig zu bekommen deswegen =>
        foreach ($filters as $key => $values) {
            //Wenn ein wert leer ist wird übersprungen (mit continue)
            if (empty($values))
                continue;

            // Werte escapen und als SQL-String vorbereiten //======= Wir bereiten die Werte so auf, dass sie sicher in die SQL-Abfrage eingebaut werden können
            $escaped = [];  // In das $escaped-Array schreiben wir die bereinigten (escaped) Filterwerte,
            //damit wir sie später sicher in die SQL-Abfrage einbauen können – im richtigen Format für IN (...)
            foreach ($values as $v) {
                $escaped[] = "'" . mysqli_real_escape_string($conn, $v) . "'";
            }

            $valueString = implode(',', $escaped);

            //Je nachdem welcher Filter verarbeitet wird wird dieser an die große SQL abfrage angehängt
            switch ($key) {
                case 'ram':
                    $sql .= " AND ram.capacity_gb IN ($valueString)";
                    break;
                case 'grafikkarte':
                    $sql .= " AND gpu.brand IN ($valueString)";
                    break;
                case 'prozessor':
                    $sql .= " AND proc.brand IN ($valueString)";
                    break;
                case 'speicher':
                    $sql .= " AND storage.storage_type IN ($valueString)";
                    break;
                case 'displaygröße':
                    $sql .= " AND display.size_inch IN ($valueString)";
                    break;
                case 'bildwiederholrate':
                    $sql .= " AND display.refresh_rate_hz IN ($valueString)";
                    break;
                case 'betriebssystem':

                    $sql .= " AND os.name IN ($valueString)";
                    break;
            }
        }
    }




    //festlegen des übergebenen Sortierkriteriums in SQL (Michi)
    switch ($orderBy) {
        default:
            $order = "p.product_id";
            break;
        case "sales":
            $order = "p.sales";
            break;
        case "price":
            $order = "p.price";
            break;
        case "name":
            $order = "p.name";
            break;
    }

    //festlegen der Sortierrichtung (Michi)
    switch ($direction) {
        default:
            $dir = "ASC";
            break;
        case "asc":
            $dir = "ASC";
            break;
        case "desc":
            $dir = "DESC";
            break;
    }

    $sql .= " ORDER BY " . $order . " " . $dir;

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







// getProductImages() holt alle Bilder eines Produkts aus der Datenbank
//  und sortiert sie nach der Reihenfolge (sequence_no).
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






// getCategoryInfo() lädt die Kategorie-Informationen aus der JSON-Datei
//  assets/json/categoryInfo.json. Diese Datei enthält Informationen zu jeder Kategorie
function getCategoryInfo($category)
{
    $json = file_get_contents('assets/json/categoryInfo.json');


    //Das true sorgt dafür, dass der JSON-String als assoziatives Array eingelesen wird und nicht als Objekt
    $data = json_decode($json, true);


    $category = strtolower($category);
    if (isset($data[$category])) {
        return $data[$category];
    } else {
        return
            $data['alle'];
        ;
    }
}

// Hilfsfunktion für Preis-Formatierung
function formatPrice($price)
{
    $formatted = number_format($price, 2, ',', '.');
    if (substr($formatted, -3) === ',00') {
        return substr($formatted, 0, -3) . ',-';
    }
    return $formatted;
}


// Hilfsfunktion für Spezifikationen 
//  Diese Funktion baut die Spezifikationen eines Produkts basierend auf der Kategorie und den verfügbaren Daten zusammen
//  und gibt sie als Array zurück. 

//Brauchen die Funktion buildSpecifications() Weil die Produktdaten in der Datenbank viel zu viele Spalten haben.
//Wir wollen im Frontend nur die wichtigsten 4–6 Infos anzeigen, nicht 20+ Felder

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
        // Der ??-Operator prüft, ob der Wert existiert und nicht null ist – wenn nicht, wird der Standardwert (z. B. 'RAM') verwendet
        // Nur nötig bei dynamischen Angaben (z. B. RAM-Typ), bei festen Texten wie 'Zoll Display' wird er nicht gebraucht

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
            $specs[] = $product['os_name'];
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




// generateFilter() erstellt die Filter für die Sidebar basierend auf den Produktdaten (arbeitet ausschließlich mit bereits geladenen Produktdaten)
//  und fügt sie dem übergebenen Filter-Array hinzu.
function generateFilter($filters, $product)
{
    // Prozessor Filter
    if (!empty($product['processor_brand'])) {
        $filters['Prozessor'][] = $product['processor_brand'];
    }

    // GPU Filter
    if (!empty($product['gpu_brand'])) {
        $filters['Grafikkarte'][] = $product['gpu_brand'];
    }

    // RAM Filter
    if (!empty($product['ram_capacity'])) {
        $filters['RAM'][] = $product['ram_capacity'] . ' GB';
    }


    // Storage Filter
    if (!empty($product['storage_type'])) {
        $filters['Speicher'][] = $product['storage_type'];
    }

    if (!empty($product['display_size'])) {
        $filters['Displaygröße'][] = $product['display_size'] . '" Zoll';

    }

    if (!empty($product['refresh_rate_hz'])) {
        $filters['Bildwiederholrate'][] = $product['refresh_rate_hz'] . ' Hz';

    }

    if (!empty($product['os_name'])) {
        $filters['Betriebssystem'][] = $product['os_name'];

    }

    return $filters;
}

//LAURIN SCHNITZER ENDE


