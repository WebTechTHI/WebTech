<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - Produktkategorien</title>
    <link rel="stylesheet" href="../assets/css/categoryTest.css">
    <link rel="stylesheet" href="../assets/css/shopHeader.css">
    <link rel="stylesheet" href="../assets/css/mystyle.css">
    <link rel="stylesheet" href="../assets/css/footer.css">
    <script src="../assets/javascript/base.js"></script>
    <script src="../assets/javascript/toggleTheme.js"></script>
</head>

<body>
    <?php
        // Product loading logic
        $category = 'monitor'; 
        $json_data_products = file_get_contents('../assets/json/productList.json');
        $products_all = json_decode($json_data_products, true);
        $filtered_products = [];
        if ($category === 'angebote') {
            foreach ($products_all as $product_item) {
                if (isset($product_item['sale']) && $product_item['sale'] === true) {
                    $filtered_products[] = $product_item;
                }
            }
        } else {
            foreach ($products_all as $product_item) {
                if ((isset($product_item['kategorie']) && $product_item['kategorie'] === $category) ||
                    (isset($product_item['unterkategorie']) && $product_item['unterkategorie'] === $category)) {
                    $filtered_products[] = $product_item;
                }
            }
        }

        // Design/Content loading logic from produktBeschreibung.json
        $json_data_desc = file_get_contents('../assets/json/produktBeschreibung.json');
        $descriptions = json_decode($json_data_desc, true);
        
        $page_content = isset($descriptions[$category]) ? $descriptions[$category] : null;

        $breadcrumb_text = $page_content['breadcrumb'] ?? 'Produkte';
        $sidebarTitel_text = $page_content['sidebarTitel'] ?? ucfirst($category);
        $unterkategorien_list = $page_content['unterkategorien'] ?? [];
        
        $bannerTitel_text = $page_content['titel'] ?? '';
        $bannerUntertitel_text = $page_content['untertitel'] ?? '';
        $bannerBeschreibung_text = $page_content['beschreibung'] ?? '';
        
        $infoTitel_text = $page_content['infoTitel'] ?? '';
        $infoText_text = $page_content['infoText'] ?? '';
    ?>
    <header id="header">
        <div class="topNavHeader">
            <a class="logo" href='../index.html'><img src='../assets/images/logo/logoDarkmode.png'></a>

            <nav class="userNav">
                <a href="../pages/login.php"><img class="userIconHeader" src="../assets/images/icons/login.png"
                        alt="Login"></a>
                <a href="../pages/registration.php"><img class="userIconHeader" src="../assets/images/icons/register.png"
                        alt="Registrierung"></a>
                <a href="../pages/logout.php"><img class="userIconHeader" src="../assets/images/icons/logout.png"
                        alt="Logout"></a>
                <a href="../pages/shoppingBasket.php"><img class="userIconHeader"
                        src="../assets/images/icons/shoppingcart.png" alt="Warenkorb"></a>
                <a href="../pages/user.php"><img class="userIconHeader" src="../assets/images/icons/user.png"></a>
            </nav>
        </div>

        <div class="bottomNavHeader">
            <ul>
                <li>
                    <a href="../productLists/desktopPcList.php">Desktop-PCs</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/gamingPcList.php">Gaming-PCs</a></li>
                        <li><a href="../productLists/officePcList.php">Office-PCs</a></li>
                    </ul>
                </li>

                <li>
                    <a href="../productLists/laptopList.php">Laptops</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/gamingLaptopList.php">Gaming Laptops</a></li>
                        <li><a href="../productLists/officeLaptopList.php">Office Laptops</a></li>
                    </ul>
                </li>

                <li>
                    <a href="../productLists/zubehoerList.php">Zubehör</a>
                    <ul class="subMenu">
                        <li><a href="../productLists/monitorList.php">Monitore</a></li>
                        <li><a href="../productLists/mausList.php">Mäuse</a></li>
                        <li><a href="../productLists/tastaturList.php">Tastaturen</a></li>
                    </ul>
                </li>
                <li class="headerDeals">
                    <a href="../productLists/deals.php">Angebote</a>
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
        <a href="../index.html">MLR</a> › <span><?php echo htmlspecialchars($breadcrumb_text); ?></span>
    </div>

    <div class="main-content">
        
        <div class="sidebar">
            <div class="sidebar-title"><?php echo htmlspecialchars($sidebarTitel_text); ?></div>
            <ul class="sidebar-menu">
                <?php if (!empty($unterkategorien_list)): ?>
                    <?php foreach ($unterkategorien_list as $uk): ?>
                        <li><a href="../<?php echo htmlspecialchars(str_replace('.html', '.php', $uk['link'])); ?>"><?php echo htmlspecialchars($uk['name']); ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                     <li><a href="#">Keine Unterkategorien</a></li>
                <?php endif; ?>
            </ul>

            <div class="filter-section">
                <div class="filter-title">Auswahl filtern</div>
                <a href="#" class="reset-btn">ZURÜCKSETZEN <span class="reset-icon">✕</span></a>
                
            </div>
        </div>

        
        <div class="products">
            
            <div class="banner">
                <div class="banner-content">
                    <h1><span><?php echo htmlspecialchars($bannerTitel_text); ?></span> <?php echo htmlspecialchars($bannerUntertitel_text); ?></h1>
                    <p><?php echo htmlspecialchars($bannerBeschreibung_text); ?></p>
                </div>
            </div>

            
            <div class="section-info">
                <h2><?php echo htmlspecialchars($infoTitel_text); ?></h2>
                <p class="subtext"><?php echo htmlspecialchars($infoText_text); ?></p>
                <div class="read-more">
                    <a href="#">mehr lesen ▼</a>
                </div>
            </div>

            <?php if (!empty($unterkategorien_list)): ?>
            <h3 class="section-title">UNTERKATEGORIE WÄHLEN:</h3>
            <div class="categories">
                <?php foreach ($unterkategorien_list as $uk): ?>
                    <a href="../<?php echo htmlspecialchars(str_replace('.html', '.php', $uk['link'])); ?>" class="category-item">
                        <img src="../<?php echo htmlspecialchars($uk['bild']); ?>" alt="<?php echo htmlspecialchars($uk['name']); ?>">
                        <span><?php echo htmlspecialchars($uk['name']); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <h3 class="section-title" style="display: none;">UNTERKATEGORIE WÄHLEN:</h3>
            <div class="categories" style="display: none;">
            </div>
            <?php endif; ?>
            
            <h3 class="section-title">UNSERE TOPSELLER</h3>

            
            <div class="products-grid">
                <?php if (empty($filtered_products)): ?>
                    <div class="loading-placeholder" style="text-align: center; padding: 40px; color: #666;">
                        <p>Keine Produkte in dieser Kategorie gefunden.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($filtered_products as $product): ?>
                        <div class="product-card" data-product-id="<?php echo htmlspecialchars($product['id']); ?>">
                            <a href="../product.html?id=<?php echo htmlspecialchars($product['id']); ?>">
                                <img src="../assets/<?php echo htmlspecialchars($product['bild'][0]); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
                            </a>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description"><?php echo htmlspecialchars(implode(', ', $product['highlights'])); ?></p>
                                <div class="product-price">
                                    <?php if (isset($product['sale']) && $product['sale'] && isset($product['preis_reduziert'])): ?>
                                        <span class="original-price">€<?php echo htmlspecialchars(number_format($product['preis'], 2, ',', '.')); ?></span>
                                        <span class="discounted-price">€<?php echo htmlspecialchars(number_format($product['preis_reduziert'], 2, ',', '.')); ?></span>
                                    <?php else: ?>
                                        <span>€<?php echo htmlspecialchars(number_format($product['preis'], 2, ',', '.')); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-rating">
                                    <?php 
                                        $rating = isset($product['bewertung']) ? $product['bewertung'] : 0;
                                        for ($i = 0; $i < 5; $i++): 
                                    ?>
                                        <span class="star"><?php echo ($i < $rating) ? '★' : '☆'; ?></span>
                                    <?php endfor; ?>
                                </div>
                            </div>
                            <button class="add-to-cart-btn" onclick="event.stopPropagation(); addToCart(<?php echo htmlspecialchars($product['id']); ?>, '<?php echo htmlspecialchars(addslashes($product['name'])); ?>', <?php echo (isset($product['sale']) && $product['sale'] && isset($product['preis_reduziert'])) ? htmlspecialchars($product['preis_reduziert']) : htmlspecialchars($product['preis']); ?>, '../assets/<?php echo htmlspecialchars($product['bild'][0]); ?>');">In den Warenkorb</button>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-logo">
                        <img src="/api/placeholder/120/30" alt="MLR Logo" style="height:30px;">
                    </div>
                    <p class="footer-about">
                        MLR ist Deutschlands führender Anbieter von hochwertigen Gaming-PCs, Laptops und individuell
                        konfigurierbaren Computer-Systemen mit über 15 Jahren Erfahrung und exzellentem Kundenservice.
                    </p>
                    <div class="footer-social">
                        <a href="#" title="Facebook">f</a>
                        <a href="#" title="Twitter">t</a>
                        <a href="#" title="Instagram">i</a>
                        <a href="#" title="YouTube">y</a>
                        <a href="#" title="Discord">d</a>
                    </div>
                </div>

                <div>
                    <h4 class="footer-title">PRODUKTE</h4>
                    <ul class="footer-links">
                        <li><a href="productLists/gamingPcList.php">Gaming PCs</a></li>
                        <li><a href="productLists/desktopPcList.php">Desktop PCs</a></li>
                        <li><a href="productLists/laptopList.php">Laptops</a></li>
                        <li><a href="../productLists/zubehoerList.php">Zubehör</a></li>
                        <li><a href="../productLists/monitorList.php">Monitore</a></li>
                        <li><a href="../productLists/gamingPcList.php">Gaming PCs</a></li>
                        <li><a href="../productLists/desktopPcList.php">Desktop PCs</a></li>
                        <li><a href="../productLists/laptopList.php">Laptops</a></li>
                        <li><a href="../productLists/zubehoerList.php">Zubehör</a></li>
                        <li><a href="../productLists/monitorList.php">Monitore</a></li>
                        <li><a href="#">PC Konfigurator</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">SERVICE</h4>
                    <ul class="footer-links">
                        <li><a href="#">Support</a></li>
                        <li><a href="#">FAQ</a></li>
                        <li><a href="#">Reparatur & Wartung</a></li>
                        <li><a href="#">Garantie</a></li>
                        <li><a href="#">Downloads</a></li>
                        <li><a href="#">Hardware-Ratgeber</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="footer-title">KONTAKT</h4>
                    <ul class="footer-contact footer-links">
                        <li><span class="footer-contact-icon">◆</span> MLR GmbH<br>Musterstraße 123<br>80335 München</li>
                        <li><span class="footer-contact-icon">☎</span> +49 89/660 77 969 0</li>
                        <li><span class="footer-contact-icon">✉</span> info@mlr-computer.de</li>
                        <li><span class="footer-contact-icon">⌚</span> Mo-Fr: 9-18 Uhr<br>Sa: 10-14 Uhr</li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <div>© 2025 MLR GmbH. Alle Rechte vorbehalten.</div>
                <div class="footer-payment">
                    <div class="payment-icon" title="PayPal"></div>
                    <div class="payment-icon" title="Visa"></div>
                    <div class="payment-icon" title="Mastercard"></div>
                    <div class="payment-icon" title="Klarna"></div>
                    <div class="payment-icon" title="Sofortüberweisung"></div>
                </div>
            </div>
        </div>
    </footer>

    <style>
        /* Footer Styles */
        .footer {
            background-color: #111;
            color: white;
            padding: 40px 0 20px;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
            max-width: 1200px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }

        .footer-logo {
            margin-bottom: 20px;
        }

        .footer-about {
            font-size: 14px;
            line-height: 1.6;
            color: #aaa;
            margin-bottom: 20px;
        }

        .footer-social {
            display: flex;
            gap: 10px;
        }

        .footer-social a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: #333;
            color: white;
            border-radius: 50%;
            text-decoration: none;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .footer-social a:hover {
            background-color: #e00;
        }

        .footer-title {
            font-size: 18px;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background-color: #e00;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            color: #aaa;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-contact li {
            display: flex;
            margin-bottom: 15px;
            color: #aaa;
            font-size: 14px;
        }

        .footer-contact-icon {
            margin-right: 10px;
            color: #e00;
            min-width: 16px;
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #333;
            font-size: 13px;
            color: #aaa;
            max-width: 1200px;
            margin: 0 auto;
            padding-left: 20px;
            padding-right: 20px;
        }

        .footer-payment {
            display: flex;
            gap: 10px;
        }

        .payment-icon {
            width: 40px;
            height: 25px;
            background-color: #fff;
            border-radius: 3px;
        }

        /* Loading Placeholder Styles */
        .loading-placeholder {
            grid-column: 1 / -1;
        }

        /* Container für Footer */
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Responsive Design für Footer */
        @media (max-width: 768px) {
            .footer-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }
            
            .footer-bottom {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }
        }
    </style>
</body>

</html>