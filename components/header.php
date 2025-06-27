<?php
//MICHAEL PIETSCH
// Wenn wir eine Session verwenden, starten wir sie.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../model/CartModel.php';

//=========== DAMIT DER WARENKORB ZÄHLER IMMER AKTUELL IST ==============
$cartCount = 0;

if (isset($_SESSION['user']['user_id'])) {
  
    $cartModel = new CartModel();
    $dbCart = $cartModel->getCartFromDb($_SESSION['user']['user_id']);
    // Zählt die Anzahl der verschiedenen Produkte im Warenkorb
     $cartCount = count($dbCart);

} else{
    if (isset($_COOKIE['mlr_cart'])) {
    $cartData = json_decode($_COOKIE['mlr_cart'], true);
    if (is_array($cartData)) {
        // Zählt die Anzahl der verschiedenen Produkte im Warenkorb
        $cartCount = count($cartData); 

    }
}
}
//=========== DAMIT DER WARENKORB ZÄHLER IMMER AKTUELL IST ENDE ==============
?>

<?php 
//============ DER ZÄHLER FÜR PRODUKTE IM SALE ========================
 $cartModel = new CartModel();
 $saleCount = $cartModel->getSaleCount(); 
?>

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="/assets/css/components/header.css">
    <script src="/assets/javascript/toggleTheme.js"></script> <!-- statt Link zu script geänder -->

</head>

<body>
  <?php // =============== COOKIE FUNKTIONALITÄT IM HEADER EINGEBUNDEN ====================?>

  
   <div id="cookie-wrapper">
       <?php include __DIR__ . '/cookie_banner.php'; ?>
       <link rel="stylesheet" href="/assets/css/cookie-banner.css">
       <script src="/assets/javascript/cookieConsent.js" defer></script>
       <?php
       if (!isset($_COOKIE['cookie_consent'])) {
           echo '<style>#cookie-consent-banner { display: block !important; }</style>';
       }
       ?>

<?php // =============== COOKIE FUNKTIONALITÄT IM HEADER EINGEBUNDEN ENDE ====================?>




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
                    <a href="/index.php?page=orders">
                        <img class="header-icon" src="/assets/images/icons/bestellungen.svg" alt="Meine Bestellungen" title="Meine Bestellungen">
                    </a>

                    <a href="/index.php?page=user">
                        <img class="header-icon" src="/assets/images/icons/account.svg" alt="Account" title="Account">
                    </a>

                    <a href="/index.php?page=logout">
                        <img class="header-icon user" src="/assets/images/icons/logout.svg" alt="Logout" title="Logout">
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
                        <a href="index.php?page=configurator&action=configure">Konfigurator</a>
                    </li>
                    <li>
                        <a href="/index.php?page=category&category=angebote">Sale & Aktionen <span
                                class="red-badge"><?php echo $saleCount ?></span></a>
                    </li>
                </ul>
            </div>

            <!-- Warenkorb und Wunschliste-->
            <div class="wishlist-cart-container">
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



 
</body>

</html>


<?//MICHAEL PIETSCH ENDE ?>