<!DOCTYPE html>
<html lang="de">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Registrierung</title>
    <link rel="icon" href="/WebTech/assets/images/logo/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="/WebTech/assets/css/colors.css">

    <link rel="stylesheet" href="/WebTech/assets/css/loginRegistration.css">

    <link rel="stylesheet" href="/WebTech/assets/css/footer.css">
    <link rel="stylesheet" href="/WebTech/assets/css/specialHeader.css">
   
    <script src="/WebTech/assets/javascript/toggleTheme.js"></script>
    
</head>

<body class="backgroundpicture darkMode">

    <header>
        <a href="/WebTech/index.php">
            <img src="/WebTech/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/WebTech/assets/images/icons/darkmode-btn.png" onclick="toggleTheme()">
    </header>






    <!-- Ab Hier Registrieren Formular -->
   <form class="userInfoFormregistration" action="registration.php" method="post">
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
        <div >
            
            <input class="userInformationInput" type="text" name="fromusernameregistration" id="usernameregistration" placeholder="Benutzername" required>
        </div>
    </div>

    <div>
        <label for="userpasswordregistration">Passwort</label>
        <div >
           
            <input class="userInformationInput" type="password" name="fromuserpasswordregistration" id="userpasswordregistration" placeholder="Passwort" required>
        </div>
    </div>

  
    <div>
        <label for="userpasswordregistrationconfirmation">Passwort bestätigen</label>
        <div >
           
            <input class="userInformationInput" type="password" name="fromuserpasswordregistrationconfirmation" id="userpasswordregistrationconfirmation" placeholder="Passwort bestätigen" required>
        </div>
    </div>

            <!-- Submit-Button (Daten an Server schicken) -->  
    <input class="submitButton" type="submit" value="Registrieren">

    

    <label  title="Wenn sie bereits einen Account haben können sie mit dem Login Button sich einloggen!">Sie haben bereits ein Konto?</label>

          <!-- Abbrechen-Button (Zurück zu Login Maske) -->
    <a class="directionbutton" href="login.php"  title="Wenn sie bereits einen Account haben können sie mit dem Login Button sich einloggen!">Zum Login</a>
  
    <!-- Abbrechen-Button (Zurück zu Startseite Maske) -->
    <a class="directionbutton" href="/WebTech/index.php" onclick='confirmAction("Sind Sie sicher, dass Sie die Registrierung abbrechen möchten? Alle Änderungen gehen verloren.")'>Zur Startseite</a>
</form>




        

    <footer>
        <nav>
            <p>© 2025 MLR | <a href="about.php">Impressum</a></p>
        </nav>
    </footer>







      <!--JavaScript hier noch einfügen-->
      <script src="/WebTech/assets/javascript/Validierung_Registration.js"></script>
      <script src="/WebTech/assets/javascript/uhrzeit.js"></script>
</body>

</html>