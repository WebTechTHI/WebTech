<?php
//RINOR STUBLLA
require_once __DIR__ . '/../db_verbindung.php';

class CartModel {



    //Produkte holen aus variable Cart
    //Als array alle Produkte zurück geben
    public function getProductsForCart($cart) {
        global $conn;
        $products = [];

        foreach ($cart as $pid => $qty) {
            $sql = "
                SELECT 
                    p.product_id, p.name, p.price,
                    (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
                FROM product p
                WHERE p.product_id = ?
            ";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $pid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $row['quantity'] = $qty;
                $products[] = $row;
            }
        }

        return $products;
    }




    
    //Wenn Benutzer eingeloggt ist führen wir das hier aus und holen seine Items aus DB
    //Wird ausgeführt wenn eingeloggter user Sidebar oder CartView öffnet
       public function getCartFromDb($userId) {
        global $conn;
        $sql = "
            SELECT 
                c.quantity, 
                p.product_id, p.name, p.price,
                (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
            FROM cart c
            JOIN product p ON c.product_id = p.product_id
            WHERE c.user_id = ?
        ";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        return $items;
    }





    //Entfernen Produkt aus DB
    //Wenn User eingeloggt ist greifen wir auf sein DB zu => wird aus removeCartItem.php ausgerufen
     public function removeProductFromDb($userId, $productId) {
        global $conn;
        $sql = "DELETE FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
    }




    //Dafür da die Zuständige Menge zu ändern (Wenn user eingeloggt ist)
    //Wird immer von updateCart.php aufgerufen (Das wiederum von Sidebar oder CartView)
 public function addOrUpdateProductInDb($userId, $productId, $quantity) {
        global $conn;

        // 1. Prüfen, ob der Nutzer dieses Produkt bereits in seinem DB-Warenkorb hat.
        $sql = "SELECT cart_id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ii', $userId, $productId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // JA, das Produkt ist schon da.
            // 2. Addiere die neue Menge zur bestehenden Menge.
            $newQuantity = $row['quantity'] + $quantity;
            
            // 3. Aktualisiere den Eintrag in der Datenbank mit der neuen Gesamtmenge.
            $updateSql = "UPDATE cart SET quantity = ? WHERE cart_id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param('ii', $newQuantity, $row['cart_id']);
            $updateStmt->execute();

        } else {
            // NEIN, das Produkt ist neu für diesen Nutzer.
            // 2. Füge einfach einen neuen Eintrag in die Datenbank ein.
            $insertSql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param('iii', $userId, $productId, $quantity);
            $insertStmt->execute();
        }
    }


   



    //Übertragen die Artikel aus Cookie Warenkorb in DB ===> Wenn user sich einloggt !
    //Von Login Controller aufrufen und dann hier ==> für jedes Produkt eintrag in DB erstellen 
    // (mit schhleife addOrUpdateProductInDb aufrufen)
    public function mergeCookieCartWithDbCart($userId, $cookieCart) {
        // Gehe durch jedes Produkt im Cookie-Warenkorb...
        foreach ($cookieCart as $productId => $quantity) {
            // ...und rufe für jedes Produkt die Add/Update-Funktion auf.
            $this->addOrUpdateProductInDb($userId, (int)$productId, (int)$quantity);
        }
    }






    //Wenn Benutzer nicht eingeloggt ist 
    //Wird Aufgerufen wenn Gast den Warenkrob öffnet (Sidebar/getCart) => Müssen daten laden wie Produkte ausschauen da auf cookie
    //nur gespeichert ist welches produkt(id) und menge(int)
    public function getProductsFromCookieData($cookieCart) {
        global $conn;
        $products = [];

        if (empty($cookieCart)) {
            return [];
        }

        // Platzhalter für die IN-Klausel vorbereiten
        $placeholders = str_repeat('?,', count($cookieCart) - 1) . '?';
        $productIds = array_keys($cookieCart);
        
        $sql = "
            SELECT 
                p.product_id, p.name, p.price,
                (SELECT file_path FROM image WHERE product_id = p.product_id ORDER BY sequence_no LIMIT 1) AS image
            FROM product p
            WHERE p.product_id IN ($placeholders)
        ";

        $stmt = $conn->prepare($sql);
        // Parametertypen und Werte binden
        $types = str_repeat('i', count($productIds));
        $stmt->bind_param($types, ...$productIds);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $pid = $row['product_id'];
            if(isset($cookieCart[$pid])) {
                $row['quantity'] = $cookieCart[$pid]; // Menge aus dem Cookie-Array hinzufügen
                $products[] = $row;
            }
        }

        return $products;
    }





    //Zählen der Produkte die mit Sale makeirt sind
    //===> Wird von header gebraucht
    function getSaleCount() {
    global $conn;
    $sql = "SELECT sale FROM product";
    $result = $conn->query($sql);

    $saleCount = 0;
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if ($row['sale'] == 1) {
                $saleCount++;
            }
        }
    }
    return $saleCount;
}

}
//RINOR STUBLLA ENDE

