<?php
//Verbindungsdaten zur Datenbank aufbauen
$servername = "mlr-shop.de";
$username = "shopuser";
$password = "12345678";
$dbname = "onlineshop";

//Verbindung herstellen
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Zeichensatz utf8mb4 sorgt dafür, dass Umlaute und Emojis richtig funktionieren.
mysqli_set_charset($conn, "utf8mb4");

//Verbindung prüfen oder fehlgeschlagen
if(!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}
?>