<?php
require_once 'db_verbindung.php';
require_once 'categoryFunctions.php';

// Kategorie aus URL-Parameter ermitteln
$category = $_GET['category'] ?? 'alle';                                                                //Änderung Änderung Änderung Änderung
$filters = [                                                                                            //Änderung Änderung Änderung Änderung
    'ram' => $_GET['ram'] ?? null,                                                                      //Änderung Änderung Änderung Änderung
    'gpu' => $_GET['gpu'] ?? null,                                                                      //Änderung Änderung Änderung Änderung
    'processor' => $_GET['processor'] ?? null,                                                          //Änderung Änderung Änderung Änderung
    'storage' => $_GET['storage'] ?? null                                                               //Änderung Änderung Änderung Änderung
];                                                                                                      //Änderung Änderung Änderung Änderung
                                                                                                        //Änderung Änderung Änderung Änderung
$products = getProductsByCategory($conn, $category, "id", "asc");   //Änderung Änderung Änderung Änderung
$categoryInfo = getCategoryInfo($category);                                                   //Änderung Änderung Änderung Änderung
                                                                                                        //Änderung Änderung Änderung Änderung
?>                                                                                                      <!--Änderung Änderung Änderung Änderung-->
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


        <?php $orderBy = $_GET['orderBy'] ?? 'id';  // Standard sortieren nach ID
        $direction = (isset($_GET['sortDirection']) && $_GET['sortDirection'] === 'desc') ? 'desc' : 'asc';

        $products = getProductsByCategory($conn, $category, $orderBy, $direction);
        ?>

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
                    
                <!--    Sortierkriterium      (Michi) -->
                <div class="sort-container">
                <div>Sortieren nach:</div>

                    <!-- Formular zur Aktualisierung bei Anwenden von Sortier-Filtern -->
                    <form method="GET" id="sortForm">  
                        <div class="sort-check">                                                                   <!--Änderung Änderung Änderung Änderung-->                                                                     <!--Änderung Änderung Änderung Änderung-->
                            <select style="text-indent:4px; width: 120px; text-transform:uppercase; font-weight: bold;" name="orderBy" id="orderBy" onchange="document.getElementById('sortForm').submit()">    <!--Änderung Änderung Änderung Änderung-->
                                <option value="sales" <?php if ($orderBy === 'sales')                                                  
                                    echo 'selected'; ?>>Bestseller                                                          <!--Änderung Änderung Änderung Änderung-->
                                    </option>                                                                               <!--Änderung Änderung Änderung Änderung-->
                                <option value="price" <?php if ($orderBy === 'price')
                                    echo 'selected'; ?>>Preis                                                               <!--Änderung Änderung Änderung Änderung-->
                                    </option>                                                                               <!--Änderung Änderung Änderung Änderung-->
                                <option value="name" <?php if ($orderBy === 'name')
                                    echo 'selected'; ?>>Name                                                                <!--Änderung Änderung Änderung Änderung-->
                                    </option>                                                                               <!--Änderung Änderung Änderung Änderung-->
                            </select>                                                                                       <!--Änderung Änderung Änderung Änderung-->
                                                                                                                            <!--Änderung Änderung Änderung Änderung-->
                            <input class="hidden" type="checkbox" id="sortDirection" name="sortDirection" value="desc" <?php if ($direction === 'desc')
                                echo 'checked'; ?>                                                                                    
                                onchange="document.getElementById('sortForm').submit()" />                                  <!--Änderung Änderung Änderung Änderung-->
                            <label for="sortDirection">                                                                     <!--Änderung Änderung Änderung Änderung-->
                                <svg class="arrow-direction" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
                                    width="30" height="30" fill="currentColor">                                             <!--Änderung Änderung Änderung Änderung-->
                                    <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z" />          <!--Änderung Änderung Änderung Änderung-->
                                </svg>                                                                                      <!--Änderung Änderung Änderung Änderung-->
                            </label>                                                                                        <!--Änderung Änderung Änderung Änderung-->
                        </div>                                                                                              <!--Änderung Änderung Änderung Änderung-->
                    </form>                                                                                                 <!--Änderung Änderung Änderung Änderung-->
                </div>  
                

                <span class="filter-title">Auswahl filtern</span>

                                                                                                                    <!--Änderung Änderung Änderung Änderung-->

                <!-- Weiter Rinor -->

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
</body>

</html>