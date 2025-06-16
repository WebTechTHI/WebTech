<?php
require_once "model/RegistrationModel.php";

class RegistrationController
{
    public function handleRequest()
    {
        

        $fehlermeldung = null;
        $erfolgsmeldung = null;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // ==> Validierung in JavaScript
            $username = trim(string: $_POST["fromusernameregistration"]);
            $password = $_POST["fromuserpasswordregistration"];


            $model = new RegistrationModel();
            $result = $model->registerUser($username, $password);

            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];

            } else {
                $_SESSION['user'] = $model->getUserData($result["success"]); // zum setzen der benutzerinformationen, gleiches für login klasse noch anwenden. funktion in userFunctions definiert

                $erfolgsmeldung = "Registrierung hat geklappt! Benutzer ID ist:  " . $result['success'];
            }
        }
        require "view/RegistrationView.php";
    }
}