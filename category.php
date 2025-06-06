<?php
require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// Kategorie aus URL-Parameter ermitteln
$category = $_GET['category'] ?? 'alle';
$filters = [
    'ram' => $_GET['ram'] ?? null,
    'gpu' => $_GET['gpu'] ?? null,
    'processor' => $_GET['processor'] ?? null,
    'storage' => $_GET['storage'] ?? null
];

$products = getProductsByCategory($conn, $category, "id", "asc");
$categoryInfo = getCategoryInfo($category);

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - <?php echo htmlspecialchars($categoryInfo['sidebarTitel']); ?></title>
    <link rel="stylesheet" href="/assets/css/categoryList.css">
    <link rel="stylesheet" href="/assets/css/mystyle.css">
    <script src="/assets/javascript/base.js"></script>
    <script src="/assets/javascript/toggleTheme.js"></script>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
</head>

<body>
    <?php include 'components/header.html'; ?>

    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="index.html">MLR</a> › <span><?php echo htmlspecialchars($categoryInfo['breadcrumb']); ?></span>
    </div>

    <div class="main-content">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-title"><?php echo strtoupper($categoryInfo['sidebarTitel']); ?> 
            <img src="/assets/images/image.png" alt="Ein/Ausklappen" onclick="toggleSidebar()" class="toggle-icon"></div>
            <div class="sidebar-all" id="sidebarContent">
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





                    <!-- Weiter Rinor -->

                    <?php
                    // Filter dynamisch generieren basierend auf verfügbaren Produkten
                    $filters = [];

                    foreach ($products as $product) {

                        $filters = generateFilter($filters, $product);

                    }

                    // Filter-HTML generieren
                    if ($category !== 'alle' && $category !== 'zubehör') {


                        foreach ($filters as $filterName => $values) {
                            $uniqueValues = array_unique($values);
                            if (count($uniqueValues) >= 1) {
                                echo "<div class='filter-group'>";
                                echo "<div class='filter-header' onclick='toggleFilter(this)'><span>$filterName</span><span class='arrow'>▼</span></div>";

                                echo "<ul class='filter-options'>";

                                foreach ($uniqueValues as $value) {
                                    $count = array_count_values($values)[$value];
                                    $id = strtolower(str_replace(' ', '-', $filterName . '-' . $value));
                                    echo "<li><input type='checkbox' id='$id' class='filter-checkbox'> <label for='$id'>$value ($count)</label></li>";
                                }

                                echo "</ul>";
                                echo "</div>";


                            }
                        }
                    } else {
                        echo "<div class='filter-group'>";
                        echo "<span class='no-filter'>Keine Filter verfügbar</span>";
                        echo "</div>";


                    }
                    ?>
                    <?php
                    if ($category !== 'alle' && $category !== 'zubehör') {
                        echo '<div class="filterButtons">';
                        echo '<a href=category.php?category=' . $category . ' class="reset-btn">Zurücksetzen </a>';
                        echo '<button class="safe-btn">Anwenden</button>
                        </div>';
                    }
                    ?>

                </div>
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
                <h3 class="section-title">UNTERKATEGORIE WÄHLEN:</h3>

                <div class="category-container">

                    <?php foreach ($categoryInfo['unterkategorien'] as $uk): ?>
                        <a class="product-container-link " href="<?php echo htmlspecialchars($uk['link']); ?>">
                            <div class="category-card">
                                <img src="<?php echo htmlspecialchars($uk['bild']); ?>"
                                    alt="<?php echo htmlspecialchars($uk['name']); ?>">
                                <div class="category-overlay"></div>
                                <div class="category-content">
                                    <h3 class="category-title"><?php echo htmlspecialchars($uk['name']); ?></h3>

                                    <a href="<?php echo htmlspecialchars($uk['link']); ?>" class="category-link">MEHR
                                        ERFAHREN</a>
                                </div>
                            </div>
                        </a>

                    <?php endforeach; ?>
                </div>
            <?php endif; ?>




            <!-- Products Grid -->
            <div class="title-and-sort">
                <h3 class="section-title">UNSERE TOPSELLER</h3>
                <!--    Sortierkriterium      (Michi) -->
                <div class="sort-container">
                    <select id="orderBy">
                        <option value="sales">Bestseller</option>
                        <option value="price">Preis</option>
                        <option value="name">Name</option>
                    </select>

                    <input type="checkbox" id="sortDirection" class="hidden" />
                    <label for="sortDirection" onclick="toggleSort()">
                        <p id="sortButton">Aufsteigend</p>
                        <svg class="arrow-direction" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
                            width="30" height="30" fill="currentColor">
                            <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z" />
                        </svg>
                    </label>
                </div>
            </div>



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
                                <div class="product-footer">
                                    <div class="price">
                                        <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                                    </div>
                                    <div class="financing"><span>Jetzt mit 0% Finanzierung</span></div>
                                    <a href="/productPages/product.php?id=<?php echo $product['product_id']; ?>"
                                        class="buy-btn">Mehr zum produkt</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.html'; ?>

    <script>
        function toggleFilter(header) {
            const options = header.nextElementSibling;
            options.classList.toggle('collapsed');
            header.classList.toggle('open');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const allOptions = document.querySelectorAll('.filter-options');
            const allHeaders = document.querySelectorAll('.filter-header');

            allOptions.forEach((el, index) => {
                if (index === 0) {
                    el.classList.remove('collapsed'); // Erstes Element offen lassen
                    allHeaders[index].classList.add('open');
                } else {
                    el.classList.add('collapsed'); // Alle anderen einklappen
                }
            });
        });
    </script>

    <script>


        function toggleSort() {
            const btn = document.getElementById('sortButton');

            if (btn.textContent === 'Aufsteigend') {
                btn.textContent = 'Absteigend';
            } else {
                btn.textContent = 'Aufsteigend';
            }

            // Optional: Sortierfunktion aufrufen oder Daten neu laden

        }
    </script>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarContent');
    const icon = document.querySelector('.toggle-icon');

    sidebar.classList.toggle('closed');
    icon.classList.toggle('rotated');
}
</script>





</body>

</html>