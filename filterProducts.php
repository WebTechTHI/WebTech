<?php
require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// JSON-Body einlesen
$data = json_decode(file_get_contents('php://input'), true);

$category = $data['category'] ?? 'alle';
$filters = $data['filters'] ?? [];

$products = getProductsByCategory($conn, $category, 'id', 'asc', $filters);

?>

<?php if (empty($products)): ?>
    <div class="no-products" style="text-align: center; padding: 40px; color: #666;">
        <p>Derzeit sind keine Produkte in dieser Kategorie verfügbar.</p>
    </div>
<?php else: ?>
    <?php foreach ($products as $product): ?>
        <?php
        $images = getProductImages($conn, $product['product_id']);
        $firstImage = !empty($images) ? $images[0]['file_path'] : 'assets/images/placeholder.png';
        $specs = buildSpecifications($product);
        ?>
        <div class="product">
            <?php if ($product['sale']) {
                echo '<span class="product-badge">SALE %</span>';
            }  ?>
            <div class="product-image">
                <a class="product-image-buy"
                    href="/productPages/product.php?id=<?php echo $product['product_id']; ?>">
                    <img src="<?php echo htmlspecialchars($firstImage); ?>"
                        alt="<?php echo htmlspecialchars($product['alt_text'] ?? $product['name']); ?>">
                </a>
            </div>
            <div class="product-details">
                <h4 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                <ul class="product-specs">
                    <?php foreach ($specs as $spec): ?>
                        <li><?php echo htmlspecialchars($spec); ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="product-footer">
                    <div class="price">
                        <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                    </div>
                    <div class="financing"><span>Jetzt mit 0% Finanzierung</span></div>
                    <div class="button-container">
                        <a href="/productPages/product.php?id=<?php echo $product['product_id']; ?>"
                            class="buy-btn">Mehr zum produkt</a>
                        <button class="favorite-btn">
                            <img src="/assets/images/icons/favorite-border.svg" alt="Favorit" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>



