<?php
require_once __DIR__ . '../../db_verbindung.php';

class AdminModel
{
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


    //speichert Anderungen im upload products Formular in der Datenbank.
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
            echo "Produkt erfolgreich gespeichert.";
        } else {
            echo "Fehler beim Speichern: " . $stmt->error;
        }

        $stmt->close();

    }
}

