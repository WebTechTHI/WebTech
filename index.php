<!DOCTYPE html>
<html lang="de">
<link rel="stylesheet" href="/assets/css/mystyle.css">
<link rel="stylesheet" href="/assets/css/shopHeader.css">
<link rel="stylesheet" href="/assets/css/footer.css">
<link rel="stylesheet" href="/assets/css/productList.css">
<script src="/assets/javascript/base.js"></script>
<script src="/assets/javascript/loadProducts_index.js"></script>
<script src="/assets/javascript/toggleTheme.js"></script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR | Startseite</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
</head>
<style>
    a {
        color: blue;
        text-decoration: none;
    }
</style>

<body>
    <header id="header">
        <div id="topHeader" class="topNavHeader">
            <a class="logo" href='index.php'><img src='/assets/images/logo/logoDarkmode.png'></a>
            <nav class="userNav">

                <a href="/pages/login.php"><img class="userIconHeader" src="/assets/images/icons/login.png"
                        alt="Login"></a>

                <a href="/pages/registration.php"><img class="userIconHeader" src="/assets/images/icons/register.png"
                        alt="Registrierung"></a>

                <a href="/pages/logout.php"><img class="userIconHeader" src="/assets/images/icons/logout.png"
                        alt="Logout"></a>

                <a href="/pages/shoppingBasket.php"><img class="userIconHeader"
                        src="/assets/images/icons/shoppingcart.png" alt="Warenkorb"></a>

                <a href="/pages/user.php"><img class="userIconHeader" 
                        src="/assets/images/icons/user.png"></a>
            </nav>
        </div>

        <div class="bottomNavHeader">
            <ul>
                <li>
                    <a href="productLists/desktopPcList.html">Desktop-PCs</a>
                    <ul class="subMenu">
                        <li><a href="productLists/gamingPcList.html">Gaming-PCs</a></li>
                        <li><a href="productLists/officePcList.html">Office-PCs</a></li>
                    </ul>
                </li>

                <li>
                    <a href="productLists/laptopList.html">Laptops</a>
                    <ul class="subMenu">
                        <li><a href="productLists/gamingLaptopList.html">Gaming Laptops</a></li>
                        <li><a href="productLists/officeLaptopList.html">Office Laptops</a></li>
                    </ul>
                </li>

                <li>
                    <a href="productLists/zubehörList.html">Zubehör</a>
                    <ul class="subMenu">
                        <li><a href="productLists/monitorList.html">Monitore</a></li>
                        <li><a href="productLists/mausList.html">Mäuse</a></li>
                        <li><a href="productLists/tastaturList.html">Tastaturen</a></li>
                    </ul>
                </li>
                <li class="headerDeals">
                    <a href="productLists/deals.html">Angebote</a>
                </li>

                <div class="search-container">
                    <img src="/assets/images/icons/suchIcon.png" alt="Suche" id="searchIcon" class="suchIcon">
                    <input type="text" name="suchFeld" id="suchFeld" placeholder="Suchen..." onkeyup="searchFunction()">
                  </div>
            </ul>
            <img id="themeToggleBtn" class="toggleTheme" src="/assets/images/icons/darkmode-btn.png"
                onclick="toggleTheme()">
        </div>
    </header>
    <a href="testIndex.html">test Startseite</a>
    <a href="testProduct.html">test Produktseite</a>
    <a href="categoryNeu.html">test Produktliste</a>
    <a href="testEndprodukt.html">test testEndprodukte</a>

    <div class="categoryNameContainer">
        <h1 class="categoryName">nicht pullen bra</h1>
    </div>

    <div class="productMainContainer"></div>

    <footer>
        <p>© 2025 MLR | <a href="/pages/about.html"><i>Impressum</i></a></p>

    </footer>
</body>


</html>