<?php
require_once 'db_verbindung.php';

class AdminModel
{
    public function getComponents($type){



    $sql = "SELECT * FROM $type";
    $stmt = $GLOBALS['conn']->prepare($sql);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $stmt->close();

    return $result;
}
    }

