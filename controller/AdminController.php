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
        $cpus   = $model->getComponents('cpu');
        $ram = $model->getComponents('ram');
        $networks = $model->getComponents('network');
        $connectors = $model->getComponents('connector');
        $features = $model->getComponents('feature');
        $operatingSystems = $model->getComponents('os');
        $storages = $model->getComponents('storage');

        if (!isset($_SESSION["user"]) || $_SESSION["user"]['username'] !== 'Admin') {
            header("Location: index.php?page=login");
        exit;
}

        //Die view zeigen
       include 'view/AdminView.php';
    }
}