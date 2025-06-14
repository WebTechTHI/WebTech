<?php
require_once "model/UserModel.php";

class UserController
{

    public function handleRequest()
    {

        session_start();
        $model = new UserModel();

        $defaultData = [
            "username" => "MaxMustermann",
            "richtiger_name" => "Max",
            "password" => "1234567890",
            "land" => "Deutschland",
            "stadt" => "Ingolstadt",
            "email" => "MaxMustermann@email.de",
        ];

        if (isset($_SESSION["user_id"])) {
            $user_id = $_SESSION["user_id"];

            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = trim($_POST["username"]);
                $richtiger_name = trim($_POST["richtiger_name"]);
                $land = trim($_POST["land"]);
                $stadt = trim($_POST["stadt"]);
                $email = trim($_POST["email"]);
                $password_input = trim($_POST["password"]);

                if (!empty($password_input)) {
                    $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);
                } else {
                    $hashedPassword = $model->getCurrentPassword($user_id);
                }


                if ($model->usernameExists($username, $user_id)) {
                    $_SESSION["fehlermeldung"] = "Dieser Benutzername ist bereits vergeben";
                } else {
                    if ($model->updateUserData($user_id, $username, $hashedPassword, $richtiger_name, $land, $stadt, $email)) {
                        $_SESSION["erfolgsmeldung"] = "Daten wurden erfolgsreich aktualisiert!";
                    } else {
                        $_SESSION["fehlermeldung"] = "Fehler beim Speichern!!!";
                    }
                }
                header("Location: /index.php?page=user");
                exit;

            }

            $defaultData = $model->getUserData($user_id) ?: $defaultData;

        }

        require "view/UserView.php";
        exit;
    }
}