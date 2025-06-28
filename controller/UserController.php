<?php
//LAURIN SCHNITZER 
require_once "model/UserModel.php";

class UserController
{

    public function handleRequest()
    {

        $model = new UserModel();

        // Standard-Werte, falls keine DB-Daten vorhanden (Werden aktuell aber nie angezeigt weil wir nur eingeloggt auf userView zugreifen können 
        // --> Persönlichen daten werden also immer direkt mitgeladen und überschreiben die Default Data hier)
        $defaultData = [
            "username" => "MaxMustermann",
            "richtiger_name" => "Max",
            "password" => "1234567890",
            "land" => "Deutschland",
            "stadt" => "Ingolstadt",
            "email" => "MaxMustermann@email.de",
        ];

        // Prüft, ob User eingeloggt ist
        if (isset($_SESSION['user']['user_id'])) {

            $user_id = $_SESSION['user']["user_id"];


            // Wenn Formular abgeschickt --> Daten speichern
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
                $username = trim($_POST["username"]);
                $richtiger_name = trim($_POST["richtiger_name"]);
                $land = trim($_POST["land"]);
                $stadt = trim($_POST["stadt"]);
                $email = trim($_POST["email"]);
                $password_input = trim($_POST["password"]);

                //// SERVERSEITIGE Validierung (gegen Manipulation)
                // == Sicher auf server seite für eingabe das die korrekt sind falls jemand js ausschaltet !!! =========
                if (strlen($username) < 5 || !preg_match('/[A-Z]/', $username) || !preg_match('/[a-z]/', $username)) {
                    $_SESSION["fehlermeldung"] = "Benutzername erfüllt nicht Anforderungen !";
                    header("Location: /index.php?page=user");
                    exit;
                }


                if (!empty($password_input) && strlen($password_input) < 10) {
                    $_SESSION["fehlermeldung"] = "Passwort muss mind. 10 zeichen haben !";
                    header("Location: /index.php?page=user");
                    exit;
                }


                // Passwort nur hashen, wenn neu eingegeben wird
                if (!empty($password_input)) {
                    $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);
                } else {
                    // Sonst altes Passwort behalten
                    $hashedPassword = $model->getCurrentPassword($user_id);
                }


                // Prüft, ob Username schon bei anderem User existiert falls er das ändern möchte
                if ($model->usernameExists($username, $user_id)) {
                    $_SESSION["fehlermeldung"] = "Dieser Benutzername ist bereits vergeben";
                } else {
                    if ($model->updateUserData($user_id, $username, $hashedPassword, $richtiger_name, $land, $stadt, $email)) {
                        $_SESSION["erfolgsmeldung"] = "Daten wurden erfolgsreich aktualisiert!";
                    } else {
                        $_SESSION["fehlermeldung"] = "Fehler beim Speichern!!!";
                    }
                }
                // Immer Redirect nach POST 
                header("Location: /index.php?page=user");
                exit;

            }

            // Lädt aktuelle Daten aus DB für View = Nach jedem Update oder Seitenaufruf hole ich die aktuellen User-Daten aus der Datenbank
            //                                       und gebe sie an die View weiter. So sieht der User IMMER die aktuellen Infos. Das stelle ich mit getUserData() sicher
            $defaultData = $model->getUserData($user_id) ?: $defaultData;

        }

        // Zeigt View nachdem alles fertig
        require "view/UserView.php";
        exit;
    }
//LAURIN SCHNITZER ENDE
}