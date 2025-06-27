

<?php
//RINOR STUBLLA

//-- Kein Ersatz für categoryView!

 //Es ist ein Hilfs-Skript nur für AJAX. Es wird nicht direkt im Browser angezeigt, sondern:

 //       Empfängt die Filter-Daten.

 //       Fragt gefilterte Produkte aus der DB ab (getProductsByCategory(...)).

 //       Baut neue HTML-Blöcke (Produktliste + Sidebar).

//        Antwortet nur mit diesen Blöcken im JSON.

 //       JavaScript im Browser ersetzt dann nur Teile der aktuellen Seite damit.

 //       bleiben also auf categoryView, aber Inhalte ändern sich dynamisch! 
require_once 'db_verbindung.php';
require_once 'model/categoryFunctions.php';

// JSON-Body einlesen
$data = json_decode(file_get_contents('php://input'), true);

$category = $data['category'] ?? 'alle';
$filters = $data['filters'] ?? [];

$products = getProductsByCategory($conn, $category, 'id', 'asc', $filters);


ob_start(); // Startet Pufferspeicherung für Produkte
?>

<?php if (empty($products)): ?>
    <div class="no-products">
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
            } ?>
            <div class="product-image">
                <a class="product-image-buy" href="/index.php?page=product&id=<?php echo $product['product_id']; ?>">
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
                        <a href="/index.php?page=product&id=<?php echo $product['product_id']; ?>" class="buy-btn">Mehr zum
                            produkt</a>
                        <button class="favorite-btn">
                            <img src="/assets/images/icons/favorite-border.svg" alt="Favorit" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php
$productsHtml = ob_get_clean(); // Holt Produkt-HTML




ob_start(); // Startet Pufferspeicherung für Filter


$filterData = [];

foreach ($products as $product) {
    $filterData = generateFilter($filterData, $product);
}
$zaehler = 0;
foreach ($filterData as $filterName => $values) {
    $uniqueValues = array_unique($values);
    if (count($uniqueValues) >= 1) {
        echo "<div class='filter-group'>";
        if ($zaehler == 0) {
            echo "<div class='filter-header open' onclick='toggleFilter(this)'><span>$filterName</span><span class='arrow'>▼</span></div>";
            echo "<ul class='filter-options'>";
        } else {
            echo "<div class='filter-header' onclick='toggleFilter(this)'><span>$filterName</span><span class='arrow'>▼</span></div>";
            echo "<ul class='filter-options collapsed'>";
        }
        $zaehler++;
        foreach ($uniqueValues as $value) {
            $count = array_count_values($values)[$value];
            $labelId = strtolower(str_replace(' ', '-', $filterName . '-' . $value));
            echo "<li>
                <input class='filter-checkbox' type='checkbox' 
                       data-filter='" . strtolower($filterName) . "' 
                       value='" . htmlspecialchars($value) . "' 
                       id='$labelId '>
                <label for='$labelId '>" . htmlspecialchars($value) . " ($count)</label>
            </li>";
        }
        echo "</ul></div>";

    }
}

$filtersHtml = ob_get_clean(); // Holt Filter-HTML

header('Content-Type: application/json');

echo json_encode([
    'productsHtml' => $productsHtml,
    'filtersHtml' => $filtersHtml
]);
// -- filterProducts.php baut nur das HTML für die Produktliste und Filter – serverseitig.

// base.js nimmt das fertige HTML (aus dem JSON) und ersetzt damit live Teile des DOM auf der Seite categoryView.php.

// categoryView.php selbst wird dabei nie neu geladen! 
?>

