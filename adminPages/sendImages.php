
<!DOCTYPE html>
<html lang="de">

<link rel="stylesheet" href="/assets/css/mystyle.css">
<link rel="stylesheet" href="/assets/css/admin/upload.css">
<script src="/assets/javascript/toggleTheme.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Admin - Start</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
</head>

<body>
    <header style="display: flex; justify-content: space-between;">
        <a href="../index.html">
            <img src="../assets/images/logo/logoDarkmode.png" alt="logo.png" class="logoHeader" style="width: 100px;">
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()">
    </header>

    <h1>
        Admin Startseite
    </h1>

    <div style="margin: 100px 0; display: flex; justify-content: center">
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label for="imageUpload">Bild hochladen:</label>
            <input type="file" name="image" id="imageUpload" accept="image/*" required>
            <button type="submit">Hochladen</button>
        </form>
    </div>


    <footer style="margin-top: auto;">

        <nav>
            <p>© 2025 MLR | <a href="about.html"><i>Impressum</i></a></p>
        </nav>
    </footer>
</body>

</html>