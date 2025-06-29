<!-- LAURIN SCHNITZER -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Logout</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon" />

    <!-- Zentrale CSS -->
    <link rel="stylesheet" href="/assets/css/colors.css" />
    <link rel="stylesheet" href="/assets/css/loginRegistration.css" />
    <link rel="stylesheet" href="/assets/css/specialHeader.css" />
    <link rel="stylesheet" href="/assets/css/footer.css" />

    <!-- Dark Mode Toggle -->
    <script src="/assets/javascript/toggleTheme.js"></script>
</head>


<body class="backgroundpicture">

    <!-- HEADER -->
    <header>
        <a href="/index.php">
            <img src="/assets/images/logo/logoDarkmode.png" alt="MLR Logo" class="logoHeader" />
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()" />
    </header>




    <!-- LOGOUT-Meldung -->
    <form class="userInfoFormlogin">
        <h1 class="formTitle">Logout erfolgreich</h1>

        <div id="liveClock" class="Clock"></div>

        <h4 class="formSubtitle">Sie wurden erfolgreich abgemeldet.</h4>

        <p class="logoutMessage">Vielen Dank für Ihren Besuch. Wir hoffen, Sie bald wiederzusehen!</p>

        <div class="basketActions">
            <a class="directionbutton" href="/index.php?page=login">Zum Login</a>
            <a class="directionbutton" href="/index.php">Zur Startseite</a>
        </div>
    </form>

    <!-- FOOTER -->
    <footer>
        <nav>
            <p>© 2025 MLR | <a href="/index.php?page=about">Impressum</a></p>
        </nav>
    </footer>


    <script src="/assets/javascript/uhrzeit.js"></script>

</body>

</html>