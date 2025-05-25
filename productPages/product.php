<?php
    $productId = isset($_GET['id']) ? $_GET['id'] : null;
    $product = null;
    $productName = "Produkt nicht gefunden";
    $formattedPrice = "N/A";
    $productImages = [];
    $productSpecs = [];
    $productDescription = "<p>Das angeforderte Produkt konnte nicht gefunden werden.</p>";
    $productKurzbeschreibung = "";
    $productRating = 0;
    $productSale = false;
    $productOriginalPrice = null;
    $product_category_for_design = 'pc'; // Default category for design

    if ($productId) {
        $json_data_products_single = file_get_contents('../assets/json/productList.json');
        $products_list_single = json_decode($json_data_products_single, true);

        foreach ($products_list_single as $p_single) {
            if ($p_single['id'] == $productId) {
                $product = $p_single;
                break;
            }
        }

        if ($product) {
            $productName = htmlspecialchars($product['name']);
            
            $priceToFormat = (isset($product['sale']) && $product['sale'] && isset($product['preis_reduziert'])) ? $product['preis_reduziert'] : $product['preis'];
            if (floor($priceToFormat) == $priceToFormat) {
                $formattedPrice = number_format($priceToFormat, 0, ',', '.') . ",-";
            } else {
                $formattedPrice = number_format($priceToFormat, 2, ',', '.');
            }

            if (isset($product['sale']) && $product['sale'] && isset($product['preis'])) {
                $productSale = true;
                if (floor($product['preis']) == $product['preis']) {
                    $productOriginalPrice = number_format($product['preis'], 0, ',', '.') . ",-";
                } else {
                    $productOriginalPrice = number_format($product['preis'], 2, ',', '.');
                }
            }

            if (isset($product['bild']) && is_array($product['bild'])) {
                foreach ($product['bild'] as $imgPath) {
                    $productImages[] = htmlspecialchars($imgPath);
                }
            }


            $productKurzbeschreibungArray = isset($product['highlights']) ? $product['highlights'] : [];
            $productKurzbeschreibung = "";
            if (!empty($productKurzbeschreibungArray)) {
                foreach($productKurzbeschreibungArray as $highlight) {
                    $productKurzbeschreibung .= "<li>" . htmlspecialchars($highlight) . "</li>";
                }
            }

            if (isset($product['beschreibung'])) {
                if (is_array($product['beschreibung'])) {
                    $productDescription = "";
                    foreach($product['beschreibung'] as $paragraph) {
                        $productDescription .= "<p>" . htmlspecialchars($paragraph) . "</p>";
                    }
                } else {
                    $productDescription = "<p>" . htmlspecialchars($product['beschreibung']) . "</p>";
                }
            } else {
                $productDescription = "<p>Keine Beschreibung verfügbar.</p>";
            }
            
            $productSpecs = isset($product['technische_details']) ? $product['technische_details'] : [];
            $productRating = isset($product['bewertung']) ? intval($product['bewertung']) : 0;

            // Determine category for design elements
            if (!empty($product['unterkategorie'])) {
                $product_category_for_design = $product['unterkategorie'];
            } elseif (!empty($product['kategorie'])) {
                $product_category_for_design = $product['kategorie'];
            }
        }
    }

    // Design/Content loading logic from produktBeschreibung.json
    $json_data_desc_product_page = file_get_contents('../assets/json/produktBeschreibung.json');
    $descriptions_product_page = json_decode($json_data_desc_product_page, true);
    
    // Use the determined category for fetching design, fallback to a default if specific one not found
    $page_content_product_page = $descriptions_product_page[$product_category_for_design] ?? ($descriptions_product_page['pc'] ?? null);

    $breadcrumb_text_product_page = $page_content_product_page['breadcrumb'] ?? 'Produkte';
    $sidebarTitel_text_product_page = $page_content_product_page['sidebarTitel'] ?? 'Kategorien';
    // Unterkategorien for sidebar should come from the *parent* category of the product if it's a subcategory.
    // For example, if product is 'gamingLaptop', sidebar should show 'Gaming Laptops' and 'Office Laptops' from 'laptop' category.
    $parent_category_key = '';
    if(isset($product['kategorie'])) { // Determine parent category for sidebar items
        $parent_category_key = $product['kategorie'];
    } else if ($product_category_for_design === 'gamingPC' || $product_category_for_design === 'officePC'){
        $parent_category_key = 'pc';
    } else if ($product_category_for_design === 'gamingLaptop' || $product_category_for_design === 'officeLaptop'){
        $parent_category_key = 'laptop';
    } else if ($product_category_for_design === 'monitor' || $product_category_for_design === 'maus' || $product_category_for_design === 'tastatur'){
        $parent_category_key = 'zubehör';
    }

    $sidebar_unterkategorien_list = [];
    if (!empty($parent_category_key) && isset($descriptions_product_page[$parent_category_key]['unterkategorien'])) {
        $sidebar_unterkategorien_list = $descriptions_product_page[$parent_category_key]['unterkategorien'];
    } elseif (isset($page_content_product_page['unterkategorien'])) { // Fallback to current category's subcategories if parent logic fails
         $sidebar_unterkategorien_list = $page_content_product_page['unterkategorien'];
    }

?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MLR | <?php echo $productName; ?></title>
  <link rel="icon" href="../assets/images/logo/favicon.png" type="image/x-icon">

  <link rel="stylesheet" href="../assets/css/mystyle.css">
  <link rel="stylesheet" href="../assets/css/shopHeader.css">
  <link rel="stylesheet" href="../assets/css/footer.css">
  <link rel="stylesheet" href="../assets/css/product.css">
  <link rel="stylesheet" href="../assets/css/warenkorbSide.css">

  <script src="../assets/javascript/base.js"></script>
  <script src="../assets/javascript/toggleTheme.js"></script>
  <script src="../assets/javascript/Warenkorb.js"></script> <!-- Assuming Warenkorb.js contains addToCart and handles cart UI -->
  
</head>
<body>
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
        <a href="../index.html">MLR</a> › 
        <a href="../productLists/<?php 
            // Attempt to link to a relevant category page
            if (!empty($parent_category_key)) {
                // Construct filename, e.g. laptopList.php from 'laptop'
                $parent_list_page = strtolower($parent_category_key) . 'List.php';
                if ($parent_category_key === 'zubehör') $parent_list_page = 'zubehoerList.php'; // Handle 'ö'
                echo htmlspecialchars($parent_list_page);
            } elseif (!empty($product_category_for_design) && $product_category_for_design !== 'pc') { // fallback to product's own category if no parent
                 $cat_list_page = strtolower($product_category_for_design) . 'List.php';
                 if ($product_category_for_design === 'zubehör') $cat_list_page = 'zubehoerList.php';
                 echo htmlspecialchars($cat_list_page);
            } else {
                echo 'desktopPcList.php'; // Default fallback
            }
        ?>"><?php echo htmlspecialchars($breadcrumb_text_product_page); ?></a> ›
        <span><?php echo $productName; ?></span>
    </div>

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
     <div class="main-content-product-page"> <!-- New wrapper for product page specific layout -->
        <div class="sidebar-product-page">
            <div class="sidebar-title"><?php echo htmlspecialchars($sidebarTitel_text_product_page); ?></div>
            <ul class="sidebar-menu">
                <?php if (!empty($sidebar_unterkategorien_list)): ?>
                    <?php foreach ($sidebar_unterkategorien_list as $uk): ?>
                        <?php
                           // Ensure the link path is relative to the current file (productPages/product.php)
                           // The $uk['link'] is already like "productLists/gamingPcList.html"
                           // So, just replacing .html with .php and prepending ../ is correct.
                           $linkPath = "../" . htmlspecialchars(str_replace('.html', '.php', $uk['link']));
                        ?>
                        <li><a href="<?php echo $linkPath; ?>"><?php echo htmlspecialchars($uk['name']); ?></a></li>
                    <?php endforeach; ?>
                <?php else: ?>
                     <li>Keine verwandten Kategorien</li>
                <?php endif; ?>
            </ul>
        </div>

        <div class="product-container">
            <?php if ($product): ?>
            <div class="product-gallery">
                <div class="main-image">
                    <img src="../assets/<?php echo !empty($productImages) ? $productImages[0] : 'images/placeholder.png'; ?>" alt="<?php echo $productName; ?>">
                </div>
                <div class="thumbnail-row">
                    <?php 
                        $maxThumbnails = 4; // Or however many you want to display
                        $currentThumbnails = 0;
                        if(!empty($productImages)) {
                            foreach ($productImages as $index => $img) {
                                if ($currentThumbnails >= $maxThumbnails) break;
                                ?>
                                <div class="thumbnail <?php echo $index === 0 ? 'active' : ''; ?>">
                                    <img src="../assets/<?php echo $img; ?>" alt="<?php echo $productName . ' Ansicht ' . ($index + 1); ?>">
                                </div>
                                <?php
                                $currentThumbnails++;
                            }
                        } else { ?>
                            <div class="thumbnail active"><img src="../assets/images/placeholder.png" alt="Placeholder"></div>
                        <?php } ?>
                </div>
            </div>
            
            <div class="product-details">
                <?php if (isset($product['badge']) && !empty($product['badge'])): ?>
                    <span class="product-badge"><?php echo htmlspecialchars($product['badge']); ?></span>
                <?php endif; ?>
                <h1 class="product-title"><?php echo $productName; ?></h1>
                <div class="product-id">Artikelnummer: <?php echo htmlspecialchars($product['id_display'] ?? $product['id']); ?></div>
                
                <div class="product-rating">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php echo ($i <= $productRating) ? '★' : '☆'; ?>
                        <?php endfor; ?>
                    </div>
                    <!-- Reviews count can be added if available in JSON -->
                </div>
                
                <div class="price-container">
                    <?php if ($productSale && $productOriginalPrice): ?>
                        <div class="price original-price-crossed">€ <?php echo $productOriginalPrice; ?></div>
                        <div class="price sale-price">€ <?php echo $formattedPrice; ?></div>
                    <?php else: ?>
                        <div class="price">€ <?php echo $formattedPrice; ?></div>
                    <?php endif; ?>
                    <!-- Price info like financing can be added if available -->
                    <div class="tax-info">inkl. 19% MwSt., zzgl. Versandkosten</div>
                </div>
                
                <div class="stock-status">
                    <div class="stock-dot <?php echo (isset($product['lagerstatus']) && strtolower($product['lagerstatus']) === 'auf lager') ? 'available' : 'unavailable'; ?>"></div>
                    <div class="stock-text"><?php echo htmlspecialchars($product['lagerstatus'] ?? 'Nicht verfügbar'); ?><?php if(isset($product['lieferzeit'])) { echo ", Lieferzeit " . htmlspecialchars($product['lieferzeit']); } ?></div>
                </div>
                
                <!-- Shipping info can be dynamic if needed -->
                
                <div class="qty-selector">
                    <label>Menge:</label>
                    <div class="qty-input">
                        <button class="qty-btn" onclick="changeQuantity(-1)">-</button>
                        <input class="qty-value" type="text" value="1" readonly id="qty-value">
                        <button class="qty-btn" onclick="changeQuantity(1)">+</button>
                    </div>
                </div>
                
                <div class="action-buttons">
                     <button class="buy-btn" onclick="addToCart(<?php echo htmlspecialchars($product['id']); ?>, '<?php echo htmlspecialchars(addslashes($product['name'])); ?>', <?php echo (isset($product['sale']) && $product['sale'] && isset($product['preis_reduziert'])) ? htmlspecialchars($product['preis_reduziert']) : htmlspecialchars($product['preis']); ?>, '../assets/<?php echo !empty($productImages) ? $productImages[0] : 'images/placeholder.png'; ?>', document.getElementById('qty-value').value)">IN DEN WARENKORB</button>
                    <!-- Configure and wishlist buttons can be linked if functionality exists -->
                </div>
                
                <ul class="specs-list">
                    <?php echo $productKurzbeschreibung; ?>
                </ul>
            </div>
            
            <div class="tabs">
                <div class="tab-header">
                    <div class="tab-item active">Produktdetails</div>
                    <div class="tab-item">Technische Daten</div>
                    <div class="tab-item">Bewertungen</div>
                    <!-- Zubehör and FAQ tabs can be added if content is available -->
                </div>
                
                <div class="tab-content">
                    <h3><?php echo $productName; ?></h3>
                    <?php echo $productDescription; ?>
                    <!-- Additional sections like Leistungsmerkmale, Lieferumfang can be populated if data exists -->
                </div>
            </div>
            
            <div class="specs-table-container" style="width: 100%; margin-top: 30px; padding: 20px; background-color: white; border: 1px solid #ddd;">
                <h3 style="margin-bottom: 20px;">Technische Spezifikationen</h3>
                <?php if (!empty($productSpecs)): ?>
                <table class="specs-table">
                    <?php foreach ($productSpecs as $specName => $specValue): ?>
                    <tr>
                        <td><?php echo htmlspecialchars(str_replace('_', ' ', ucfirst($specName))); ?></td>
                        <td><?php echo htmlspecialchars(is_array($specValue) ? implode(', ', $specValue) : $specValue); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
                <?php else: ?>
                <p>Keine technischen Daten verfügbar.</p>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div style="text-align: center; padding: 50px;">
                <h1>Produkt nicht gefunden</h1>
                <p>Das von Ihnen gesuchte Produkt ist leider nicht verfügbar.</p>
                <a href="../index.html">Zurück zur Startseite</a>
            </div>
            <?php else: ?>
            <div style="text-align: center; padding: 50px; grid-column: 1 / -1;"> <!-- Ensure it spans full width if sidebar is present -->
                <h1>Produkt nicht gefunden</h1>
                <p>Das von Ihnen gesuchte Produkt ist leider nicht verfügbar.</p>
                <a href="../index.html">Zurück zur Startseite</a>
            </div>
            <?php endif; ?>
        
            <div class="related-products" <?php if (!$product) echo 'style="display:none;"'; ?>>
                <h3 class="section-title">Das könnte Sie auch interessieren</h3>
                
                <!-- Related products can be dynamically generated if logic exists -->
            </div>
        </div> <!-- End of product-container -->
    </div> <!-- End of main-content-product-page -->
    
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
                <img src="../assets/images/icons/paymentMethods/visa-light.svg" alt="Visa">
                <img src="../assets/images/icons/paymentMethods/mastercard-light.svg" alt="Mastercard">
                <img src="../assets/images/icons/paymentMethods/paypal-light.svg" alt="PayPal">
                <img src="../assets/images/icons/paymentMethods/klarna.svg" alt="Klarna">
                <img src="../assets/images/icons/paymentMethods/bitcoin-light.svg" alt="Bitcoin">
            </div>
        </div>
    </div>
    <script>
        // JavaScript for image gallery
        const mainImage = document.querySelector('.main-image img');
        const thumbnails = document.querySelectorAll('.thumbnail img');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                mainImage.src = this.src;
                document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
                this.parentElement.classList.add('active');
            });
        });

        // JavaScript for tabs
        const tabItems = document.querySelectorAll('.tab-item');
        const tabContents = document.querySelectorAll('.tab-content'); // Assuming multiple tab content sections, though example showed one

        tabItems.forEach(tab => {
            tab.addEventListener('click', function() {
                // Remove active class from all tabs and hide all content
                tabItems.forEach(item => item.classList.remove('active'));
                // If there were multiple tab-content divs, they would be handled here.
                // For now, this assumes one .tab-content that might be internally changed or always shows 'Produktdetails'
                
                // Add active class to clicked tab
                this.classList.add('active');
                // Logic to show corresponding tab content would go here
                // e.g. display a specific section within the .tab-content or switch .tab-content divs.
                // The current HTML has only one .tab-content div, so this is more for structure.
            });
        });

        // JavaScript for quantity selector
        function changeQuantity(amount) {
            const qtyValueInput = document.getElementById('qty-value');
            let currentValue = parseInt(qtyValueInput.value);
            currentValue += amount;
            if (currentValue < 1) {
                currentValue = 1;
            }
            qtyValueInput.value = currentValue;
        }
    </script>
</body>
</html>