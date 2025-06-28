<?php
//MICHAEL PIETSCH

require_once "model/ConfiguratorModel.php";
class ConfiguratorController
{
    public function handleRequest($action)
    {

        $model = new ConfiguratorModel();

        if (!isset($_SESSION["user"])) {
            header("Location: index.php?page=login");
            exit;
        }

        switch ($action) {

            
            default:
            case 'configure':
                $displays = $model->getComponents('display');
                $gpus = $model->getComponents('gpu');
                $cpus = $model->getComponents('processor');
                $rams = $model->getComponents('ram');
                $networks = $model->getComponents('network');
                $connectors = $model->getComponents('connectors');
                $features = $model->getComponents('feature');
                $operatingSystems = $model->getComponents('operating_system');
                $storages = $model->getComponents('storage');


                require "view/ConfiguratorView.php";
                break;



            case 'configurationSubmit':
                
                break;
                
        }


        exit;
    }

//MICHAEL PIETSCH ENDE

}