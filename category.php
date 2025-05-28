<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - Gaming PCs, Laptops & High-End Computer</title>

    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/mystyle.css">

</head>

<body>

 <?php include 'components/header.html'; ?>


<?php
// Verbindungsdaten zur Datenbank
$servername = "mlr-shop.de";
$username = "shopuser";
$password = "12345678";
$dbname = "onlineshop";

// Verbindung herstellen
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Verbindung prüfen
if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

// Produkte abfragen
$sql = "SELECT * FROM product";
$result = mysqli_query($conn, $sql);

// HTML-Ausgabe starten
echo '<div style="display: flex; flex-wrap: wrap; gap: 20px; padding: 20px;">';

if (mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_assoc($result)) {
        echo '
        <div style="border: 1px solid #ccc; border-radius: 10px; padding: 16px; width: 300px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
            <h2>' . htmlspecialchars($row["name"]) . '</h2>
            <p><strong>Kurzbeschreibung:</strong> ' . htmlspecialchars($row["short_description"]) . '</p>
            <p><strong>Preis:</strong> €' . number_format($row["price"], 2, ',', '.') . '</p>
            <a href="/productPages/product.html?id=' . htmlspecialchars($row["product_id"]) . '" 
               style="display: inline-block; margin-top: 10px; padding: 10px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;">
               Zum Produkt
            </a>
        </div>';
    }
} else {
    echo "<p>Keine Produkte gefunden.</p>";
}

echo '</div>';

// Verbindung schließen
mysqli_close($conn);
?>



    
 <?php include 'components/footer.html'; ?>   
</html>