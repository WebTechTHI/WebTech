<?php
require_once 'db_verbindung.php';
require_once 'productFunctions.php';

// Kategorie aus URL-Parameter ermitteln
$productId = $_GET['id'] ?? '1'; // Standardwert 1, falls kein Parameter gesetzt ist

$product = getProductById($conn, $productId);
if (!$product) {
    echo "<h1>Produkt nicht gefunden</h1>";
    exit;
}

$images = getProductImages($conn, $productId);
$firstImage = $images[0]['file_path'];


$specs = buildSpecifications($product);
?>


<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR - <?php echo htmlspecialchars($product['name']) ?></title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <link rel="stylesheet" href="/assets/css/mystyle.css">


    <link rel="stylesheet" href="/assets/css/product.css">
    <link rel="stylesheet" href="/assets/css/warenkorbSide.css">

    <script src="/assets/javascript/base.js"></script>
    <script src="/assets/javascript/toggleTheme.js"></script>


</head>

<body>

    <?php include 'components/header.html'; ?>

    <div class="breadcrumb">
        <a href="/index.php">MLR</a> ›
        <a
            href="/category.php?category=<?php echo htmlspecialchars($product['category_name']) ?>"><?php echo htmlspecialchars(getCategoryDisplayName($product['category_name'])); ?></a>
        ›
        <a
            href="/category.php?category=<?php echo htmlspecialchars(str_replace('-', '', $product['subcategory_name'])); ?>"><?php echo htmlspecialchars(getCategoryDisplayName($product['subcategory_name'])); ?></a>
        ›
        <span><?php echo htmlspecialchars($product['name']) ?></span>
    </div>

    <!-- Warenkorb Button und Overlay -->
    <button class="warenkorbToggle">
        <img src="/assets/images/icons/shopping-cart.svg" style="width: 25px;">
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

                <img src="<?php echo $firstImage ?>" alt="<?php echo htmlspecialchars($product['name']) ?>">
            </div>
            <div class="thumbnail-row">

                <?php
                $counter = 0;
                foreach ($images as $image) {
                    if ($counter === 0) {
                        echo '<div class="thumbnail active">
                            <img src="' . htmlspecialchars($image['file_path']) . '" alt=' . htmlspecialchars($product['name']) . '">
                        </div>';
                        $counter++;
                    } else {
                        echo '<div class="thumbnail">
                            <img src="' . htmlspecialchars($image['file_path']) . '" alt=' . htmlspecialchars($product['name']) . '>
                        </div>';
                    }

                }
                ?>

            </div>
        </div>


        <?php

        $formattedId = sprintf('%04d', $productId);

        ?>

        <div class="product-details">
            <?php
            if ($product['sale']) {
                echo '<span class="product-badge sale">SALE</span>';
            } else {
                echo '<span class="product-badge">TOP</span>';
            }
            ?>

            <h1 class="product-title"><?php echo htmlspecialchars($product['name']) ?></h1>
            <h2 class="product-short-description"><?php echo htmlspecialchars($product['short_description']) ?></h2>
            <div class="product-id">Artikelnummer: MLR-<?= $formattedId ?></div>

            <div class="product-rating">
                <div class="stars">★★★★★</div>
                <div class="reviews-count">(27 Bewertungen)</div>
            </div>

            <div class="price-container">
                <div class="price"><span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                </div>

                <div class="tax-info">inkl. 19% MwSt., zzgl. Versandkosten</div>
            </div>

            <div class="stock-status">
                <div class="stock-dot green"></div>
                <div class="stock-text">Auf Lager, Lieferzeit 1-3 Werktage</div>
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
                <button class="wishlist-btn">ZUR WUNSCHLISTE HINZUFÜGEN</button>
            </div>

            <div class="additional-info">
                <div class="info-badge">
                    <span>Kostenloser Versand</span>
                </div>
                <div class="info-badge">
                    <span>2 Jahre Garantie</span>
                </div>
                <div class="info-badge">
                    <span>30 Tage Rückgaberecht</span>
                </div>
            </div>
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
                <h3><?php echo htmlspecialchars($product['name'])?></h3>
                <p><?php echo htmlspecialchars($product['description'])?></p>

               

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

                <p>Alle Komponenten wurden sorgfältig ausgewählt und perfekt aufeinander abgestimmt. Nach der Montage
                    wird jedes System einem ausführlichen Belastungstest unterzogen, um eine einwandfreie Funktion zu
                    gewährleisten.</p>
            </div>
        </div>

        <div class="specs-table-container"
            style="width: 100%; margin-top: 30px; padding: 20px; background-color: white; border: 1px solid #ddd;">
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



    <?php include 'components/footer.html'; ?>



    <script>
        // Warte, bis der DOM ready ist
        document.addEventListener('DOMContentLoaded', () => {
            const mainImg = document.querySelector('.main-image img');
            const thumbnails = document.querySelectorAll('.thumbnail');

            thumbnails.forEach(thumb => {
                thumb.addEventListener('click', () => {
                    // 1. Hol dir das neue src aus dem img-Tag im Thumbnail
                    const newSrc = thumb.querySelector('img').getAttribute('src');

                    // 2. Setz es ins große Bild
                    mainImg.setAttribute('src', newSrc);

                    // 3. Update active-Klasse
                    document.querySelector('.thumbnail.active')
                        .classList.remove('active');
                    thumb.classList.add('active');
                });
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabItems = document.querySelectorAll('.tab-item');
            const tabContent = document.querySelector('.tab-content');

            tabItems.forEach((tab, index) => {
                tab.addEventListener('click', function () {
                    // Alle Tabs deaktivieren
                    tabItems.forEach(t => t.classList.remove('active'));
                    // Aktuellen Tab aktivieren
                    this.classList.add('active');

                    // Tab-Content entsprechend ändern (vereinfacht)
                    if (index === 1) { // Technische Daten Tab
                        const specsTable = document.querySelector('.specs-table-container');
                        if (specsTable) {
                            specsTable.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                });
            });
        });

    </script>


</body>

</html>