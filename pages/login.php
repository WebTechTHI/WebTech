<!DOCTYPE html>
<html lang="de">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Login</title>
    <link rel="icon" href="/WebTech/assets/images/logo/favicon.png" type="image/x-icon">


    <link rel="stylesheet" href="/WebTech/assets/css/colors.css">

    
    <link rel="stylesheet" href="/WebTech/assets/css/loginRegistration.css">


    <link rel="stylesheet" href="/WebTech/assets/css/specialHeader.css">
    <link rel="stylesheet" href="/WebTech/assets/css/footer.css">
  
    <script src="/WebTech/assets/javascript/toggleTheme.js"></script>


</head>

<body class="backgroundpicture darkMode">

    <header>
        <a href="/WebTech/index.php">
            <img src="/WebTech/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/WebTech/assets/images/icons/darkmode-btn.png" onclick="toggleTheme()">
    </header>

   
<!--Ab hier PHP noch unfertig !!!! -->
    <?php
    $kontakte = [];

    if(isset($_POST[]))

?>



    <!-- Ab Hier Login Funktionen -->
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
      <script src="/WebTech/assets/javascript/Validierung_Login.js"></script>
      <script src="/WebTech/assets/javascript/uhrzeit.js"></script>

</body>

</html>