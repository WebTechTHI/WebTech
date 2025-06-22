<?php
require_once 'model/AdminModel.php';

class AdminController
{
    public function handleRequest($action)
    {

        //Abfrage, ob der Admin angemeldet ist, falls nicht zurück zum login
        if ($_SESSION["user"]['role_id'] !== 1) {
            header("Location: index.php?page=login");
            exit;
        }



        //AdminModel laden
        $model = new AdminModel();



        // alle Komponenten Laden (-> beim Neuanlegen von Produkten später auswählbar machen)
        $displays = $model-> getComponents('display');
        $gpus = $model -> getComponents('gpu');
        $cpus = $model -> getComponents('processor');
        $rams = $model -> getComponents('ram');
        $networks = $model -> getComponents('network');
        $connectors = $model -> getComponents('connectors');
        $features = $model -> getComponents('feature');
        $operatingSystems = $model -> getComponents('operating_system');
        $storages = $model -> getComponents('storage');


        switch ($action) {
            
            //Produkte Hochladen Formular
            case 'upload':
                require 'view/admin/adminPages/';
                break;


            //Ändern von Produktdetails
            case 'edit':                   
                require 'view/admin/adminPages/';
                break;


            //speichert änderungen an produkten
            case 'uploadSubmit':           
                $model -> uploadSubmit();
                break;


            //Admin Startseite (zur Modusauswahl)
            default:
                require 'view/AdminView.php';
                break;
        }

    }
}