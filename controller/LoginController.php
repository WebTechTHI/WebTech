<?php
// Lädt das Model für Login rein
require_once "model/LoginModel.php";

class LoginController
{
    public function handleRequest()
    {

        $fehlermeldung = "";
        // Prüfe ob Formular abgeschickt wurde
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // Hole Benutzereingaben aus Formular            
            $username = trim($_POST["variablefromusername"]);
            $password = $_POST["variableformpassword"];


            // Erstelle LoginModel und prüfe Login (CheckLogin Funktion wird in Model gemacht)         
            $model = new LoginModel();
            $result = $model->checkLogin($username, $password);

            // Wenn Fehler, speichere Fehlermeldung
            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];
            } else {

                // Speichere User-Daten in Session
                $_SESSION['user'] = $model->getUserData($result["user_id"]);  //Setzen von Benutzerinformationen in 'user'-array

                // Optionale Erfolgsmeldung
                $_SESSION["erfolgsmeldung"] = "Willkommen, " . htmlspecialchars($result['username']) . "!<br>Ihre Benutzer ID lautet: " . $result['user_id'];
                
//                           Result enthält user id (und auch username) als Schlüssel das brauchen wir um getUserData aufzurufen mit genau der ID 
//„Ich nehme aus $result nur die user_id.
//Die gebe ich an getUserData — und DAS Ergebnis (komplettes User-Array) speichere ich als $_SESSION['user'].
//So habe ich ab sofort ALLE User-Daten in der Session gespeichert, nicht nur die ID.“


                // Weiterleitung ins Benutzerprofil
                header("Location: /index.php?page=user");
                exit;
            }
        }

        // Zeige View (immer, egal ob GET oder POST)
        require "view/LoginView.php";
    }
}