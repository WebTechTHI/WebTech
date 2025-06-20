<!DOCTYPE html>
<html lang="de">

<link rel="stylesheet" href="../assets/css/mystyle.css">
<link rel="stylesheet" href="../assets/css/specialHeader.css">
<link rel="stylesheet" href="../assets/css/footer.css">
<script src="/assets/javascript/toggleTheme.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Admin - Start</title>
    <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">
</head>

<body>
    <header>
        <a href="../index.html">
            <img src="../assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()">
    </header>

    <h1>
        Admin Startseite
    </h1>
    <p>
        Diese Seite dient als index für Admin funktionen, wie Produkte hinzufügen, löschen, benutzer verwalten, ...
    </p>

    <a href="sendImages.php" style="color: blue;">
        bilder hochladen
    </a>
    <a href="showImages.php" style="color: blue;">
        bilder ansehen
    </a>
    <a href="uploadProdWIP/uploadProduct.php" style="color: blue;">
        Produkte hinzufügen
    </a>
    <a href="uploadProdWIP/showProduct.php" style="color: blue;">
        Produkte ansehen
    </a>

    <footer style="margin-top: auto;">

        <nav>
            <p>© 2025 MLR | <a href="about.html"><i>Impressum</i></a></p>
        </nav>
    </footer>
</body>

</html>