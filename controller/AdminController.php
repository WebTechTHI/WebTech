<?php
require_once __DIR__ . '/../model/AdminModel.php';

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

        //Alle komponenten laden
        $displays = $model->getComponents('display');
        $gpus = $model->getComponents('gpu');
        $cpus = $model->getComponents('processor');
        $rams = $model->getComponents('ram');
        $networks = $model->getComponents('network');
        $connectors = $model->getComponents('connectors');
        $features = $model->getComponents('feature');
        $operatingSystems = $model->getComponents('operating_system');
        $storages = $model->getComponents('storage');


        switch ($action) {

            //Produkte Hochladen Formular
            case 'upload':

                require 'view/admin/adminPages/createProductsView.php';
                break;



            case 'productList':

                //produktinformationen laden
                $productsRaw = $model->getProducts();

                $products = [];
                foreach ($productsRaw as $p) {
                    $p['images'] = $model->getProductImages($p['product_id']);
                    $p['specs'] = $model->buildSpecifications($p);
                    $products[] = $p;
                }

                require 'view/admin/adminPages/ProductListView.php';
                break;


            //Ändern von Produktdetails
            case 'edit':


                //Informationen zu bestimmten produkt laden, um zu selektieren bei auswahl

                require 'view/admin/adminPages/AlterProductsView.php';
                break;


            //speichert neu angelegte Produkte
            case 'uploadSubmit':
                $model->uploadSubmit();
                require 'view/admin/adminPages/CreateProductsView.php';
                break;


            //Admin Startseite (zur Modusauswahl)
            default:
                require 'view/AdminView.php';
                break;
        }

    }
}