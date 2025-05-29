<?php
require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// Kategorie aus URL-Parameter ermitteln
$category = $_GET['category'] ?? 'alle';
$products = getProductsByCategory($conn, $category);
$categoryInfo = getCategoryInfo($category);

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - <?php echo htmlspecialchars($categoryInfo['sidebarTitel']); ?></title>
    <link rel="stylesheet" href="/assets/css/categoryTest.css">
    <link rel="stylesheet" href="/assets/css/shopHeader.css">
    <link rel="stylesheet" href="/assets/css/mystyle.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
    <script src="/assets/javascript/base.js"></script>
    <script src="/assets/javascript/toggleTheme.js"></script>
</head>

<body>


    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="#">MLR</a> › <span><?php echo htmlspecialchars($categoryInfo['breadcrumb']); ?></span>
    </div>

    <div class="main-content">


        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title"><?php echo strtoupper($categoryInfo['sidebarTitel']); ?></div>
            <ul class="sidebar-menu">
                <?php if (!empty($categoryInfo['unterkategorien'])): ?>
                    <?php foreach ($categoryInfo['unterkategorien'] as $uk): ?>
                        <li><a
                                href="<?php echo htmlspecialchars($uk['link']); ?>"><?php echo htmlspecialchars($uk['name']); ?></a>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>

            <div class="filter-section">
                <div class="filter-title">Auswahl filtern</div>
                <a href="#" class="reset-btn">ZURÜCKSETZEN <span class="reset-icon">✕</span></a>

                <?php
                // Filter dynamisch generieren basierend auf verfügbaren Produkten
                $filters = [];

                foreach ($products as $product) {
                    // Prozessor Filter
                    if (!empty($product['processor_brand'])) {
                        $filters['Prozessor'][] = $product['processor_brand'];
                    }

                    // RAM Filter
                    if (!empty($product['ram_capacity'])) {
                        $filters['RAM'][] = $product['ram_capacity'] . ' GB';
                    }

                    // GPU Filter
                    if (!empty($product['gpu_brand']) && !$product['gpu_integrated']) {
                        $filters['Grafikkarte'][] = $product['gpu_brand'];
                    }

                    // Storage Filter
                    if (!empty($product['storage_type'])) {
                        $filters['Speicher'][] = $product['storage_type'];
                    }
                }

                // Filter-HTML generieren
                foreach ($filters as $filterName => $values) {
                    $uniqueValues = array_unique($values);
                    if (count($uniqueValues) > 1) {
                        echo "<div class='filter-group'>";
                        echo "<div class='filter-header'><span>$filterName</span><span>▼</span></div>";
                        echo "<ul class='filter-options'>";

                        foreach ($uniqueValues as $value) {
                            $count = array_count_values($values)[$value];
                            $id = strtolower(str_replace(' ', '-', $filterName . '-' . $value));
                            echo "<li><input type='checkbox' id='$id'> <label for='$id'>$value ($count)</label></li>";
                        }

                        echo "</ul>";
                        echo "</div>";
                    }
                }
                ?>
            </div>
        </div>

        <!-- Main Products Area -->
        <div class="products">
            <!-- Banner -->
            <div class="banner">
                <div class="banner-content">
                    <h1><span><?php echo htmlspecialchars($categoryInfo['titel']); ?></span>
                        <?php echo htmlspecialchars($categoryInfo['untertitel']); ?></h1>
                    <p><?php echo htmlspecialchars($categoryInfo['beschreibung']); ?></p>
                </div>
            </div>

            <!-- Section Info -->
            <div class="section-info">
                <h2><?php echo htmlspecialchars($categoryInfo['infoTitel']); ?></h2>
                <p class="subtext"><?php echo htmlspecialchars($categoryInfo['infoText']); ?></p>
                <div class="read-more">
                    <a href="#">mehr lesen ▼</a>
                </div>
            </div>


          <?php if (!empty($categoryInfo['unterkategorien'])): ?>
    <div class="categories">
        <?php foreach ($categoryInfo['unterkategorien'] as $uk): ?>
            <a href="<?php echo htmlspecialchars($uk['link']); ?>">
                <div class="category">
                    <div class="category-title">
                        <?php echo strtoupper(htmlspecialchars($uk['name'])); ?>
                    </div>
                    <div class="category-image">
                        <img src="<?php echo htmlspecialchars($uk['bild']); ?>" alt="<?php echo htmlspecialchars($uk['name']); ?>">
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


    <!-- Products Grid -->
    <h3 class="section-title">UNSERE TOPSELLER</h3>

    <div class="products-grid">
        <?php if (empty($products)): ?>
            <div class="no-products" style="text-align: center; padding: 40px; color: #666;">
                <p>Derzeit sind keine Produkte in dieser Kategorie verfügbar.</p>
            </div>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php
                // Erstes Bild laden
                $images = getProductImages($conn, $product['product_id']);

                $firstImage = !empty($images) ? $images[0]['file_path'] : 'assets/images/placeholder.png';

                // Spezifikationen aufbauen
                $specs = buildSpecifications($product);
                ?>

                <div class="product">
                    <span class="product-badge"><?php echo $product['sale'] ? 'SALE' : 'TOP'; ?></span>
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($firstImage); ?>"
                            alt="<?php echo htmlspecialchars($product['alt_text'] ?? $product['name']); ?>">
                    </div>
                    <div class="product-details">
                        <h4 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <ul class="product-specs">
                            <?php foreach ($specs as $spec): ?>
                                <li><?php echo htmlspecialchars($spec); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="price">
                            <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                        </div>
                        <div class="financing">Jetzt mit 0% Finanzierung</div>
                        <a href="/productPages/product.php?id=<?php echo $product['product_id']; ?>" class="buy-btn">Jetzt
                            konfigurieren</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    </div>
    </div>

    <!-- Footer -->
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
                        <li><a href="categoryList.php?kategorie=gaming-pc">Gaming PCs</a></li>
                        <li><a href="categoryList.php?kategorie=pc">Desktop PCs</a></li>
                        <li><a href="categoryList.php?kategorie=laptop">Laptops</a></li>
                        <li><a href="categoryList.php?kategorie=zubehör">Zubehör</a></li>
                        <li><a href="categoryList.php?kategorie=monitor">Monitore</a></li>
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
                        <li><span class="footer-contact-icon">◆</span> MLR GmbH<br>Musterstraße 123<br>80335 München
                        </li>
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

        .no-products {
            grid-column: 1 / -1;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
        }

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