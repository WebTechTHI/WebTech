<!DOCTYPE html>
<html lang="de">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Registrierung</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/loginRegistration.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <link rel="stylesheet" href="/assets/css/specialHeader.css">

    <script src="/assets/javascript/toggleTheme.js"></script>

</head>

<body class="backgroundpicture">

    <header>
        <a href="/index.php">
            <img src="/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()">
    </header>






    <!-- ============ PHP ab Hier Audio Meldung !!==================-->

    <?php
    // Zeigt eine Fehlermeldung + spielt Fehler-Sound ab, wenn $fehlermeldung gesetzt ist
    if (isset($fehlermeldung)): ?>
        <div class='meldung-container meldung-fehler'>
            <?= $fehlermeldung ?>
            <audio autoplay>
                <source src="/assets/sounds/registerError.mp3" type="audio/mpeg">
            </audio>
        </div>
    <?php endif; ?>


    <?php  
    // Zeigt eine Erfolgsmeldung + spielt Erfolgs-Sound ab, wenn $erfolgsmeldung gesetzt ist
    if (isset($erfolgsmeldung)): ?>
        <div class='meldung-container meldung-erfolg'>
            <?= $erfolgsmeldung ?>
            <audio autoplay>
                <source src="/assets/sounds/registerSuccess.mp3" type="audio/mpeg">
            </audio>
        </div>
    <?php endif; ?>




    <!-- Ab Hier Registrieren Formular -->
    <form class="userInfoFormregistration" action="?page=registration" method="post">
        <h1 class="formTitle" title="Please fill out the blanks">📝 Registrierung</h1>

        <div id="liveClock" class="Clock"></div>

        <h4 class="formSubtitle" title="Please fill out the blanks">Bitte erstellen Sie ein Benutzerkonto</h4>

        <div class="hinweisBlock">
            <p><strong>Hinweis zur Eingabe:</strong></p>
            <ul>
                <li>Benutzername: mind. 5 Zeichen</li>
                <li>Mindestens ein Groß- und Kleinbuchstabe im Benutzernamen</li>
                <li>Passwort: mind. 10 Zeichen</li>
                <li>Passwort-Wiederholung muss exakt übereinstimmen</li>
            </ul>
        </div>

        <div>
            <label for="usernameregistration">Benutzername</label>
            <div>

                <input class="userInformationInput" type="text" name="fromusernameregistration"
                    id="usernameregistration" placeholder="Benutzername" required>
            </div>
        </div>

        <div>
            <label for="userpasswordregistration">Passwort</label>
            <div>

                <input class="userInformationInput" type="password" name="fromuserpasswordregistration"
                    id="userpasswordregistration" placeholder="Passwort" required>
            </div>
        </div>


        <div>
            <label for="userpasswordregistrationconfirmation">Passwort bestätigen</label>
            <div>

                <input class="userInformationInput" type="password" name="fromuserpasswordregistrationconfirmation"
                    id="userpasswordregistrationconfirmation" placeholder="Passwort bestätigen" required>
            </div>
        </div>

        <!-- Submit-Button (Daten an Server schicken) -->
        <input class="submitButton" type="submit" value="Registrieren">



        <label title="Wenn sie bereits einen Account haben können sie mit dem Login Button sich einloggen!">Sie haben
            bereits ein Konto?</label>

        <!-- Abbrechen-Button (Zurück zu Login Maske) -->
        <a class="directionbutton" href="?page=login"
            title="Wenn sie bereits einen Account haben können sie mit dem Login Button sich einloggen!">Zum Login</a>

        <!-- Abbrechen-Button (Zurück zu Startseite Maske) -->
        <a class="directionbutton" href="/index.php"
            onclick='confirmAction("Sind Sie sicher, dass Sie die Registrierung abbrechen möchten? Alle Änderungen gehen verloren.")'>Zur
            Startseite</a>
    </form>


    <footer>
        <nav>
            <p>© 2025 MLR | <a href="/index.php?page=about">Impressum</a></p>
        </nav>
    </footer>


    <!--JavaScript hier noch einfügen-->
    <script src="/assets/javascript/Validierung_Registration.js"></script>
    <script src="/assets/javascript/uhrzeit.js"></script>
</body>

</html>