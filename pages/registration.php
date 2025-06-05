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






<!-- ======= Ab hier PHP !!!! ======= -->
    <?php
    session_start();
    include '../db_verbindung.php';
   
    //Prüfung ob fehld existiert mit iesset und mit !empty das es auch nicht leer ist
   if (
        isset($_POST['fromusernameregistration']) && isset($_POST['fromuserpasswordregistration'])
    && !empty($_POST['fromusernameregistration']) && !empty($_POST['fromuserpasswordregistration'])
   ) {

    //Eingaben aus Formular jetz in php variablen speichern
    $username = $_POST['fromusernameregistration'];
    $password = $_POST['fromuserpasswordregistration'];

    //Passwort hashen (sicherheit!!!)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    

    
    //========= Hier noch prüfung ob es Benutzername schon in Datenbank gibt !! ===========
    $check_sql = "SELECT username FROM kontakte WHERE username = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $username);
    $check_stmt->execute();
    $check_stmt->store_result();

    if($check_stmt-> num_rows > 0) {
        $fehlermeldung = "Achtung! Dieser Benutzername ist leider schon vergeben :(";
     } else {
        //SQL statement (prepared statement) 1. sql statement vorbereiten dann 2 strings einbinden
        $stmt = $conn->prepare("INSERT INTO kontakte (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hashedPassword);



        //Ausführen von sql statement (stmt) und Ergebnis prüfen
            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;                //user ID aus DB holen
                $_SESSION['user_id'] = $user_id;            // in Session speichern (für Warenkorb später wichtig !)
                $erfolgsmeldung = "Registrierung hat geklappt ! Benutzer ID ist: " . $user_id;
            }   
            else {
                echo "Fehler !: " . $stmt->error;
            }
            $stmt->close();
            
        }
        $check_stmt->close();
        $conn->close();

       }      else {
                 echo "";
         }



   ?>


   <?php        //Hier werden CSS/ Formatierung von der jeweiligen Meldung festgelegt wenn diese aufgerufen wird im code darüber !!!
   if   (isset($fehlermeldung)): ?>
        <div class='meldung-container meldung-fehler'>
            <?= $fehlermeldung ?>
            <audio autoplay>
                <source src="/assets/sounds/registerError.mp3" type="audio/mpeg">
            </audio>
        </div>
    <?php endif; ?>


    <?php    if   (isset($erfolgsmeldung)):       //Wenn erfolgreich angemeldet wurde dann wird wird erfolgsmeldung hier geworfen mit Audio :) !!!!!       ?>
        <div class= 'meldung-container meldung-erfolg'>
            <?= $erfolgsmeldung ?>
            <audio autoplay>
                <source src="/assets/sounds/registerSuccess.mp3" type="audio/mpeg">
            </audio>
           </div>
    <?php endif; ?>    



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