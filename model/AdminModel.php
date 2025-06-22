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

        Alle werte Aus der Form auslesen und Speichern.

        */
        $subcategory = $_POST['subcategory'] ?? 1;
        $name = $_POST['name'] ?? "";
        $short_description = $_POST['short-description'] ?? "";
        $description = $_POST['description'] ?? "";
        $price = $_POST['price'] ?? "9999.99";
        $display = $_POST['display'] ?? null;
        $connector = $_POST['connector'] ?? null;
        $feature = $_POST['feature'] ?? null;
        $cpu = $_POST['cpu'] ?? null;
        $gpu = $_POST['gpu'] ?? null;
        $storage = $_POST['storage'] ?? null;
        $os = $_POST['os'] ?? null;
        $ram = $_POST['ram'] ?? null;
        $network = $_POST['network'] ?? null;
        $sale = $_POST['sale'] ?? null;


        //SQL Anfrage vorbereiten

        $sql = "INSERT INTO product

        (name, short_description, price, sale, subcategory_id, cpu_id, gpu_id, ram_id, storage_id, display_id, os_id, network_id, connectors_id, feature_id, alt_text, description)

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $GLOBALS['conn']->prepare($sql);


        //Variablen belegen (Values setzen)
        $stmt->bind_param(
            "ssssssssssssssss",
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

