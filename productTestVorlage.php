<?php
require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// Produkt-ID aus der URL holen und absichern
$productId = isset($_GET['id']) ? (int)$_GET['id'] : 0;



// Produktdaten abrufen
$product = getProductById($conn, $productId);
echo'Product ID: ' . $productId; // Debugging-Zeile, um die Produkt-ID zu überprüfen
echo'Product Name: ' . $product['name']; // Debugging-Zeile, um den Produktnamen zu überprüfen
$images = getProductImages($conn, $productId);
$specs = buildSpecifications($product); // Ihre bestehende Funktion

// Falls Produkt nicht existiert, Fehler anzeigen
if (!$product) {
    http_response_code(404);
    echo "<h1>404 - Produkt nicht gefunden</h1>";
    // Hier könnte eine schönere Fehlerseite eingebunden werden
    exit();
}

// Verwandte Produkte laden
$relatedProducts = getRelatedProducts($conn, $product['subcategory_name'], $productId);
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - <?php echo htmlspecialchars($product['name']); ?></title>
    <!-- Bestehende Stylesheets -->
    <link rel="stylesheet" href="/assets/css/mystyle.css">
    <link rel="stylesheet" href="/assets/css/categoryList.css">
    <!-- Neues Stylesheet für die Produktseite -->
    <style>
        /* Product Detail Page Specific Styles */
        .product-detail-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
            padding: 20px;
            background-color: white;
            margin: 20px;
            border-radius: 15px;
        }

        .product-main-view {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        /* Gallery */
        .product-gallery {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        .main-image-container {
            width: 100%;
            height: 300px;
            background-color: #f9f9f9;
            border: 1px solid #eee;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        .main-image-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .main-image-container:hover img {
            transform: scale(1.05);
        }

        .product-badge-detail {
            position: absolute;
            top: 15px;
            right: 15px;
            border-radius: 8px;
            background-color: #e00;
            color: white;
            padding: 8px 15px;
            font-size: 18px;
            font-weight: bold;
            z-index: 10;
        }

        .thumbnail-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 5px;
        }

        .thumbnail-item {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 5px;
            cursor: pointer;
            padding: 5px;
            flex-shrink: 0;
            transition: border-color 0.3s ease;
        }

        .thumbnail-item img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .thumbnail-item:hover, .thumbnail-item.active {
            border-color: #e00;
        }

        /* Product Info */
        .product-info {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .product-info h1 {
            font-size: 28px;
            margin-bottom: 10px;
            line-height: 1.2;
        }

        .product-availability {
            font-size: 14px;
            color: #008500;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .product-price-box {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .product-price-box .price {
            font-size: 36px;
            color: #e00;
        }

        .product-price-box .financing {
            margin-top: 5px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .add-to-cart-btn {
            flex-grow: 1;
            background-color: #e00;
            color: white;
            text-align: center;
            padding: 15px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }
        .add-to-cart-btn:hover { background-color: #c00; }
        
        .product-key-features {
            list-style: none;
            padding-left: 0;
        }
        .product-key-features li {
            padding: 8px 0;
            font-size: 15px;
            border-bottom: 1px solid #eee;
        }
        .product-key-features li::before {
            content: "›";
            margin-right: 10px;
            color: #e00;
            font-weight: bold;
        }

        /* Tabs Section */
        .product-details-tabs {
            margin-top: 40px;
        }
        .tab-nav {
            display: flex;
            border-bottom: 2px solid #ddd;
            gap: 5px;
        }
        .tab-btn {
            padding: 15px 25px;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 16px;
            font-weight: bold;
            color: #555;
            position: relative;
            bottom: -2px;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }
        .tab-btn.active, .tab-btn:hover {
            color: #111;
            border-bottom-color: #e00;
        }
        .tab-content {
            padding: 30px 10px;
            line-height: 1.7;
            color: #333;
        }
        .tab-pane { display: none; }
        .tab-pane.active { display: block; }

        .tech-specs-table {
            width: 100%;
            border-collapse: collapse;
        }
        .tech-specs-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .tech-specs-table tr td:first-child {
            font-weight: bold;
            width: 30%;
            background-color: #f9f9f9;
        }

        .related-products-section {
            padding: 0 20px 20px;
        }
        .related-products-section .product {
             /* Stellt sicher, dass die Produktkarten das Layout der Kategorieseite beibehalten */
            flex-direction: column;
            min-height: 320px;
            max-height: none;
        }
        .related-products-section .product-image {
            width: 100%;
            height: 150px;
            padding-right: 0;
        }

        /* Responsive Anpassungen */
        @media (min-width: 768px) {
            .product-main-view {
                flex-direction: row;
            }
            .product-gallery { flex-basis: 45%; }
            .product-info { flex-basis: 55%; }
            .main-image-container { height: 450px; }
            
            .related-products-section .products-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (min-width: 1024px) {
            .related-products-section .products-grid { grid-template-columns: repeat(4, 1fr); }
        }
    </style>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
</head>
<body>
    <?php include 'components/header.html'; ?>

    <!-- Breadcrumb -->
    <!-- Breadcrumb -->
<div class="breadcrumb">
    <a href="index.php">MLR</a> › 
    <a href="<?php echo htmlspecialchars($product['category_link']); ?>"><?php echo htmlspecialchars($product['category_name']); ?></a> › 
    <a href="<?php echo htmlspecialchars($product['subcategory_link']); ?>"><?php echo htmlspecialchars($product['subcategory_name']); ?></a> › 
    <span><?php echo htmlspecialchars($product['name']); ?></span>
</div>
    <!-- Main Product Content -->
    <div class="product-detail-container">
        <div class="product-main-view">
            <!-- Product Image Gallery -->
            <div class="product-gallery">
                <?php if ($product['sale']) { echo '<span class="product-badge-detail">SALE %</span>'; } ?>
                <div class="main-image-container">
                    <img id="mainProductImage" src="<?php echo htmlspecialchars(!empty($images) ? $images[0]['file_path'] : '/assets/images/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($product['alt_text']); ?>">
                </div>
                <div class="thumbnail-container">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="thumbnail-item <?php echo $index === 0 ? 'active' : ''; ?>" onclick="changeImage('<?php echo htmlspecialchars($image['file_path']); ?>', this)">
                            <img src="<?php echo htmlspecialchars($image['file_path']); ?>" alt="Vorschaubild <?php echo $index + 1; ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Product Information & Actions -->
            <div class="product-info">
                <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                <p class="product-availability">Sofort versandfertig, Lieferzeit 1-3 Werktage</p>
                
                <div class="product-price-box">
                    <div class="price">
                        <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                    </div>
                    <div class="financing"><span>Jetzt mit 0% Finanzierung</span></div>
                </div>

                <div class="product-actions">
                    <a href="#" class="add-to-cart-btn">In den Warenkorb</a>
                    <button class="favorite-btn">
                        <img src="/assets/images/icons/favorite-border.svg" alt="Favorit" />
                    </button>
                </div>

                <ul class="product-key-features">
                    <?php foreach ($specs as $spec): ?>
                        <li><?php echo htmlspecialchars($spec); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- TABS: Description, Specs, Reviews -->
        <div class="product-details-tabs">
            <div class="tab-nav">
                <button class="tab-btn active" data-tab="description">Beschreibung</button>
                <button class="tab-btn" data-tab="specs">Technische Daten</button>
                <button class="tab-btn" data-tab="reviews">Bewertungen</button>
            </div>
            <div class="tab-content">
                <div id="description" class="tab-pane active">
                    <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                </div>
                <div id="specs" class="tab-pane">
                    <table class="tech-specs-table">
                        <tbody>
                            <?php if($product['processor_model']): ?><tr><td>Prozessor</td><td><?php echo htmlspecialchars($product['processor_brand'] . ' ' . $product['processor_model']); ?></td></tr><?php endif; ?>
                            <?php if($product['gpu_model']): ?><tr><td>Grafikkarte</td><td><?php echo htmlspecialchars($product['gpu_brand'] . ' ' . $product['gpu_model'] . ' (' . $product['gpu_vram'] . ' GB)'); ?></td></tr><?php endif; ?>
                            <?php if($product['ram_capacity']): ?><tr><td>Arbeitsspeicher</td><td><?php echo htmlspecialchars($product['ram_capacity'] . ' GB ' . $product['ram_type']); ?></td></tr><?php endif; ?>
                            <?php if($product['storage_capacity']): ?><tr><td>Speicher</td><td><?php echo htmlspecialchars($product['storage_capacity'] . ' GB ' . $product['storage_type']); ?></td></tr><?php endif; ?>
                            <?php if($product['display_size']): ?><tr><td>Display</td><td><?php echo htmlspecialchars($product['display_size'] . '", ' . $product['resolution'] . ', ' . $product['refresh_rate_hz'] . 'Hz, ' . $product['panel_type']); ?></td></tr><?php endif; ?>
                            <?php if($product['os_name']): ?><tr><td>Betriebssystem</td><td><?php echo htmlspecialchars($product['os_name']); ?></td></tr><?php endif; ?>
                            <?php if($product['connectors_spec']): ?><tr><td>Anschlüsse</td><td><?php echo htmlspecialchars($product['connectors_spec']); ?></td></tr><?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="reviews" class="tab-pane">
                    <p>Für dieses Produkt gibt es noch keine Bewertungen.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products Section -->
    <?php if (!empty($relatedProducts)): ?>
    <div class="related-products-section">
        <h3 class="section-title">DAS KÖNNTE DIR AUCH GEFALLEN</h3>
        <div class="products-grid">
            <?php foreach ($relatedProducts as $relatedProduct): ?>
                <?php
                    $relatedImages = getProductImages($conn, $relatedProduct['product_id']);
                    $relatedFirstImage = !empty($relatedImages) ? $relatedImages[0]['file_path'] : '/assets/images/placeholder.png';
                    $relatedSpecs = buildSpecifications($relatedProduct);
                ?>
                <div class="product">
                    <div class="product-image">
                        <a class="product-image-buy" href="/product.php?id=<?php echo $relatedProduct['product_id']; ?>">
                            <img src="<?php echo htmlspecialchars($relatedFirstImage); ?>" alt="<?php echo htmlspecialchars($relatedProduct['alt_text'] ?? $relatedProduct['name']); ?>">
                        </a>
                    </div>
                    <div class="product-details">
                        <h4 class="product-title"><?php echo htmlspecialchars($relatedProduct['name']); ?></h4>
                        <ul class="product-specs">
                            <?php foreach (array_slice($relatedSpecs, 0, 4) as $spec): ?>
                                <li><?php echo htmlspecialchars($spec); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="product-footer">
                            <div class="price"><span class="price-prefix">€</span><?php echo formatPrice($relatedProduct['price']); ?></div>
                            <a href="/product.php?id=<?php echo $relatedProduct['product_id']; ?>" class="buy-btn">Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php include 'components/footer.html'; ?>

    <!-- JavaScript am Ende des Body für bessere Performance -->
    <script>
        // Funktion zum Wechseln des Hauptbildes in der Galerie
        function changeImage(newSrc, clickedThumbnail) {
            document.getElementById('mainProductImage').src = newSrc;
            
            // "active" Klasse von allen Thumbnails entfernen
            const thumbnails = document.querySelectorAll('.thumbnail-item');
            thumbnails.forEach(thumb => thumb.classList.remove('active'));
            
            // "active" Klasse zum geklickten Thumbnail hinzufügen
            clickedThumbnail.classList.add('active');
        }

        // Funktion für die Tabs
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-btn');
            const tabPanes = document.querySelectorAll('.tab-pane');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Alle Buttons und Panes deaktivieren
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabPanes.forEach(pane => pane.classList.remove('active'));

                    // Den geklickten Button und das zugehörige Pane aktivieren
                    const tabId = button.getAttribute('data-tab');
                    document.getElementById(tabId).classList.add('active');
                    button.classList.add('active');
                });
            });
        });
    </script>
</body>
</html>