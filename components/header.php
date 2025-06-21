<?php
$cartCount = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    // Variante A: Zähle einfach die Items im Array
    $cartCount = count($_SESSION['cart']);

    // Variante B: Falls du pro Eintrag eine Menge speicherst, summiere die Quantitäten
    // $cartCount = array_sum(array_column($_SESSION['cart'], 'quantity'));
}
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/assets/css/components/header.css">
    <script src="/assets/javascript/toggleTheme.js"></script> <!-- statt Link zu script geänder -->

</head>

<body>
    <div id="header">
        <!-- top header -->
        <div class="top-bar">
            <img class="header-icon" id="themeToggleBtn" alt="toggle-theme-btn" onclick="toggleTheme()">
            <div class="login-user-container">

                <!-- Falls ein Admin angemeldet ist, kann dieser auf das Admin-panel zugreifen. -->
                <?php if (isset($_SESSION['user']) && $_SESSION['user']['role_id'] === 1) { ?>
                    <!-- Nur diese Zeile ist neu eingefügt von Laurin -->
                    <a href="/index.php?page=admin">
                        <img class="header-icon" src="/assets/images/icons/admin.svg" alt="Admin"
                            title="Adminbereich">
                    </a>

                <?php } ?>

                <!-- Der Login-button wird nur angezeigt, falls der Benutzer abgemeldet ist. -->
                <?php if (!isset($_SESSION['user'])) { ?>


                    <a href="/index.php?page=login">
                        <img class="header-icon" src="/assets/images/icons/login.svg" alt="Login/Registrierung"
                            title="Login/Registrierung">
                    </a>

                <?php } ?>

                <?php if (isset($_SESSION['user'])) { ?>
                    <a href="/index.php?page=user">
                        <img class="header-icon" src="/assets/images/icons/account.svg" alt="Account" title="Account">
                    </a>

                    <a href="/index.php?page=logout">
                        <img class="header-icon" src="/assets/images/icons/logout.svg" alt="Logout" title="Logout">
                    </a>

                <?php } ?>
            </div>
        </div>

        <!-- nav header -->
        <nav class="main-nav">
            <div class="main-menu">
                <div class="logo">
                    <a href="/index.php"><img src="/assets/images/logo/logoDarkmode.png" alt="MLR Logo"
                            style="height:60px;"></a>
                </div>
                <ul>
                    <li>
                        <a href="/index.php?page=category&category=alle">Alle Produkte</a>
                    </li>
                    <li>
                        <a href="/index.php?page=category&category=pc">Desktop PCs</a>
                        <ul class="submenu">
                            <li><a href="/index.php?page=category&category=gamingpc">gaming PCs</a></li>
                            <li><a href="/index.php?page=category&category=officepc">office PCs</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/index.php?page=category&category=laptop">Laptops</a>
                        <ul class="submenu">
                            <li><a href="/index.php?page=category&category=gaminglaptop">gaming Laptops</a></li>
                            <li><a href="/index.php?page=category&category=officelaptop">office Laptops</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/index.php?page=category&category=zubehör">Monitore & Zubehör</a>
                        <ul class="submenu">
                            <li><a href="/index.php?page=category&category=monitor">Monitore</a></li>
                            <li><a href="/index.php?page=category&category=tastatur">Tastaturen</a></li>
                            <li><a href="/index.php?page=category&category=maus">Mäuse</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="/configurator.php">Konfigurator</a>
                    </li>
                    <li>
                        <a href="/index.php?page=category&category=angebote">Sale & Aktionen <span
                                class="red-badge">5</span></a>
                    </li>
                </ul>
            </div>

            <!-- Warenkorb und Wunschliste-->
            <div class="wishlist-cart-container">
                <!-- Suchfeld NEU NUR ZUM TESTEN-->
                <!-- ============================================-->
                <div class="search-container">
                    <img class="header-icon" id="search-icon" src="/assets/images/icons/search.svg" alt="Suche"
                        title="Suche" onclick="toggleSearch()">
                    <div class="search-field-container" id="search-field">
                        <input type="text" class="search-input" placeholder="Produkte suchen..." id="search-input">
                        <button class="search-button" onclick="performSearch()">Suchen</button>
                    </div>
                </div>
                <!-- ============================================-->
                <!-- Suchfeld NEU NUR ZUM TESTEN-->
                <a href="/index.php?page=wishlist" title="Wunschliste">
                    <img class="header-icon" id="wishlist-icon" src="/assets/images/icons/favorite-border.svg"
                        alt="wishlist">
                </a>
                <a href="/index.php?page=cart" class="cart">
                    MEIN WARENKORB
                    <span class="cart-badge"><?= htmlspecialchars($cartCount, ENT_QUOTES, 'UTF-8') ?></span>
                </a>


            </div>

        </nav>
    </div>



    <!-- Suchfeld NEU NUR ZUM TESTEN-->
    <!-- ============================================-->
    <script>
        function toggleSearch() {
            const searchField = document.getElementById('search-field');
            const searchInput = document.getElementById('search-input');

            searchField.classList.toggle('active');

            // Focus auf Input setzen wenn geöffnet
            if (searchField.classList.contains('active')) {
                setTimeout(() => searchInput.focus(), 100);
            }
        }

        function performSearch() {
            const searchTerm = document.getElementById('search-input').value;
            if (searchTerm.trim()) {
                // Hier kannst du die Suchlogik implementieren
                window.location.href = `/search.php?q=${encodeURIComponent(searchTerm)}`;
            }
        }

        // Schließe Suchfeld beim Klick außerhalb
        document.addEventListener('click', function (event) {
            const searchContainer = document.querySelector('.search-container');
            const searchField = document.getElementById('search-field');

            if (!searchContainer.contains(event.target)) {
                searchField.classList.remove('active');
            }
        });
    </script>
</body>

</html>