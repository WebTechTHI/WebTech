<?php
$uploadOrdnerPfad = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
$uploadUrl = '/uploads/';

// Löschen, wenn Formular abgeschickt
$nachricht = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image'])) {
    $bild = basename($_POST['delete_image']); // nur Dateiname, keine Pfade
    $pfad = $uploadOrdnerPfad . $bild;

    if (file_exists($pfad)) {
        if (unlink($pfad)) {
            $nachricht = "Bild '$bild' wurde gelöscht.";
        } else {
            $nachricht = "Fehler beim Löschen von '$bild'.";
        }
    } else {
        $nachricht = "Bild '$bild' nicht gefunden.";
    }
}
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>MLR | Admin - Bilder</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="/assets/css/mystyle.css" />
    <link rel="stylesheet" href="/assets/css/admin/upload.css" />
    <script src="/assets/javascript/toggleTheme.js"></script>

    <style>
        .galerie-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-bottom: 100px;
        }

        .galerie-item {
            position: relative;
            max-width: 200px;
            text-align: center;
        }

        .galerie-item img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .galerie-item img:hover {
            transform: scale(1.05);
        }

        .delete-form {
            margin-top: 5px;
        }

        .delete-button {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 24px;
            color: #c00;
            transition: color 0.3s ease;
        }

        .delete-button:hover {
            color: #900;
        }
    </style>
</head>

<body>
    <header style="display: flex; justify-content: space-between;">
        <a href="../index.html">
            <img src="/assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader" style="width: 100px;" />
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png" onclick="toggleTheme()" />
    </header>

    <h1>Hochgeladene Bilder</h1>

    <?php if ($nachricht): ?>
        <p style="color: green; font-weight: bold; text-align: center;"><?php echo htmlspecialchars($nachricht); ?></p>
    <?php endif; ?>

    <div class="galerie-container">
        <?php
        if (is_dir($uploadOrdnerPfad)) {
            $bilder = array_diff(scandir($uploadOrdnerPfad), ['.', '..']);
            $erlaubt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

            foreach ($bilder as $bild) {
                $endung = strtolower(pathinfo($bild, PATHINFO_EXTENSION));
                if (in_array($endung, $erlaubt)) {
                    echo '<div class="galerie-item">';
                    echo '<img src="' . htmlspecialchars($uploadUrl . $bild) . '" alt="Bild">';
                    echo '<form class="delete-form" method="post" onsubmit="return confirm(\'Bild wirklich löschen?\');">';
                    echo '<input type="hidden" name="delete_image" value="' . htmlspecialchars($bild) . '">';
                    echo '<button type="submit" class="delete-button" title="Bild löschen">🗑️</button>';
                    echo '</form>';
                    echo '</div>';
                }
            }
        } else {
            echo '<p>Fehler: Upload-Verzeichnis nicht gefunden.</p>';
        }
        ?>
    </div>

    <footer style="margin-top: auto;">
        <nav>
            <p>© 2025 MLR | <a href="about.html"><i>Impressum</i></a></p>
        </nav>
    </footer>
</body>

</html>
