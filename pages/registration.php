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

<body class="backgroundpicture darkMode">

    <header>
        <a href="/index.php">
            <img src="/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png" onclick="toggleTheme()">
    </header>






<!--Ab hier PHP noch unfertig !!!! -->
    <?php
    $kontakte = [];

    if(file_exists('kontakte.txt')){
            $text = file_get_contents('kontakte.txt', true);
           $kontakte = json_decode($text, true);
    }

    if(isset($_POST['fromusernameregistration']) && isset($_POST['fromuserpasswordregistration'])){
        echo 'Hallo ' . $_POST['fromusernameregistration'] . ' sie sind Angemeldet!';
        $neueKontakte = [
            'fromusernameregistration' => $_POST['fromusernameregistration'],
            'fromuserpasswordregistration' => $_POST['fromuserpasswordregistration']
        ];
            array_push($kontakte, $neueKontakte);
            file_put_contents('kontakte.txt', json_encode($kontakte, JSON_PRETTY_PRINT));

    }

?>









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
    <a class="directionbutton" href="/index.php" onclick='confirmAction("Sind Sie sicher, dass Sie die Registrierung abbrechen möchten? Alle Änderungen gehen verloren.")'>Zur Startseite</a>
</form>




        

    <footer>
        <nav>
            <p>© 2025 MLR | <a href="about.php">Impressum</a></p>
        </nav>
    </footer>







      <!--JavaScript hier noch einfügen-->
      <script src="/assets/javascript/Validierung_Registration.js"></script>
      <script src="/assets/javascript/uhrzeit.js"></script>
</body>

</html>