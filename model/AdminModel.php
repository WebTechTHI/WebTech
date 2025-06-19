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
    }

