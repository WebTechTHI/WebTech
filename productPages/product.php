<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Produkt</title>
  <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">

  <link rel="stylesheet" href="../assets/css/mystyle.css">
  <link rel="stylesheet" href="../assets/css/shopHeader.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/product.css">
  <link rel="stylesheet" href="../assets/css/warenkorbSide.css">

  <script src="../assets/javascript/base.js"></script>
  <script src="../assets/javascript/toggleTheme.js"></script>
<script src="../assets/javascript/productInfo.js"></script>
  
</head>
<body>
     <header id="header">
        <div class="topNavHeader">
            <a class="logo" href='../index.html'><img src='../assets/images/logo/logoDarkmode.png'></a>

            <nav class="userNav">
                <a href="../pages/login.html"><img class="userIconHeader" src="../assets/images/icons/login.png"
                        alt="Login"></a>
                <a href="../pages/registration.html"><img class="userIconHeader" src="../assets/images/icons/register.png"
                        alt="Registrierung"></a>
                <a href="../pages/logout.html"><img class="userIconHeader" src="../assets/images/icons/logout.png"
                        alt="Logout"></a>
                <a href="../pages/shoppingBasket.html"><img class="userIconHeader"
                        src="../assets/images/icons/shoppingcart.png" alt="Warenkorb"></a>
                <a href="../pages/user.html"><img class="userIconHeader" src="../assets/images/icons/user.png"></a>
            </nav>
        </div>

        <div class="bottomNavHeader">
            <ul>
                <li>
                    <a href="../productLists/desktopPcList.html">Desktop-PCs</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/gamingPcList.html">Gaming-PCs</a></li>
                        <li><a href="../productLists/officePcList.html">Office-PCs</a></li>
                    </ul>
                </li>

                <li>
                    <a href="../productLists/laptopList.html">Laptops</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/gamingLaptopList.html">Gaming Laptops</a></li>
                        <li><a href="../productLists/officeLaptopList.html">Office Laptops</a></li>
                    </ul>
                </li>

                <li>
                    <a href="../productLists/zubehörList.html">Zubehör</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/monitorList.html">Monitore</a></li>
                        <li><a href="../productLists/mausList.html">Mäuse</a></li>
                        <li><a href="../productLists/tastaturList.html">Tastaturen</a></li>
                    </ul>
                </li>
                <li class="headerDeals">
                    <a href="../productLists/deals.html">Angebote</a>
                </li>

                <div class="search-container">
                    <img src="../assets/images/icons/suchIcon.png" alt="Suche" id="searchIcon" class="suchIcon">
                    <input type="text" name="suchFeld" id="suchFeld" placeholder="Suchen..." onkeyup="searchFunction()">
                  </div>
            </ul>
            <img id="themeToggleBtn" class="toggleTheme" src="../assets/images/icons/darkmode-btn.png" onclick="toggleTheme()">
        </div>
    </header>
    
    <div class="breadcrumb">
        <a href="#">MLR</a> › 
        <a href="#">Desktop PCs</a> › 
        <a href="#">Gaming PCs</a> › 
        <span>Gaming PC Ryzen 7 7700 - RTX 5070</span>
    </div>

    <!-- Warenkorb Button und Overlay -->
     <button class="warenkorbToggle">
      <img src="../assets/images/icons/shoppingcart.png" style="width: 25px;">
      <span class="warenkorbAnzahl">0</span>
    </button>

    <div class="warenkorbContainer">
      <div class="warenkorbHeader">
        <h3>Warenkorb</h3>
        <button class="warenkorbSchliessen">&times;</button>
      </div>
      <div class="warenkorbInhalt">
        <div class="leerNachricht">Ihr Warenkorb ist leer.</div>
      </div>
      <div class="warenkorbFooter">
        <div class="warenkorbGesamt">
          <span>Gesamt:</span>
          <span>0,00 €</span>
        </div>
        <button class="zurKasseButton">Zur Kasse</button>
      </div>
    </div>

    <div class="warenkorbOverlay"></div>
    
    <div class="product-container">
        <div class="product-gallery">
            <div class="main-image">
                <img src="../assets/images/products/1/vorne.png" alt="Gaming PC Ryzen 7 7700">
            </div>
            <div class="thumbnail-row">
                <div class="thumbnail active">
                    <img src="../assets/images/products/1/vorne.png" alt="Ansicht 1">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Ansicht 2">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Ansicht 3">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Ansicht 4">
                </div>
            </div>
        </div>
        
        <div class="product-details">
            <span class="product-badge">TOP</span>
            <h1 class="product-title">Gaming PC Ryzen 7 7700 - RTX 5070</h1>
            <div class="product-id">Artikelnummer: MLR-RYZEN7-5070</div>
            
            <div class="product-rating">
                <div class="stars">★★★★★</div>
                <div class="reviews-count">(27 Bewertungen)</div>
            </div>
            
            <div class="price-container">
                <div class="price"><span class="price-prefix">€</span>1.749.-</div>
                <div class="price-info">Jetzt mit <span>0% Finanzierung</span> ab €48,58 im Monat</div>
                <div class="tax-info">inkl. 19% MwSt., zzgl. Versandkosten</div>
            </div>
            
            <div class="stock-status">
                <div class="stock-dot"></div>
                <div class="stock-text">Auf Lager, Lieferzeit 1-3 Werktage</div>
            </div>
            
            <div class="shipping-info">
                Bei Bestellung innerhalb der nächsten <strong>2 Stunden und 47 Minuten</strong> erfolgt der Versand heute.
            </div>
            
            <div class="qty-selector">
                <label>Menge:</label>
                <div class="qty-input">
                    <button class="qty-btn">-</button>
                    <input class="qty-value" type="text" value="1" readonly>
                    <button class="qty-btn">+</button>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="buy-btn">IN DEN WARENKORB</button>
                <a href="#" class="configure-btn">JETZT KONFIGURIEREN</a>
                <a href="#" class="wishlist-btn">ZUR WUNSCHLISTE HINZUFÜGEN</a>
            </div>
            
            <ul class="specs-list">
                <li>AMD Ryzen 7 7700, 8C/16T, 5.3 GHz Boost</li>
                <li>MSI B650 Gaming Mainboard</li>
                <li>RTX 5070 12GB, Gainward</li>
                <li>32GB DDR5-6600, Corsair RGB</li>
                <li>1TB NVMe SSD, WD_BLACK SN850X</li>
                <li>BeQuiet! 750W 80+ Gold Netzteil</li>
                <li>Windows 11 Pro vorinstalliert</li>
            </ul>
        </div>
        
        <div class="tabs">
            <div class="tab-header">
                <div class="tab-item active">Produktdetails</div>
                <div class="tab-item">Technische Daten</div>
                <div class="tab-item">Bewertungen</div>
                <div class="tab-item">Zubehör</div>
                <div class="tab-item">FAQ</div>
            </div>
            
            <div class="tab-content">
                <h3>Gaming PC Ryzen 7 7700 - RTX 5070</h3>
                <p>Dieser leistungsstarke Gaming-PC bietet alles, was Sie für ein herausragendes Spielerlebnis benötigen. Mit dem AMD Ryzen 7 7700 Prozessor und der neuesten RTX 5070 Grafikkarte sind Sie bestens für aktuelle und kommende Spieletitel gerüstet.</p>
                
                <p>Der Gaming PC ist optimal auf Performance ausgelegt und bietet dank der schnellen DDR5-6600 RAM-Module und der ultraschnellen NVMe SSD genügend Reserven auch für anspruchsvolle Anwendungen und Multitasking.</p>
                
                <h3>Leistungsmerkmale:</h3>
                <ul class="specs-list">
                    <li>Herausragende Gaming-Performance in 4K und VR</li>
                    <li>RGB-Beleuchtung für individuellen Style</li>
                    <li>Leises Kühlsystem für optimale Temperaturen unter Last</li>
                    <li>Hochwertiges MSI Gaming Mainboard mit vielen Anschlussmöglichkeiten</li>
                    <li>2 Jahre Garantie und lebenslanger Support</li>
                </ul>
                
                <h3>Was ist im Lieferumfang enthalten?</h3>
                <ul class="specs-list">
                    <li>Gaming PC Ryzen 7 7700 - RTX 5070</li>
                    <li>Netzkabel</li>
                    <li>Handbuch und Treiber-CD</li>
                    <li>Kostenloses Spiele-Bundle (limitierte Aktion)</li>
                </ul>
                
                <p>Alle Komponenten wurden sorgfältig ausgewählt und perfekt aufeinander abgestimmt. Nach der Montage wird jedes System einem ausführlichen Belastungstest unterzogen, um eine einwandfreie Funktion zu gewährleisten.</p>
            </div>
        </div>
        
        <div class="specs-table-container" style="width: 100%; margin-top: 30px; padding: 20px; background-color: white; border: 1px solid #ddd;">
            <h3 style="margin-bottom: 20px;">Technische Spezifikationen</h3>
            
            <table class="specs-table">
                <tr>
                    <td>Prozessor</td>
                    <td>AMD Ryzen 7 7700, 8 Kerne, 16 Threads, 5.3 GHz Max Boost</td>
                </tr>
                <tr>
                    <td>Mainboard</td>
                    <td>MSI B650 Gaming Mainboard, AM5 Sockel</td>
                </tr>
                <tr>
                    <td>Arbeitsspeicher</td>
                    <td>32GB (2x16GB) Corsair Vengeance RGB DDR5-6600 MHz</td>
                </tr>
                <tr>
                    <td>Grafikkarte</td>
                    <td>NVIDIA GeForce RTX 5070, 12GB GDDR6X, Gainward</td>
                </tr>
                <tr>
                    <td>Festplatte / SSD</td>
                    <td>1TB WD_BLACK SN850X NVMe SSD (Lesen: bis zu 7.300 MB/s)</td>
                </tr>
                <tr>
                    <td>Netzteil</td>
                    <td>BeQuiet! Straight Power 11 750W, 80+ Gold</td>
                </tr>
                <tr>
                    <td>Gehäuse</td>
                    <td>MLR Gaming Gehäuse mit Tempered Glass Seitenteil, 4x RGB Lüfter</td>
                </tr>
                <tr>
                    <td>Kühlung</td>
                    <td>BeQuiet! Dark Rock Pro 4 CPU-Kühler</td>
                </tr>
                <tr>
                    <td>Betriebssystem</td>
                    <td>Windows 11 Pro 64-bit</td>
                </tr>
                <tr>
                    <td>WLAN</td>
                    <td>Wi-Fi 6E (802.11ax) + Bluetooth 5.3</td>
                </tr>
                <tr>
                    <td>Anschlüsse vorne</td>
                    <td>2x USB 3.2 Gen1 Typ-A, 1x USB 3.2 Gen2 Typ-C, Audio In/Out</td>
                </tr>
                <tr>
                    <td>Anschlüsse hinten</td>
                    <td>4x USB 3.2 Gen1, 2x USB 3.2 Gen2, 1x USB 3.2 Gen2x2 Typ-C, 1x LAN (2.5 GBit), 5x Audio</td>
                </tr>
                <tr>
                    <td>Grafikanschlüsse</td>
                    <td>3x DisplayPort 1.4a, 1x HDMI 2.1</td>
                </tr>
                <tr>
                    <td>Abmessungen</td>
                    <td>45 cm x 21 cm x 45 cm (H x B x T)</td>
                </tr>
                <tr>
                    <td>Gewicht</td>
                    <td>ca. 12 kg</td>
                </tr>
                <tr>
                    <td>Garantie</td>
                    <td>2 Jahre Garantie, lebenslanger Support</td>
                </tr>
            </table>
        </div>
        
        <div class="related-products">
            <h3 class="section-title">Das könnte Sie auch interessieren</h3>
            
            <div class="products-row">
                <div class="related-product">
                    <div class="related-product-image">
                        <img src="/api/placeholder/200/180" alt="Gaming PC Ryzen 5">
                    </div>
                    <h4 class="related-product-title">Gaming PC Ryzen 5 7600X - RTX 4070</h4>
                    <div class="related-product-price">€1.299.-</div>
                    <a href="#" class="related-product-btn">Details</a>
                </div>
                
                <div class="related-product">
                    <div class="related-product-image">
                        <img src="/api/placeholder/200/180" alt="Gaming PC Ryzen 9">
                    </div>
                    <h4 class="related-product-title">Gaming PC Ryzen 9 7900X - RTX 5080 Ti</h4>
                    <div class="related-product-price">€2.499.-</div>
                    <a href="#" class="related-product-btn">Details</a>
                </div>
                
                <div class="related-product">
                    <div class="related-product-image">
                        <img src="/api/placeholder/200/180" alt="27 Zoll Gaming Monitor">
                    </div>
                    <h4 class="related-product-title">27 Zoll Gaming Monitor, 240Hz, 1ms, QHD</h4>
                    <div class="related-product-price">€399.-</div>
                    <a href="#" class="related-product-btn">Details</a>
                </div>
                
                <div class="related-product">
                    <div class="related-product-image">
                        <img src="/api/placeholder/200/180" alt="Gaming Headset">
                    </div>
                    <h4 class="related-product-title">MLR Pro Gaming Headset mit 7.1 Surround Sound</h4>
                    <div class="related-product-price">€99.-</div>
                    <a href="#" class="related-product-btn">Details</a>
                </div>
            </div>
        </div>
    </div>
    

    
    <div class="footer">
        <div class="footer-content">
            <div class="footer-column">
                <h4 class="footer-title">Kundenservice</h4>
                <ul class="footer-links">
                    <li><a href="#">Kontakt</a></li>
                    <li><a href="#">FAQ / Hilfe</a></li>
                    <li><a href="#">Rückgabe & Garantie</a></li>
                    <li><a href="#">Bestellstatus</a></li>
                    <li><a href="#">Reparaturservice</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4 class="footer-title">Information</h4>
                <ul class="footer-links">
                    <li><a href="#">AGB</a></li>
                    <li><a href="#">Datenschutz</a></li>
                    <li><a href="#">Widerrufsrecht</a></li>
                    <li><a href="#">Impressum</a></li>
                    <li><a href="#">Cookie-Einstellungen</a></li>
                </ul>
            </div>
            
            <div class="footer-column">
                <h4 class="footer-title">Service & Vorteile</h4>
                <ul class="footer-links">
                    <li><a href="#">Finanzierung</a></li>
                    <li><a href="#">Lieferung & Versand</a></li>
                    <li><a href="#">Geschenkgutscheine</a></li>
                    <li><a href="#">Newsletter anmelden</a></li>
                    <li><a href="#">Studentenrabatt</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="bottom-footer">
        <div class="bottom-footer-content">
            <div>© 2025 MLR Computer GmbH. Alle Rechte vorbehalten.</div>
            <div class="payment-methods">
                <span>Zahlungsarten:</span>
                <img src="/api/placeholder/40/20" alt="Visa">
                <img src="/api/placeholder/40/20" alt="Mastercard">
                <img src="/api/placeholder/40/20" alt="PayPal">
                <img src="/api/placeholder/40/20" alt="Klarna">
                <img src="/api/placeholder/40/20" alt="Sofortüberweisung">
            </div>
        </div>
    </div>
</body>
</html>