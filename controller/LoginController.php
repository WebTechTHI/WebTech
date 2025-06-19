<?php
require_once "model/LoginModel.php";

class LoginController
{
    public function handleRequest()
    {

        $fehlermeldung = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["variablefromusername"]);
            $password = $_POST["variableformpassword"];


            $model = new LoginModel();
            $result = $model->checkLogin($username, $password);

            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];
            } else {
                
                $_SESSION['user'] = $model->getUserData($result["user_id"]);  //Setzen von Benutzerinformationen in 'user'-array

                $_SESSION["erfolgsmeldung"] = "Willkommen, " . htmlspecialchars($result['username']) . "!<br>Ihre Benutzer ID lautet: " . $result['user_id'];

                header("Location: /index.php?page=user");
                exit;
            }
        }

        require "view/LoginView.php";
    }
}