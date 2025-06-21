<?php
require_once __DIR__ . '../../db_verbindung.php';

class AdminModel
{
    public function getComponents($type) {
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

    public function uploadSubmit(){

        /*
        
        HIER WIRD DIE UPLOAD FUNKION GESCHRIEBEN (EINFACH SQL ANFRAGE AN SERVER ZUM SPEICHERN DES PROD.)
        
        */

        echo '<h1>asdsdasadasddasasdasdasdasdadasdasdasd</h1>';
    }
    }

