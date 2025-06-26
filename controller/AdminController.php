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

                //speichert neu angelegte Produkte
            case 'uploadSubmit':
                $model->uploadSubmit();
                require 'view/admin/adminPages/CreateProductsView.php';
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


                //Informationen zu bestimmtem produkt laden, um zu selektieren bei auswahl
                $product = $model->getProductById($_GET['id']);
                $category = $product['category_id'];
                $subcategory = $product['subcategory_id'];


                require 'view/admin/adminPages/AlterProductsView.php';
                break;

            

            //speichert Änderungen an Produkten
            case 'editSubmit':
                $model->editSubmit($_GET['id']);
                header("Location: /index.php?page=admin&action=productList");
                break;

            

            
            //Produkt löschen
case 'delete':

        $model->deleteProduct($_GET['id']);
        header("Location: /index.php?page=admin&action=productList");

    break;


    //Userliste anzeigen
    case "userList":

        $users = $model->getUsers();

        require 'view/admin/adminPages/UserListView.php';
                break;



                //User anzeigen
    case "user":


        $user = $model->getUserById($_GET["user-id"]);

        //lädt bestellungen für angegebene userid
        $orders = $model->getOrdersById($_GET['user-id']);

        require 'view/admin/adminPages/UserView.php';
                break;



                //Orderliste anzeigen
    case "orderList":

        //lädt alle bestellungen
        $orders = $model->getOrders();

        require 'view/admin/adminPages/OrderListView.php';
                break;



                //Order anzeigen
    case "order":
        

        //Lädt bestellungen für angegebene bestellungs-id
        $order = $model->getOrderById($_GET['order-id']);
        require 'view/admin/adminPages/OrderView.php';
                break;


                //speichert änderungen an einer bestellung
        case 'orderStatusChange':

            $model->changeStatus($_GET('order-id'));
            break;

            //Admin Startseite (zur Modusauswahl)
            default:
                require 'view/AdminView.php';
                break;
        }

    }
}