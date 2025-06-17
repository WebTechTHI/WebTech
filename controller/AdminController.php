<?php
require_once 'model/AdminModel.php';

class AdminController
{
    public function handleRequest()
    {
        $model = new AdminModel();

        // alle Komponenten Laden (-> beim Neuanlegen von Produkten später auswählbar machen)
        $displays   = $model->getComponents('display');
        $gpus  = $model->getComponents('gpu');
        $cpus   = $model->getComponents('processor');
        $ram = $model->getComponents('ram');
        $networks = $model->getComponents('network');
        $connectors = $model->getComponents('connectors');
        $features = $model->getComponents('feature');
        $operatingSystems = $model->getComponents('operating_system');
        $storages = $model->getComponents('storage');


        //Abfrage, ob der Admin angemeldet ist, falls nicht zurück zum logina
        if ($_SESSION["user"]['role_id'] !== 1) {
            header("Location: index.php?page=login");
        exit;
}

        //Die view zeigen
       include 'view/AdminView.php';
    }
}