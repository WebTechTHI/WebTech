<!DOCTYPE html>
<html lang="de">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Benutzerkonto</title>
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

       
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/WebTech/assets/images/icons/darkmode-btn.png" alt="DM" onclick="toggleTheme()">
        
    </header>


<!--Ab hier PHP -->


        <!-- Ab Hier User Funktionen -->
   <form class="userInfoFormuser" action="user.php" method="POST">
  <h1 class="formTitle">👤 Benutzerkonto</h1>

      <div id="liveClock" class="Clock"></div>

  <h2 class="formSubtitle">Willkommen, Max Mustermann!</h2>

  <div class="infoBlockWide">
    <h2>🧾 Persönliche Informationen</h2>

    <div>
      <label for="username">Benutzername</label>
      <div>
        
        <input class="userInformationInput" type="text" id="username" name="username" value="MaxMustermann" maxlength="25">
      </div>
    </div>

    <div>
      <label for="username2">Richtiger Name</label>
      <div>
       
        <input class="userInformationInput" type="text" id="username2" name="username2" value="Max" maxlength="25">
      </div>
    </div>

    <div>
      <label for="userpassword">Passwort</label>
      <div>
        
        <input class="userInformationInput" type="password" id="userpassword" name="password" value="123456789" minlength="3" maxlength="25">
      </div>
    </div>

    <input class="submitButton" type="submit" value="💾 Sichern">
  </div>

  <div class="infoRow">
    <div class="infoBlockSmall">
      <h2>🌍 Weitere Informationen</h2>

      <div>
        <label for="Land">Land</label>
        <div>
          
          <input class="userInformationInput" type="text" id="Land" name="Land" value="Deutschland">
        </div>
      </div>

      <div>
        <label for="Stadt">Stadt</label>
        <div>
          
          <input class="userInformationInput" type="text" id="Stadt" name="Stadt" value="Ingolstadt">
        </div>
      </div>

      <div>
        <label for="email">E-Mail Adresse</label>
        <div>
         
          <input class="userInformationInput" type="text" id="email" name="email" value="MaxMustermann@email.de">
        </div>
      </div>
    </div>

    <div class="infoBlockSmall">
      <h2>⚙️ Verschiedenes</h2>

      <div>
        <label for="Zeitzone">Zeitzone</label>
        <div>
         
          <input class="userInformationInput" type="text" id="Zeitzone" name="Zeitzone" value="Europe/Berlin" disabled>
        </div>
      </div>

      <div>
        <label for="Datenschutz">Datenschutz</label>
        <div>
        
          <input class="userInformationInput" type="text" id="Datenschutz" name="Datenschutz" value="Datenschutz-Grundverordnung DSGVO" disabled>
        </div>
      </div>
    </div>
  </div>
</form>




    <footer>
        <nav>
            <p>© 2025 MLR | <a href="about.php">Impressum</a></p>
        </nav>
    </footer>


    <!--JavaScript hier noch einfügen-->
    <script src="/WebTech/assets/javascript/Validierung_User.js"></script>
    <script src="/WebTech/assets/javascript/uhrzeit.js"></script>


</body>

</html>