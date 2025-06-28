<!-- RINOR STUBLLA -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - <?php echo htmlspecialchars($categoryInfo['sidebarTitel']); ?></title>

    <link rel="stylesheet" href="/assets/css/categoryList.css">
    <script src="/assets/javascript/categoryFilter.js"></script>
    <script src="/assets/javascript/toggleTheme.js"></script>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
</head>

<body>
    <?php include 'components/header.php'; ?>
    <?php
    // Wishlist aus Cookie holen (wichtig für Icon-Status)
    $wishlist = [];
    if (isset($_COOKIE['wishlist'])) {
        $wishlist = json_decode($_COOKIE['wishlist'], true);
        if (!is_array($wishlist))
            $wishlist = [];
    }
    ?>




    <!-- ============ Breadcrumb ============ -->
     <!-- Breadcrumb für die Kategorie, sollte eine Kategorie eine Oberkateogrie haben sind oben 3 elemente (Home, Oberkategorie, unterkategorie) ansosnten nur (Home, Oberkategorie) -->
    <div class="breadcrumb">
        <a href="/index.php/page=home">Home</a>
        <?php if (!empty($categoryInfo['unterkategorien']) || $categoryInfo['breadcrumb'] == 'Angebote'): ?>
            <span><?php echo htmlspecialchars($categoryInfo['breadcrumb']); ?></span>
        <?php else: ?>
            <a
                href="/index.php?page=category&category=<?php echo htmlspecialchars($categoryInfo['oberkategorie']); ?>"><?php echo htmlspecialchars($categoryInfo['breadcrumbBefore']); ?></a>

            <span><?php echo htmlspecialchars($categoryInfo['breadcrumb']); ?></span>
        <?php endif; ?>
    </div>

    <div class="main-content">




        <!-- ============ Sidebar ============-->
        <div class="sidebar">
            <div class="sidebar-title"><?php echo strtoupper($categoryInfo['sidebarTitel']); ?>
                <img src="/assets/images/icons/filter-toggle.png" alt="Ein/Ausklappen" onclick="toggleSidebar()"
                    class="toggle-icon">
            </div>
            <div class="sidebar-all" id="sidebarContent">
                <ul class="sidebar-menu">
                    <!-- Sollt die Kategorie eine Oberkategorie sein dann sollen Unterkategorie Links in der Sidebar sein -->
                    <?php if (!empty($categoryInfo['unterkategorien'])): ?>
                        <?php foreach ($categoryInfo['unterkategorien'] as $uk): ?> <!-- Schleife durch alle Unterkategorien dynamisch erzeugen -->
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

                    echo "<div class='filters'>";
                    // Filter-HTML generieren
                    // nur wenn die Kategorie nicht 'alle' oder 'zubehör' ist
                    // dann werden die Filter angezeigt
                    if ($category !== 'alle' && $category !== 'zubehör') {
                        // zaehler ist hier nur wichtig um die erste Filter-Gruppe zu öffnen
                        $zaehler = 0;
                        foreach ($filters as $filterName => $values) {
                            // unique Werte für den Filter
                            // array_unique entfernt doppelte Werte aus dem Array
                            $uniqueValues = array_unique($values);

                            if (count($uniqueValues) >= 1) {
                                echo "<div class='filter-group'>";
                                // hier werden die Filter-Überschriften generiert
                                // wenn der Zähler 0 ist, dann wird die Gruppe geöffnet, sonst geschlossen
                                if ($zaehler == 0) {
                                    echo "<div class='filter-header open' onclick='toggleFilter(this)'><span>$filterName</span><span class='arrow'>▼</span></div>";
                                    echo "<ul class='filter-options'>";
                                } else {
                                    echo "<div class='filter-header' onclick='toggleFilter(this)'><span>$filterName</span><span class='arrow'>▼</span></div>";
                                    echo "<ul class='filter-options collapsed'>";
                                }
                                $zaehler++;


                                foreach ($uniqueValues as $value) {
                                    // Zähle die Anzahl der Vorkommen des Wertes in der Liste
                                    $count = array_count_values($values)[$value];

                                    // Erstelle eine eindeutige ID für das Label
                                    // Diese ID wird für das Label verwendet, damit es mit dem Input-Feld verknüpft ist
                                    // Die ID wird aus dem Filter-Namen und dem Wert zusammengesetzt
                                    $labelId = $filterName . "_" . $value;

                                    // hier wird die Checkbox für den Filter generiert
                                    // die Checkbox hat eine Klasse "filter-checkbox" und ein data-Attribut "data-filter"
                                    // das data-Attribut enthält den Filter-Namen in Kleinbuchstaben
                                    echo "<li>
                                            <input 
                                                type='checkbox' 
                                                id='$labelId' 
                                                class='filter-checkbox' 
                                                data-filter='" . strtolower($filterName) . "' 
                                                value='" . htmlspecialchars($value) . "'>
                                            <label for='$labelId'>" . htmlspecialchars($value) . " ($count)</label>
                                        </li>";
                                }

                                echo "</ul>";
                                echo "</div>";
                            }
                        }

                    } else {
                        // Wenn keine Filter verfügbar sind, wird eine Nachricht angezeigt
                        echo "<div class='filter-group'>";
                        echo "<span class='no-filter'>Keine Filter verfügbar</span>";
                        echo "</div>";


                    }
                    echo "</div>"; // Schließt die Filter-Div
                    ?>
                    <?php
                    // Wenn die Kategorie nicht 'alle' oder 'zubehör' ist, werden die Filter-Buttons angezeigt
                    // Diese Buttons sind zum Zurücksetzen und Anwenden der Filter
                    if ($category !== 'alle' && $category !== 'zubehör') {
                        echo '<div class="filterButtons">';
                        echo '<button class="reset-btn" id="resetFilterBtn">Zurücksetzen</button>';
                        echo '<button class="safe-btn" id="applyFilterBtn" type="button" disabled>Anwenden</button>
                        </div>';
                    }
                    ?>

                </div>

            </div>
        </div>

        
        <div class="products">
           
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

            </div>

            <!-- Unterkategorien -->
            <!-- Wenn die Kategorie Unterkategorien hat, dann werden diese angezeigt -->
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
                    
                    <select class="orderSelect" id="orderBy">
                        <!-- Die Optionen für die Sortierung -->
                        <option value="sales" <?= $orderBy === 'sales' ? 'selected' : '' ?>>Bestseller</option>
                        <option value="price" <?= $orderBy === 'price' ? 'selected' : '' ?>>Preis</option>
                        <option value="name" <?= $orderBy === 'name' ? 'selected' : '' ?>>Name</option>
                    </select>

                    <input type="checkbox" id="sortDirection" class="hidden" <?= $direction === 'desc' ? 'checked' : '' ?> />
                    <label for="sortDirection" onclick="toggleSort()">
                        <p id="sortButton">
                            <?= ($direction === 'asc') ? 'Aufsteigend' : 'Absteigend'; ?>
                        </p>
                        <svg class="arrow-direction" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
                            width="30" height="30" fill="currentColor">
                            <path d="M647-440H160v-80h487L423-744l57-56 320 320-320 320-57-56 224-224Z" />
                        </svg>
                    </label>
                </div>
            </div>


            <!-- Products Grid -->
            <!-- Hier werden die Produkte angezeigt, die in der Kategorie sind -->
            <div class="products-grid">
                <!-- Wenn keine Produkte in der Kategorie sind, wird eine Nachricht angezeigt -->
                <!-- Diese Nachricht wird nur angezeigt, wenn die Kategorie leer ist -->
                <?php if (empty($products)): ?>
                    <div class="no-products" style="text-align: center; padding: 40px; color: #666;">
                        <p>Derzeit sind keine Produkte in dieser Kategorie verfügbar.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($products as $product):      // Schleife durch alle geladenen Produkte.  ?>
                        <?php
                        // Hier wird das erste Bild des Produkts ausgewählt
                        // Wenn das Produkt keine Bilder hat, wird ein Platzhalter-Bild verwendet
                        $firstImage = $product['images'][0]['file_path']
                            ?? 'assets/images/placeholder.png';

                        ?>
                        
                        <div class="product">
                            <?php if ($product['sale']) {
                                // wenn das Produkt im sale dann erstelle ein Badge oben rechts SALE
                                echo '<span class="product-badge">SALE %</span>';
                            }
                            ?>
                            <div class="product-image">
                                <a class="product-image-buy"
                                    href="/index.php?page=product&id=<?php echo $product['product_id']; ?>"> <img
                                        src="<?php echo htmlspecialchars($firstImage); ?>" alt="<?php $product['name']; ?>"></a>
                            </div>
                            </a>
                            <div class="product-details">
                                <h4 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                                <ul class="product-specs">
                                    <?php foreach ($product['specs'] as $spec): ?>
                                    <!-- Hier werden alle Specs eines produktes als Listeneintrag in html gesetzt und im Produkt container dargestellt-->
                                        <li><?php echo htmlspecialchars($spec); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <div class="product-footer">
                                    <div class="price-row">
                                    <!-- Rabatt Preis anzeigen, wenn Sale gesetzt ist, es einen alten Preis gibt und dieser höher ist als der aktuelle Preis -->
                                        <?php if (!empty($product['old_price']) && $product['old_price'] > $product['price'] && $product['sale'] == 1): ?>
                                            <span class="old-price">€<?php echo formatPrice($product['old_price']); ?></span>
                                            <span class="price onsale">€<?php echo formatPrice($product['price']); ?></span>
                                        <?php else: ?>
                                            <span class="price">€<?php echo formatPrice($product['price']); ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <div class="financing"><span>Jetzt mit 0% Finanzierung</span></div>
                                    <div class="button-container">
                                        <a href="/index.php?page=product&id=<?php echo $product['product_id']; ?>"
                                            class="buy-btn">
                                            Mehr zum Produkt
                                        </a>
                                        <?php
                                        $isInWishlist = in_array($product['product_id'], $wishlist);
                                        $icon = $isInWishlist ? 'favorite.svg' : 'favorite-border.svg';
                                        ?>
                                        <button class="favorite-btn" data-id="<?php echo $product['product_id']; ?>"
                                            data-in-wishlist="<?php echo $isInWishlist ? '1' : '0'; ?>">
                                            <img src="/assets/images/icons/<?php echo $icon; ?>" alt="Favorit">
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.php'; ?>



    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const orderSelect = document.getElementById('orderBy');
            const dirCheckbox = document.getElementById('sortDirection');
            const currentParams = new URLSearchParams(window.location.search);
            const categoryParam = currentParams.get('category') || 'alle';


            // Wenn der User eine neue Sortier-Spalte auswählt
            orderSelect.addEventListener('change', () => {
                const params = new URLSearchParams(window.location.search);
                params.set('orderBy', orderSelect.value);
                // Kategorie beibehalten
                params.set('category', categoryParam);
                window.location.search = params.toString();
            });

            // Wenn der User auf das Richtungs-Toggle klickt
            dirCheckbox.addEventListener('change', () => {

                const params = new URLSearchParams(window.location.search);
                // Checkbox=checked → desc, sonst asc
                params.set('direction', dirCheckbox.checked ? 'desc' : 'asc');
                params.set('category', categoryParam);

                window.location.search = params.toString();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const params = new URLSearchParams(window.location.search);
            // Prüfen, ob ein Sort-Parameter gesetzt ist
            if (params.has('orderBy') || params.has('direction')) {
                const target = document.querySelector('.title-and-sort');
                if (target) {
                    // sanft scrollen
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    </script>
</body>

</html>