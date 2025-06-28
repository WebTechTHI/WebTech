<?php
//MICHAEL PIETSCH
require_once __DIR__ . '/../db_verbindung.php'; // Datenbankverbindung

class ConfiguratorModel
{

    //gibt alle komponenten einer art zurück
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


    //speichert anfrage der konfigurierten pcs in datenbank in configured_orders und configurations
    public function uploadConfiguration()
    {


        //speichern der form werte in variablen

        $connector = $_POST["connector"] != '' ? $_POST['connector'] : null;

        $cpu = $_POST["cpu"] != '' ? $_POST['cpu'] : null;

        $ram = $_POST["ram"] != '' ? $_POST['ram'] : null;

        $gpu = $_POST["gpu"] != '' ? $_POST['gpu'] : null;

        $storage = $_POST["storage"] != '' ? $_POST['storage'] : null;

        $network = $_POST["network"] != '' ? $_POST['network'] : null;

        $os = $_POST["os"] != '' ? $_POST['os'] : null;

        $userId = $_SESSION['user']['user_id'];



        //gesamtpreis ausrechnen
        $sql = "SELECT ROUND(SUM(price), 2) AS total_price
                FROM (
                SELECT price FROM connectors WHERE connectors_id = ? UNION ALL
                SELECT price FROM processor WHERE cpu_id = ? UNION ALL
                SELECT price FROM gpu WHERE gpu_id = ? UNION ALL
                SELECT price FROM network WHERE network_id = ? UNION ALL
                SELECT price FROM operating_system WHERE os_id = ? UNION ALL
                SELECT price FROM storage WHERE storage_id = ? UNION ALL
                SELECT price FROM ram WHERE ram_id = ?
                ) 
                AS combined";

        $stmt = $GLOBALS['conn']->prepare($sql);


        $stmt->bind_param(
            "iiiiiii",
            $connector,
            $cpu,
            $gpu,
            $network,
            $os,
            $storage,
            $ram,
        );

        $stmt->execute();


        //gesamtpreis setzen
        $totalPrice = (($stmt->get_result())->fetch_assoc())['total_price'];




        $sql = "INSERT INTO configurations

        (user_id, price, cpu_id, gpu_id, ram_id, storage_id, os_id, network_id, connectors_id)

        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $GLOBALS['conn']->prepare($sql);


        //Variablen belegen (Values setzen)
        $stmt->bind_param(
            "iiiiiiiii",
            $userId,
            $totalPrice,
            $cpu, 
            $gpu,
            $ram,
            $storage, 
            $os, 
            $network,
            $connector,

        );


        //Ausführen der SQL abfrage (und hoffen dass es klappt)
        $stmt->execute();





    }
}