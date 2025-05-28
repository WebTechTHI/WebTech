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

 <?php include __DIR__ . '/../components/header.html'; ?>


<?php
// Verbindungsdaten zur Datenbank
$servername = "localhost";
$username = "shopuser";
$password = "12345678";
$dbname = "onlineshop";

// Verbindung herstellen
$conn = new mysqli($servername, $username, $password, $dbname);

// Verbindung prüfen
if ($conn->connect_error) {
    die("Verbindung fehlgeschlagen: " . $conn->connect_error);
}

// Beispiel SELECT-Anweisung (kannst du ändern)
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

if ($result === false) {
    echo "Fehler bei der Abfrage: " . $conn->error;
} else {
    // Daten ausgeben
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            print_r($row);
            echo "<br>";
        }
    } else {
        echo "Keine Ergebnisse gefunden.";
    }
}

// Verbindung schließen
$conn->close();
?>


    
 <?php include __DIR__ . '/../components/footer.html'; ?>   
</html>