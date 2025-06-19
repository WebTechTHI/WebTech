<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Login</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">


    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/loginRegistration.css">
    <link rel="stylesheet" href="/assets/css/specialHeader.css">
    <link rel="stylesheet" href="/assets/css/footer.css">

    <script src="/assets/javascript/toggleTheme.js"></script>
</head>

<body class="backgroundpicture darkMode">

    <header>
        <a href="/index.php">
            <img src="/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()">
    </header>



    <?php if (!empty($fehlermeldung)):              //Nur Farbe und Layout für Meldungen    ?>
        <div class="meldung-container meldung-fehler">
            <?= $fehlermeldung ?>
        </div>
    <?php endif; ?>



    <!-- ======= Ab Hier Login Funktionen  ==========-->
    <!-- Überarbeiteter login.php – moderner & professioneller -->
    <form class="userInfoFormlogin" action="?page=login" method="post">

        <h1 class="formTitle" title="Please fill out the blanks">Login</h1>

        <div id="liveClock" class="Clock"></div>

        <h4 class="formSubtitle" title="Please fill out the blanks">Melden Sie sich mit Ihren Zugangsdaten an</h4>

        <div>
            <label for="usernamelogin">Benutzername</label>
            <div>

                <input class="userInformationInput" name="variablefromusername" id="usernamelogin" type="text"
                    placeholder="Benutzername" required>
            </div>
        </div>

        <div>
            <label for="userpasswordlogin">Passwort</label>
            <div>

                <input class="userInformationInput" name="variableformpassword" id="userpasswordlogin" type="password"
                    placeholder="Passwort" required>
            </div>
        </div>

        <input class="submitButton" type="submit" value="Anmelden">


        <label
            title="Wenn sie noch keinen einen Account haben können sie mit dem Registrieren Button einen erstellen!">Sie
            haben noch keinen Account?</label>
        <a class="directionbutton" href="/index.php?page=registration">Zur Registrierung</a>

    </form>



    <footer>
        <nav>
            <p>© 2025 MLR | <a href="/index.php?page=about">Impressum</a></p>
        </nav>
    </footer>



    <!--JavaScript hier noch einfügen-->
    <script src="/assets/javascript/Validierung_Login.js"></script>
    <script src="/assets/javascript/uhrzeit.js"></script>

</body>

</html>