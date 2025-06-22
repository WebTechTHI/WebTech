<?php
// Lädt das Model für Login rein
require_once "model/LoginModel.php";
require_once "model/CartModel.php"; // NEU: CartModel hier einbinden, damit wir die Merge-Funktion nutzen können.

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
                // HIER IST DER LOGIN ERFOLGREICH - der perfekte Ort für den Merge.

                // Speichere User-Daten in Session
                $_SESSION['user'] = $model->getUserData($result["user_id"]);

                // ----- NEU: HIER STARTET DIE MERGE-LOGIK -----
                
                // 1. Prüfen, ob ein Cookie-Warenkorb überhaupt existiert.
                if (isset($_COOKIE['mlr_cart']) && !empty($_COOKIE['mlr_cart'])) {
                    
                    // 2. Cookie-Daten holen und in ein PHP-Array umwandeln.
                    $cookieCart = json_decode($_COOKIE['mlr_cart'], true);

                    // 3. Sicherstellen, dass die Daten gültig sind.
                    if (is_array($cookieCart) && !empty($cookieCart)) {
                        
                        // 4. CartModel instanziieren, um auf die DB-Funktionen zuzugreifen.
                        $cartModel = new CartModel();
                        
                        // 5. Die Merge-Funktion aufrufen. Wir übergeben die ID des gerade eingeloggten Nutzers
                        //    und den Inhalt des Cookie-Warenkorbs.
                        $cartModel->mergeCookieCartWithDbCart($_SESSION['user']['user_id'], $cookieCart);

                        // 6. Den lokalen Cookie löschen, da die Daten jetzt sicher in der Datenbank sind.
                        //    Wir setzen das Ablaufdatum in die Vergangenheit.
                        setcookie('mlr_cart', '', time() - 3600, '/');
                    }
                }
                // ----- NEU: ENDE DER MERGE-LOGIK -----


                // Optionale Erfolgsmeldung
                $_SESSION["erfolgsmeldung"] = "Willkommen, " . htmlspecialchars($result['username']) . "!<br>Ihre Benutzer ID lautet: " . $result['user_id'];
                
                // Weiterleitung ins Benutzerprofil
                header("Location: /index.php?page=user");
                exit;
            }
        }

        // Zeige View (immer, egal ob GET oder POST)
        require "view/LoginView.php";
    }
}