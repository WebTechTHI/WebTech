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


    <!--JavaScript hier noch einfügen-->
    <script src="/assets/javascript/toggleTheme.js"></script>

    <script src="/assets/javascript/Validierung_User.js"></script>
    <script src="/assets/javascript/uhrzeit.js"></script>


</head>

<body class="backgroundpicture darkMode">

    <header>
        <a href="/index.php">
            <img src="/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader">
        </a>


        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png" alt="DM"
            onclick="toggleTheme()">

    </header>

    <?php


    // ====== Erfolgsmeldung anzeigen  =============
    if (isset($_SESSION['erfolgsmeldung'])) {
        echo "<div class='meldung-container meldung-erfolg'>" . $_SESSION['erfolgsmeldung'] . "</div>";
        unset($_SESSION['erfolgsmeldung']); //1 mal anzeigen nur 
    }


    // ===== Fehlermeldung anzeigen =============== wenn session gesetzt
    if (isset($_SESSION['fehlermeldung'])) {
        echo "<div class='meldung-container meldung-fehler'>" . $_SESSION['fehlermeldung'] . "</div>";
        unset($_SESSION['fehlermeldung']);
    }

    ?>

    <!-- Ab Hier User Funktionen -->
    <form class="userInfoFormuser" action="?page=user" method="POST">
        <h1 class="formTitle">Benutzerkonto</h1>

        <div id="liveClock" class="Clock"></div>

        <h2 class="formSubtitle headlinecolor">Willkommen, <?= htmlspecialchars($defaultData['username']) ?>!</h2>

        <div class="infoBlockWide">
            <h2>🧾 Persönliche Informationen</h2>

            <div>
                <label for="username">Benutzername</label>
                <div>

                    <input class="userInformationInput" type="text" id="username" name="username"
                        value="<?= htmlspecialchars($defaultData['username']) ?>">
                </div>
            </div>

            <div>
                <label for="username2">Richtiger Name</label>
                <div>

                    <input class="userInformationInput" type="text" id="richtiger_name" name="richtiger_name"
                        value="<?= htmlspecialchars($defaultData['richtiger_name']) ?>">
                </div>
            </div>

            <div>
                <label for="userpassword">Passwort</label>
                <div>

                    <input class="userInformationInput" type="password" id="userpassword" name="password" value=""
                        minlength="3">
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
                            $länder = [
                                "Deutschland",
                                "Österreich",
                                "Schweiz",
                                "Frankreich",
                                "Italien",
                                "Spanien",
                                "Polen",
                                "Türkei",
                                "Niederlande",
                                "Belgien",
                                "Luxemburg",
                                "Dänemark",
                                "Norwegen",
                                "Schweden",
                                "Finnland",
                                "Island",
                                "Portugal",
                                "Griechenland",
                                "Irland",
                                "Tschechien",
                                "Slowakei",
                                "Ungarn",
                                "Rumänien",
                                "Bulgarien",
                                "Kroatien",
                                "Serbien",
                                "Bosnien",
                                "Slowenien",
                                "Albanien",
                                "Montenegro",
                                "Nordmazedonien",
                                "USA",
                                "Kanada",
                                "Mexiko",
                                "Brasilien",
                                "Argentinien",
                                "Chile",
                                "Kolumbien",
                                "China",
                                "Japan",
                                "Südkorea",
                                "Indien",
                                "Thailand",
                                "Vietnam",
                                "Indonesien",
                                "Philippinen",
                                "Südafrika",
                                "Ägypten",
                                "Marokko",
                                "Tunesien",
                                "Australien",
                                "Neuseeland"
                            ];
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

                        <input class="userInformationInput" type="text" id="stadt" name="stadt"
                            value="<?= htmlspecialchars($defaultData['stadt']) ?>">
                    </div>
                </div>

                <div>
                    <label for="email">E-Mail Adresse</label>
                    <div>

                        <input class="userInformationInput" type="text" id="email" name="email"
                            value="<?= htmlspecialchars($defaultData['email']) ?>">
                    </div>
                </div>
            </div>

            <div class="infoBlockSmall">
                <h2>⚙️ Verschiedenes</h2>

                <div>
                    <label for="Zeitzone">Zeitzone</label>
                    <div>

                        <input class="userInformationInput" type="text" id="Zeitzone" name="Zeitzone"
                            value="Europe/Berlin" disabled>
                    </div>
                </div>

                <div>
                    <label for="Datenschutz">Datenschutz</label>
                    <div>

                        <input class="userInformationInput" type="text" id="Datenschutz" name="Datenschutz"
                            value="Datenschutz-Grundverordnung DSGVO" disabled>
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

</body>

</html>