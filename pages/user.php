<!DOCTYPE html>
<html lang="de">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Benutzerkonto</title>
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

       
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png" alt="DM" onclick="toggleTheme()">
        
    </header>


<!--Ab hier PHP -->






 <!-- =======  ==========-->
<?php 

  session_start();
  include '../db_verbindung.php';


  //===========Standart daten wenn man auch nicht eingeloggt ist !!!!=============
    $defaultData = [
    "username" => "MaxMustermann",
    "richtiger_name" => "Max",
    "password" => "123456789",
    "land" => "Deutschland",
    "stadt" => "Ingolstadt",
    "email" => "MaxMustermann@email.de"
  ];




  // ======= DAten speichern wenn formualr abgeschickt wurde ===================
  if( $_SERVER['REQUEST_METHOD'] === 'POST' && isset ($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    //Formulareingaben holen aus formulaar und bereinigen also whitespaces entferenn mit trim vorne und hinten 
    $username = trim($_POST['username']);
    $richtiger_name = trim($_POST['richtiger_name']);
    $land = trim($_POST['land']);
    $stadt = trim($_POST['stadt']);
    $email =trim($_POST['email']);
    $password_input = trim($_POST['password']);


    //Passwort hashen oder behalten also altes aus default data
    if (!empty($password_input)) {      //The default algorithm to use for hashing if no algorithm is provided.
                                        //This may change in newer PHP releases when newer, stronger hashing algorithms are supported.
      $hashedPassword = password_hash($password_input, PASSWORD_DEFAULT);
    } else {
      $stmt_pw = $conn->prepare("SELECT password FROM user WHERE user_id = ?");
      $stmt_pw->bind_param("i", $user_id);
      $stmt_pw->execute();
      $stmt_pw->bind_result($hashedPassword);
      $stmt_pw->fetch();
      $stmt_pw->close();

    }


    //JEtzt noch in SQL updaten der daten falls neue eingabe
    $update_sql ="UPDATE user SET username = ?, password = ?, richtiger_name = ?, land = ?, stadt = ?, email = ? WHERE user_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssssi", $username, $hashedPassword, $richtiger_name, $land, $stadt, $email, $user_id);

    if($update_stmt->execute()) {
      $_SESSION['erfolgsmeldung'] = "Daten wurden erfolgsreicht aktualisiert in der Datenbank!";

      //Jetzt diese neuen daten aktualiseren für anzeige
    $defaultData = [
      "username" => $username,
      "richtiger_name" => $richtiger_name,
      "password" => $hashedPassword,
      "land" => $land,
      "stadt" => $stadt,
      "email" => $email
    ];
      
    } else {
      echo "<div class='meldung-container meldung-fehler'> Fehler beim Speichern: " . $conn->error . "</div>";
    }

    $update_stmt->close();

  }



  // ====== Erfolgsmeldung anzeigen  =============
  if (isset($_SESSION['erfolgsmeldung'])) {
    echo "<div class='meldung-container meldung-erfolg'>" . $_SESSION['erfolgsmeldung'] . "</div>";
    unset($_SESSION['erfolgsmeldung']); //1 mal anzeigen nur 
  }



  //====== Daten aus DB ladn für korrekte Anzeige ==============
  if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT username, password, richtiger_name, land, stadt, email FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($username, $password, $richtiger_name, $land, $stadt, $email);
    if ($stmt->fetch()) {
      $defaultData = [
        "username" => $username,
        "richtiger_name" => $richtiger_name,
        "password" => $password,
        "land" => $land,
        "stadt" => $stadt,
        "email" => $email
      ];
    }

    $stmt->close();
  }



?>

      


        <!-- Ab Hier User Funktionen -->
   <form class="userInfoFormuser" action="user.php" method="POST">
  <h1 class="formTitle">Benutzerkonto</h1>

      <div id="liveClock" class="Clock"></div>

  <h2 class="formSubtitle headlinecolor" >Willkommen, <?= htmlspecialchars($defaultData['username']) ?>!</h2>

  <div class="infoBlockWide">
    <h2>🧾 Persönliche Informationen</h2>

    <div>
      <label for="username">Benutzername</label>
      <div>
        
        <input class="userInformationInput" type="text" id="username" name="username" value="<?= htmlspecialchars($defaultData['username']) ?>">
      </div>
    </div>

    <div>
      <label for="username2">Richtiger Name</label>
      <div>
       
        <input class="userInformationInput" type="text" id="richtiger_name" name="richtiger_name" value="<?= htmlspecialchars($defaultData['richtiger_name']) ?>">
      </div>
    </div>

    <div>
      <label for="userpassword">Passwort</label>
      <div>
        
        <input class="userInformationInput" type="password" id="userpassword" name="password" value="" minlength="3">
      </div>
    </div>

    <input class="submitButton" type="submit" value="Sichern">
  </div>

  <div class="infoRow">
    <div class="infoBlockSmall">
      <h2>🌍 Weitere Informationen</h2>

      <div>
        <label for="Land">Land</label>
        <div>
          
          <select class="userInformationInput" id="land" name="land">
<!-- Hier jetzt drop down liste auswählen mit land ist besser so eig übersichtlicher-->
            <?php
              $länder = ["Deutschland", "Österreich", "Schweiz", "Frankreich", "Italien", "Spanien", "Polen", "Türkei",
                         "Niederlande", "Belgien", "Luxemburg", "Dänemark", "Norwegen", "Schweden", "Finnland", "Island",
                         "Portugal", "Griechenland", "Irland", "Tschechien", "Slowakei", "Ungarn", "Rumänien", "Bulgarien",
                         "Kroatien", "Serbien", "Bosnien", "Slowenien", "Albanien", "Montenegro", "Nordmazedonien",
                         "USA", "Kanada", "Mexiko", "Brasilien", "Argentinien", "Chile", "Kolumbien",
                         "China", "Japan", "Südkorea", "Indien", "Thailand", "Vietnam", "Indonesien", "Philippinen",
                         "Südafrika", "Ägypten", "Marokko", "Tunesien", "Australien", "Neuseeland"];
              foreach ($länder as $einLand) {
                $selected = ($defaultData['land'] === $einLand) ? 'selected' : '';
                echo "<option value='$einLand' $selected>$einLand</option>";
              }
              ?>
              </select>
        </div>
      </div>

      <div>
        <label for="Stadt">Stadt</label>
        <div>
          
          <input class="userInformationInput" type="text" id="stadt" name="stadt" value="<?= htmlspecialchars($defaultData['stadt']) ?>">
        </div>
      </div>

      <div>
        <label for="email">E-Mail Adresse</label>
        <div>
         
          <input class="userInformationInput" type="text" id="email" name="email" value="<?= htmlspecialchars($defaultData['email']) ?>">
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
    <script src="/assets/javascript/Validierung_User.js"></script>
    <script src="/assets/javascript/uhrzeit.js"></script>


</body>

</html>