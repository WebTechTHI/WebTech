<?php
//RINOR STUBLLA ENDE

require_once __DIR__ . '/../model/CartModel.php';

class CartController {
    public function handleRequest() {
        // Session starten, um zu prüfen, ob ein Nutzer eingeloggt ist.
        // Wenn du das schon in der index.php machst, ist diese Zeile optional.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Model instanziieren
        $model = new CartModel();
        
        // Variable für die Warenkorb-Artikel initialisieren
        $cartItems = [];

        // Prüfen: Ist der Nutzer eingeloggt?
        if (isset($_SESSION['user']['user_id'])) {

            // ---- JA, EINGELOGGTER NUTZER (DATENBANK-LOGIK) ----
            $userId = $_SESSION['user']['user_id'];

            // Nutze die neue Funktion, um den kompletten Warenkorb aus der DB zu holen.
            $cartItems = $model->getCartFromDb($userId);

        } else {
            
            // ---- NEIN, GAST (COOKIE-LOGIK) ----
            // Prüfen, ob der Gast Cookies akzeptiert hat und ein Warenkorb-Cookie existiert.
            if (isset($_COOKIE['cookie_consent']) && $_COOKIE['cookie_consent'] === 'accepted' && isset($_COOKIE['mlr_cart'])) {
                
                // Warenkorb-Daten aus dem Cookie holen.
                $cartFromCookie = json_decode($_COOKIE['mlr_cart'], true);
                
                if (is_array($cartFromCookie) && !empty($cartFromCookie)) {
                    // Nutze die neue Funktion, um die Produktdetails für die Cookie-Daten zu laden.
                    $cartItems = $model->getProductsFromCookieData($cartFromCookie);
                }
            }
        }

        // 3) Übergib die $cartItems-Variable (egal woher sie kommt) an die View.
        // Die CartView.php muss nicht geändert werden, da sie einfach nur das $cartItems-Array durchläuft.
        include __DIR__ . '/../view/CartView.php';
    }

//RINOR STUBLLA ENDE

}