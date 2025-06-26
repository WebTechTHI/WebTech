<?php
require_once __DIR__ . '../../db_verbindung.php';
require_once "categoryFunctions.php";
class AdminModel
{


    //Lädt alle produkte aus der datenbank und speichert diese in einem array
    public function getProducts()
    {
        $conn = $GLOBALS['conn'];
        $products = [];
        $sql = "
        SELECT 
            p.product_id, p.name, p.short_description, p.price, p.sale, p.subcategory_id, p.cpu_id, p.gpu_id, p.ram_id, p.storage_id, p.display_id , p.os_id , p.network_id , p.connectors_id, p.feature_id ,  p.alt_text, p.description, p.sales,
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
        LEFT JOIN feature ON p.feature_id = feature.feature_id ORDER BY p.product_id";


        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_assoc($result)) {
            $products[] = $row;
        }

        return $products;
    }


    //Lädt die informationen zu einem einzelnen produkt
    public function getProductById($id)
    {
        $conn = $GLOBALS['conn'];
        $sql = "SELECT product.*, subcategory.category_id
                FROM product
                JOIN subcategory ON product.subcategory_id = subcategory.subcategory_id
                WHERE product_id = " . $id;

        return mysqli_fetch_assoc(mysqli_query($conn, $sql));

    }

    //Lädt die bilder eines gegebenen Rodukts
    public function getProductImages($productId)
    {
        $conn = $GLOBALS['conn'];
        return getProductImages($conn, $productId);
    }




    //gibt die spezifikationen eines gegebenen produkts
    public function buildSpecifications($product)
    {
        return buildSpecifications($product);
    }




    //gibt alle angelegten komponenten einer art, 
    // beispielsweise type = gpu -> lädt alle datensätze von gpus, also rtx4070, gtx2070, ...
    public function getComponents($type)
    {
        $sql = "SELECT * FROM $type";
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }



    //Speichert Änderungen an einem Produkt
    public function editSubmit($id)
    {

        /*

        Alle werte Aus der Form auslesen und Speichern. Werte die nicht gesetzt sind, da die selektoren nicht geladen werden, wie beispielsweise die gpu beim anlegen
        eines Monitors oder das os beim anlegen einer maus, müssen extra abgefragt werden (if-cases). wenn sie angezeigt werden aber nicht angewählt werden haben diese
        einen leeren string (''), was ebenfalls geprüft werden muss.

        */


        if (isset($_POST['subcategory'])) {
            $subcategory = ($_POST['subcategory'] === '') ? 1 : $_POST['subcategory'];
        } else {
            $subcategory = 1;
        }

        if (isset($_POST['name'])) {
            $name = ($_POST['name'] === '') ? "" : $_POST['name'];
        } else {
            $name = "";
        }

        if (isset($_POST['short-description'])) {
            $short_description = ($_POST['short-description'] === '') ? "" : $_POST['short-description'];
        } else {
            $short_description = "";
        }

        if (isset($_POST["description"])) {
            $description = ($_POST['description'] === '') ? "" : $_POST['description'];
        } else {
            $description = "";
        }

        if (isset($_FILES['images'])) {
            $images = $_FILES['images'];
        } else {
            $images = null;
        }

        if (isset($_POST["price"])) {
            $price = ($_POST['price'] === '') ? "9999.99" : $_POST['price'];
        } else {
            $price = '9999.99';
        }

        if (isset($_POST['display'])) {
            $display = ($_POST['display'] === '') ? null : $_POST['display'];
        } else {
            $display = null;
        }

        if (isset($_POST['connector'])) {
            $connector = ($_POST['connector'] === '') ? null : $_POST['connector'];
        } else {
            $connector = null;
        }

        if (isset($_POST['feature'])) {
            $feature = ($_POST['feature'] === '') ? null : $_POST['feature'];
        } else {
            $feature = null;
        }

        if (isset($_POST['cpu'])) {
            $cpu = ($_POST['cpu'] === '') ? null : $_POST['cpu'];
        } else {
            $cpu = null;
        }

        if (isset($_POST['gpu'])) {
            $gpu = ($_POST['gpu'] === '') ? null : $_POST['gpu'];
        } else {
            $gpu = null;
        }

        if (isset($_POST['storage'])) {
            $storage = ($_POST['storage'] === '') ? null : $_POST['storage'];
        } else {
            $storage = null;
        }

        if (isset($_POST['os'])) {
            $os = ($_POST['os'] === '') ? null : $_POST['os'];
        } else {
            $os = null;
        }

        if (isset($_POST['ram'])) {
            $ram = ($_POST['ram'] === '') ? null : $_POST['ram'];
        } else {
            $ram = null;
        }

        if (isset($_POST['network'])) {
            $network = ($_POST['network'] === '') ? null : $_POST['network'];
        } else {
            $network = null;
        }

        if (isset($_POST['sale'])) {
            $sale = ($_POST['sale'] === '') ? 0 : $_POST['sale'];
        } else {
            $sale = 0;
        }



        //SQL Anfrage vorbereiten

        $sql = "UPDATE product SET

                name = ?, short_description = ?, price = ?, sale = ?, subcategory_id = ?, cpu_id = ?, 
                gpu_id = ?, ram_id = ?, storage_id = ?, display_id = ?, os_id = ?, network_id = ?,
                connectors_id = ?, feature_id = ?, alt_text = ?, description = ?

                WHERE product_id = ?";

        $stmt = $GLOBALS['conn']->prepare($sql);


        //Variablen belegen (Values setzen)
        $stmt->bind_param(
            "ssdiiiiiiiiiiissi",
            $name,
            $short_description,
            $price,
            $sale,
            $subcategory,
            $cpu,
            $gpu,
            $ram,
            $storage,
            $display,
            $os,
            $network,
            $connector,
            $feature,
            $name,
            $description,
            $id
        );


        //Ausführen der SQL abfrage (und hoffen dass es klappt)
        $stmt->execute();



        //Feedback zu erfolgreichem / Erfolglosem Upload
        if ($stmt->affected_rows > 0) {
            echo "<h1 style=" . "text-align:center" . ">Produkt erfolgreich gespeichert.</h1>";
        } else {
            echo "Fehler beim Speichern: " . $stmt->error;
        }



        //
        //Ab hier: Fotos speichern
        //


        //Ordner für fotos erstellen
        $uploadDir = __DIR__ . "/../uploads/" . $id;

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }


        if (isset($_FILES['images'])) {
            $amountImages = count($_FILES['images']['name']);


            //jeden fotopfad in der datenbank speichern
            for ($imageCount = 0; $imageCount < $amountImages; $imageCount++) {

                //Temporär gespeichertes bild vom server holen und speichern. name des originalfotos speichern.
                $tmpName = $_FILES['images']['tmp_name'][$imageCount];
                $imageName = basename($_FILES['images']['name'][$imageCount]);

                //pfade für 
                $filePath = "/uploads/$id/$imageName";
                $savePath = $uploadDir . '/' . $imageName;

                move_uploaded_file($tmpName, $savePath);

                //um eins erhöhen um richtige sequenznumer in datzenbank zu speichern, da diese bei 1 und nicht 0 anfangen
                $sequenceNumber = $imageCount + 1;


                //sql absetzen
                $stmt = $GLOBALS['conn']->prepare("INSERT INTO image (product_id, file_path, sequence_no) VALUES(?,?,?)");
                $stmt->bind_param("isi", $id, $filePath, ($sequenceNumber));
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<h1>Änderungen erfolgreich gespeichert.</h1>";
                } else {
                    echo "Fehler beim Speichern: " . $stmt->error;
                }
            }
        }

    }

    //legt Datensatz für ein neues produkt in der Datenbank an
    public function uploadSubmit()
    {

        /*

        Alle werte Aus der Form auslesen und Speichern. Werte die nicht gesetzt sind, da die selektoren nicht geladen werden, wie beispielsweise die gpu beim anlegen
        eines Monitors oder das os beim anlegen einer maus, müssen extra abgefragt werden (if-cases). wenn sie angezeigt werden aber nicht angewählt werden haben diese
        einen leeren string (''), was ebenfalls geprüft werden muss.

        */


        if (isset($_POST['subcategory'])) {
            $subcategory = ($_POST['subcategory'] === '') ? 1 : $_POST['subcategory'];
        } else {
            $subcategory = 1;
        }

        if (isset($_POST['name'])) {
            $name = ($_POST['name'] === '') ? "" : $_POST['name'];
        } else {
            $name = "";
        }

        if (isset($_POST['short-description'])) {
            $short_description = ($_POST['short-description'] === '') ? "" : $_POST['short-description'];
        } else {
            $short_description = "";
        }

        if (isset($_POST["description"])) {
            $description = ($_POST['description'] === '') ? "" : $_POST['description'];
        } else {
            $description = "";
        }

        if (isset($_FILES['images'])) {
            $images = $_FILES['images'];
        } else {
            $images = null;
        }

        if (isset($_POST["price"])) {
            $price = ($_POST['price'] === '') ? "9999.99" : $_POST['price'];
        } else {
            $price = '9999.99';
        }

        if (isset($_POST['display'])) {
            $display = ($_POST['display'] === '') ? null : $_POST['display'];
        } else {
            $display = null;
        }

        if (isset($_POST['connector'])) {
            $connector = ($_POST['connector'] === '') ? null : $_POST['connector'];
        } else {
            $connector = null;
        }

        if (isset($_POST['feature'])) {
            $feature = ($_POST['feature'] === '') ? null : $_POST['feature'];
        } else {
            $feature = null;
        }

        if (isset($_POST['cpu'])) {
            $cpu = ($_POST['cpu'] === '') ? null : $_POST['cpu'];
        } else {
            $cpu = null;
        }

        if (isset($_POST['gpu'])) {
            $gpu = ($_POST['gpu'] === '') ? null : $_POST['gpu'];
        } else {
            $gpu = null;
        }

        if (isset($_POST['storage'])) {
            $storage = ($_POST['storage'] === '') ? null : $_POST['storage'];
        } else {
            $storage = null;
        }

        if (isset($_POST['os'])) {
            $os = ($_POST['os'] === '') ? null : $_POST['os'];
        } else {
            $os = null;
        }

        if (isset($_POST['ram'])) {
            $ram = ($_POST['ram'] === '') ? null : $_POST['ram'];
        } else {
            $ram = null;
        }

        if (isset($_POST['network'])) {
            $network = ($_POST['network'] === '') ? null : $_POST['network'];
        } else {
            $network = null;
        }

        if (isset($_POST['sale'])) {
            $sale = ($_POST['sale'] === '') ? 0 : $_POST['sale'];
        } else {
            $sale = 0;
        }



        //SQL Anfrage vorbereiten

        $sql = "INSERT INTO product

        (name, short_description, price, sale, subcategory_id, cpu_id, gpu_id, ram_id, storage_id, display_id, os_id, network_id, connectors_id, feature_id, alt_text, description)

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $GLOBALS['conn']->prepare($sql);


        //Variablen belegen (Values setzen)
        $stmt->bind_param(
            "ssdiiiiiiiiiiiss",
            $name,
            $short_description,
            $price,
            $sale,
            $subcategory,
            $cpu,
            $gpu,
            $ram,
            $storage,
            $display,
            $os,
            $network,
            $connector,
            $feature,
            $name,
            $description
        );


        //Ausführen der SQL abfrage (und hoffen dass es klappt)
        $stmt->execute();



        //Feedback zu erfolgreichem / Erfolglosem Upload
        if ($stmt->affected_rows > 0) {
            echo "<h1 style=" . "text-align:center" . ">Produkt erfolgreich gespeichert.</h1>";
        } else {
            echo "Fehler beim Speichern: " . $stmt->error;
        }



        //
        //Ab hier: Fotos speichern
        //


        //letzte vergeben id in products speichern, um fotos zu produkten zuordnen zu können

        $productId = $stmt->insert_id;


        //Ordner für fotos erstellen
        $uploadDir = __DIR__ . "/../uploads/" . $productId;


        mkdir($uploadDir, 0777, true);



        if (isset($_FILES['images'])) {
            $amountImages = count($_FILES['images']['name']);


            //jeden fotopfad in der datenbank speichern
            for ($imageCount = 0; $imageCount < $amountImages; $imageCount++) {

                //Temporär gespeichertes bild vom server holen und speichern. name des originalfotos speichern.
                $tmpName = $_FILES['images']['tmp_name'][$imageCount];
                $imageName = basename($_FILES['images']['name'][$imageCount]);


                $filePath = "/uploads/$productId/$imageName";
                $savePath = $uploadDir . '/' . $imageName;

                move_uploaded_file($tmpName, $savePath);

                //um eins erhöhen um richtige sequenznumer in datzenbank zu speichern, da diese bei 1 und nicht 0 anfangen
                $sequenceNumber = $imageCount + 1;


                //sql absetzen
                $stmt = $GLOBALS['conn']->prepare("INSERT INTO image (product_id, file_path, sequence_no) VALUES(?,?,?)");
                $stmt->bind_param("isi", $productId, $filePath, ($sequenceNumber));
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    echo "<h1 style=" . "text-align:center" . ">Bilder erfolgreich hochgeladen.</h1>";
                } else {
                    echo "Fehler beim Speichern: " . $stmt->error;
                }

            }
        }

    }

    //Produkt löschen, bilder bleiben noch bestehen
    public function deleteProduct($id)
    {

        $stmt = $GLOBALS['conn']->prepare("DELETE FROM product WHERE product_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();


        if ($stmt->affected_rows > 0) {
            echo "<h1 style=" . "text-align:center" . ">Löschen erfolgreich.</h1>";
        } else {
            echo "Fehler beim Löschen: " . $stmt->error;
        }
    }


    //user funktionen

    //alle user aus datenbank abrufen und zurückgeben
    public function getUsers()
    {


        $sql = "SELECT * FROM user";
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }


    //gibt user aus datenbank zurück
    public function getUserById($id)
    {


        $sql = "SELECT * FROM user WHERE user_id = " . $id;
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }


    //gibt alle bestellungen eines benutzers zurück
    public function getOrdersByUser($id)
    {


        $sql = "SELECT * FROM orders WHERE user_id = " . $id;
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }



    public function getOrders()
    {


        $sql = "SELECT * FROM orders";
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        $stmt->close();

        return $data;
    }


    //gibt spezifische bestellung nqahc gegebener orderId 
    public function getOrderById($id)
    {


        $sql = "SELECT * FROM orders WHERE order_id = " . $id;
        $stmt = $GLOBALS['conn']->prepare($sql);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        $stmt->close();

        return $data;
    }


    //ändert status einer bestellung
    public function changeOrderStatus($id,$status)
    {
        $sql = "UPDATE orders 
                SET status = ? 
                WHERE order_id = ?";

        $stmt = $GLOBALS['conn']->prepare($sql);


        //Variablen belegen (Values setzen)
        $stmt->bind_param(
            "si",
            $id,
            $status
        );


        //Ausführen der SQL abfrage (und hoffen dass es klappt)
        $stmt->execute();



        //Feedback zu erfolgreichem / Erfolglosem Upload
        if ($stmt->affected_rows > 0) {
            echo "<h1 style=" . "text-align:center" . ">Produkt erfolgreich gespeichert.</h1>";
        } else {
            echo "Fehler beim Speichern: " . $stmt->error;
        }
    }




}

