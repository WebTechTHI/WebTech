<?php
//Verbindungsdaten zur Datenbank aufbauen
$servername = "mlr-shop.de";
$username = "shopuser";
$password = "12345678";
$dbname = "onlineshop";

//Verbindung herstellen
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Verbindung prüfen oder fehlgeschlagen
if(!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}
?>