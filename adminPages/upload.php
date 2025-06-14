<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/'; // Zielordner (muss existieren und beschreibbar sein)
    $targetFile = $targetDir . basename($_FILES["image"]["name"]);

    // Prüfen, ob eine Datei hochgeladen wurde
    if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Nur bestimmte Dateitypen zulassen (optional, aber empfohlen)
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($fileType, $allowedTypes)) {
            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                echo "Die Datei " . htmlspecialchars(basename($_FILES["image"]["name"])) . " wurde erfolgreich hochgeladen. <br>";
                echo '<a href="/uploads/'
                    . htmlspecialchars(basename($_FILES["image"]["name"]))
                    . '" target="_blank" rel="noopener noreferrer">'
                    . 'Bild anzeigen</a>';
                echo "<br><a href='/adminPages/sendImages.php' >Zurück zum Upload</a>";
            } else {
                echo "Fehler beim Hochladen der Datei.";
            }
        } else {
            echo "Nur Bildformate (JPG, PNG, GIF, WEBP) sind erlaubt.";
        }
    } else {
        echo "Keine Datei hochgeladen oder Fehler beim Upload.";
    }
}
?>