<!-- RINOR STUBLLA -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR - <?php echo htmlspecialchars($product['name']) ?></title>

    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/productSite.css">
    <link rel="stylesheet" href="/assets/css/warenkorbSide.css">
    <script src="/assets/javascript/base.js"></script>
    <script src="/assets/javascript/toggleTheme.js"></script>

    <script>
        window.USER_ID = <?= isset($_SESSION['user']['user_id']) ? (int) $_SESSION['user']['user_id'] : 'null'; ?>;
        console.log("USER_ID:", window.USER_ID);
    </script>

</head>

<body>

    <?php include 'components/header.php'; ?>
    <?php // Hier wird der Breadcrumb für ein produkt erstellt (lade Hauptkategorie name und untrekategorie name und als letztes produktname) ?>
    <div class="breadcrumb">
        <a href="/index.php">MLR</a>
        <a
            href="/index.php?page=category&category=<?php echo htmlspecialchars($product['category_name']) ?>"><?php echo htmlspecialchars(getCategoryDisplayName($product['category_name'])); ?></a>

        <a
            href="/index.php?page=category&category=<?php echo htmlspecialchars(str_replace('-', '', $product['subcategory_name'])); ?>"><?php echo htmlspecialchars(getCategoryDisplayName($product['subcategory_name'])); ?></a>

        <span><?php echo htmlspecialchars($product['name']) ?></span>
    </div>


    <!-- Meldungsblock für Feedback -->
    <div id="meldung-block"></div>

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
            <button class="zurKasseButton">Zum Warenkorb</button>
        </div>
    </div>

    <div class="warenkorbOverlay"></div>

    <!-- Meldungsblock für JS -->
    <div id="meldung-block"></div>


    <div class="product-container">

        <div class="product-gallery">
            <div class="main-image">

                <img src="<?php echo $firstImage ?>" alt="<?php echo htmlspecialchars($product['name']) ?>">
            </div>
            <div class="thumbnail-row">
                <!-- Thumbnails für die Bilder des Produkts -->
                <?php
                $counter = 0;
                foreach ($product['images'] as $image) {
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


        <div class="product-details">
            <?php
            //Sollte sale sein, dann zeige SALE Badge, sonst TOP Badge
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
            <!-- Rabatt Preis anzeigen, wenn Sale gesetzt ist, es einen alten Preis gibt und dieser höher ist als der aktuelle Preis -->
                <?php if (!empty($product['old_price']) && $product['old_price'] > $product['price'] && $product['sale'] == 1): ?>
                    <div class="old-price">
                        <span class="price-prefix">€</span>
                        <span class="price-strike"><?php echo formatPrice($product['old_price']); ?></span>
                    </div>

                    <div class="price onsale">
                        <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                    </div>
                <?php else: ?>
                    <!-- Wenn kein Sale, dann zeige nur den aktuellen Preis an -->
                    <div class="price">
                        <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                    </div>

                <?php endif; ?>
                <div class="tax-info">inkl. 19% MwSt., zzgl. Versandkosten</div>
            </div>
            <!-- Lagerstatus je nachdem was gesetz ist in Datenbank, der text dafür wurde im Controller konfiguriert ($statusText)-->
            <div class="stock-status <?php echo $product['stock_status']; ?>">
                <div class="stock-dot <?php echo $product['stock_status']; ?>"></div>
                <div class="stock-text <?php echo $product['stock_status']; ?>">
                    <?php echo $statusText[$product['stock_status']] ?? ''; ?>
                </div>
            </div>


            <div class="qty-selector">
                <label>Menge:</label>
                <div class="qty-input">
                    <button class="qty-btn" onclick="updateQtyValue('decrease');">-</button>
                    <input class="qty-value" id="mengenValue" type="text" value="1" readonly>
                    <button class="qty-btn" onclick="updateQtyValue('increase');">+</button>
                </div>
            </div>





            <div class="action-buttons">
                <!-- Wenn der Lagerstatus rot ist, dann Button deaktivieren und Text anzeigen -->
                <?php if ($product['stock_status'] === 'red'): ?>
                    <button class="buy-btn disabled" disabled>Derzeit nicht verfügbar</button>
                <?php else: ?>
                    <!-- Wenn der Lagerstatus nicht rot ist, dann Button aktivieren -->
                     <!-- Button zum Warenkorb hinzufügen, der name, ID und, erstes Bild des Produktes als dataset speichert -->
                    <button class="buy-btn" data-id="<?= $product['product_id'] ?>"
                        data-name="<?= htmlspecialchars($product['name']) ?>" data-price="<?= $product['price'] ?>"
                        data-image="<?= htmlspecialchars($firstImage) ?>">IN DEN WARENKORB</button>
                <?php endif; ?>
                <button class="wishlist-btn" data-id="<?= $product['product_id'] ?>">ZUR WUNSCHLISTE HINZUFÜGEN</button>

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
            </div>

            <div class="tab-content">

                <h3>Produktbeschreibung </h3>
                <p><?php echo htmlspecialchars($product['description']); ?></p>



                <h3>Leistungsmerkmale:</h3>
                <ul class="specs-list">
                    <li>30 Tage Rückgaberecht</li>
                    <li>Mindestens 2 Jahre Herstellergarantie</li>
                    <li>Kostenfreier Standardversand</li>
                    <li>Sichere und einfache Zahlungsabwicklung</li>
                    <li>24/7 Kundenservice</li>
                    <li>Umweltfreundliche Verpackung</li>
                </ul>


                <h3>Was ist im Lieferumfang enthalten?</h3>
                <ul class="specs-list">
                    <?php echo getLieferumfang($product); ?>
                </ul>

                <?php if ($product['category_name'] === 'PC'): ?>
                    <h3>Montage und Qualitätssicherung</h3>

                    <p>Alle Komponenten wurden sorgfältig ausgewählt und perfekt aufeinander abgestimmt. Nach der Montage
                        wird jedes System einem ausführlichen Belastungstest unterzogen, um eine einwandfreie Funktion zu
                        gewährleisten.</p>
                    <p>Alle unsere PCs und Laptops werden in Deutschland von erfahrenen Technikern montiert. Wir
                        verwenden ausschließlich hochwertige Komponenten von renommierten Herstellern.</p>

                <?php endif; ?>
            </div>
        </div>

        <div class="specs-table-container">
            <h3>Technische Spezifikationen</h3>

            <table class="specs-table">
                <?php
                foreach ($product['specs'] as $key => $values) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($key) . '</td>';
                    echo '<td>' . htmlspecialchars(implode(', ', $values)) . '</td>';
                    echo '</tr>';
                }
                ?>

            </table>
        </div>

        <div class="related-products">
            <h3 class="section-title">Das könnte Sie auch interessieren</h3>
            <div class="products-row">
                <?php
                foreach ($related as $relatedProduct) {
                    $relatedFirstImage = $relatedProduct['images'][0]['file_path'];

                    echo '<div class="related-product">';
                    echo '<div class="related-product-image">';
                    echo '<img src="' . htmlspecialchars($relatedFirstImage) . '" alt="' . htmlspecialchars($relatedProduct['name']) . '">';
                    echo '</div>';
                    echo '<h4 class="related-product-title">' . htmlspecialchars($relatedProduct['name']) . '</h4>';
                    echo '<h5 class="related-product-subtitle">' . htmlspecialchars($relatedProduct['short_description']) . '</h5>';
                    echo '<div class="related-product-price">€' . formatPrice($relatedProduct['price']) . '</div>';
                    echo '<a href="/index.php?page=product&id=' . htmlspecialchars($relatedProduct['product_id']) . '" class="related-product-btn">Mehr Details</a>';
                    echo '</div>';

                }
                ?>

            </div>
        </div>
    </div>



    <?php include 'components/footer.php'; ?>



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

    <script src="/assets/javascript/Warenkorb.js" defer></script>




    <script src="/assets/javascript/addToWishlist.js"></script>

</body>

</html>