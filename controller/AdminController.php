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


        switch ($action) {

            //Produkte Hochladen Formular
            case 'upload':

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


                require 'view/admin/adminPages/createProducts.php';
                break;



            case 'productList':

                //produktinformationen laden
                $productsRaw = $model->getProducts("none");

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


                //Ausgewählte komponenten und inputs des zu bearbeitenden produkts abfragen
                $product = $model->getProducts($_GET['id']);
                var_dump($product[0]['product_id']);

                require 'view/admin/adminPages/AlterProducts.php';
                break;


            //speichert neu angelegte Produkte
            case 'uploadSubmit':
                $model->uploadSubmit();
                require 'view/admin/adminPages/CreateProducts.php';
                break;


            //Admin Startseite (zur Modusauswahl)
            default:
                require 'view/AdminView.php';
                break;
        }

    }
}