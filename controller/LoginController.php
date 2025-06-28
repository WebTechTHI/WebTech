<?php
//LAURIN SCHNITZER

require_once "model/LoginModel.php";
require_once "model/CartModel.php"; // CartModel hier einbinden, damit wir die Merge-Funktion nutzen können.

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


                  
            $model = new LoginModel();
            $result = $model->checkLogin($username, $password);

            // Wenn Fehler, speichere Fehlermeldung
            if (isset($result["error"])) {
                $fehlermeldung = $result["error"];
            } else {

                // Speichere User-Daten in Session
                $_SESSION['user'] = $model->getUserData($result["user_id"]);



                // ----- HIER STARTET DIE MERGE-LOGIK -----

                // 1. Prüfen, ob ein Cookie-Warenkorb überhaupt existiert.
                if (isset($_COOKIE['mlr_cart']) && !empty($_COOKIE['mlr_cart'])) {

                    //  Cookie-Daten holen und in ein PHP-Array umwandeln.
                    $cookieCart = json_decode($_COOKIE['mlr_cart'], true);

                   
                    if (is_array($cookieCart) && !empty($cookieCart)) {

                        
                        $cartModel = new CartModel();

                        
                        $cartModel->mergeCookieCartWithDbCart($_SESSION['user']['user_id'], $cookieCart);

                        // Den lokalen Cookie löschen, da die Daten jetzt sicher in der Datenbank sind.
                        //    Wir setzen das Ablaufdatum in die Vergangenheit.
                        setcookie('mlr_cart', '', time() - 3600, '/');
                    }
                }
                

                if (isset($_SESSION['redirect_after_login'])) {
                    
                    $redirectTo = $_SESSION['redirect_after_login'];

                    // WICHTIG: Wir löschen die Variable aus der Session, damit sie beim nächsten Login nicht wieder verwendet wird.
                    unset($_SESSION['redirect_after_login']);

                    // Wir leiten den Nutzer zur gespeicherten URL weiter (z.B. zur Kasse). (Wo der user war => kasse)
                    header("Location: " . $redirectTo);
                    exit;
                } else {
                    // Nein, es gibt keine spezielle URL. Wir leiten zum Standardziel (Benutzerprofil) weiter.
                    // Optionale Erfolgsmeldung
                    $_SESSION["erfolgsmeldung"] = "Willkommen, " . htmlspecialchars($result['username']) . "!<br>Ihre Benutzer ID lautet: " . $result['user_id'];
                    header("Location: /index.php?page=user");
                    exit;
                }
            }
        }
        // Optionale Erfolgsmeldung


        // Weiterleitung ins Benutzerprofil


        // Zeige View (immer, egal ob GET oder POST)
        require "view/LoginView.php";
    }

//LAURIN SCHNITZER ENDE

}