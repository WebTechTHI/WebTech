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
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png" onclick="toggleTheme()">
    </header>

   




<!-- =====Ab hier PHP noch unfertig !!!!  =============-->
<?php
    //IMMER als erstes Session starten
    session_start();

    //DB verbindung
    include '../db_verbindung.php';

    //Prüfn ob formular abgeschickt per Post
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        //Eingaben absichern (trim = whitespace entfernen leerzeichen)
        $username = trim($_POST['variablefromusername']);
        $password = $_POST['variableformpassword'];

        //Richtigen Nutze aus DB lesen
        $sql = "SELECT user_id, username, password FROM kontakte WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();


        $result = $stmt->get_result();

        //Prüfen ob benutzer existiert
        if ($result->num_rows === 1){
            $user = $result->fetch_assoc();


            //Passwort prüfen und entziffern !!
            if(password_verify($password, $user['password'])) {

                //Wenn login jetzt erfolgreich ist
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['username'] = $user['username'];

                echo "Willkommen, " . htmlspecialchars($user['username']) . "!<br>";
                echo "Ihre Benutzer ID lautet: " . $_SESSION['user_id'];

                //=================Hier noch später weiterleiten auf user.php oder sprüche einfügen / sound beim anmelden einfügen als erfolg ==============
            }else {
                echo "Falsches Passwort !";
            }
        } else{
            echo "Benutzer existiert nicht !";
        }

        $stmt->close();
        $conn->close();

    }

?>


    <!-- ======= Ab Hier Login Funktionen  ==========-->
 <!-- Überarbeiteter login.php – moderner & professioneller -->
<form class="userInfoFormlogin" action="login.php" method="post">

    <h1 class="formTitle" title="Please fill out the blanks">🔐Login</h1>

        <div id="liveClock" class="Clock"></div>

    <h4 class="formSubtitle" title="Please fill out the blanks">Melden Sie sich mit Ihren Zugangsdaten an</h4>

    <div>
        <label for="usernamelogin">Benutzername</label>
        <div>
           
            <input class="userInformationInput" name="variablefromusername" id="usernamelogin" type="text" placeholder="Benutzername" required>
        </div>
    </div>

    <div>
        <label for="userpasswordlogin">Passwort</label>
        <div>
          
            <input class="userInformationInput" name="variableformpassword" id="userpasswordlogin" type="password" placeholder="Passwort" required>
        </div>
    </div>

    <input class="submitButton" type="submit" value="Anmelden">

    
    <label  title="Wenn sie noch keinen einen Account haben können sie mit dem Registrieren Button einen erstellen!">Sie haben noch keinen Account?</label>
    <a class="directionbutton" href="registration.php">Zur Registrierung</a>
</form>



    <footer>
        
        <nav>
            <p>© 2025 MLR | <a href="about.php">Impressum</a></p>
        </nav>
    </footer>


      <!--JavaScript hier noch einfügen-->
      <script src="/assets/javascript/Validierung_Login.js"></script>
      <script src="/assets/javascript/uhrzeit.js"></script>

</body>

</html>