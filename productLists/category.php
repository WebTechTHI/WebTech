<?php
/******************************************************************
 *  productList.php
 *  – dynamische Kategorie-/Produktseite  (MySQL 8 / PHP 8)
 ******************************************************************/

/* ------------------ 1)  DB-Verbindung ------------------------- */
$servername = "mlr-shop.de";
$username   = "shopuser";
$password   = "12345678";
$dbname     = "onlineshop";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) {
    die("Verbindung fehlgeschlagen: " . mysqli_connect_error());
}

/* ------------------ 2)  Kategorie aus der URL ----------------- */
$param = $_GET['category']  ?? $_GET['kategorie'] ?? null;
$param = strtolower(trim($param ?? ''));       // »gamingpc« usw.

/* -------- Mapping: URL-Slug  →  Subkategorie / Kategorie ------ */
$slugToSub  = [
    'gamingpc'      => 'Gaming-PC',
    'officepc'      => 'Office-PC',
    'gaminglaptop'  => 'Gaming-Laptop',
    'officelaptop'  => 'Office-Laptop',
    'monitor'       => 'Monitor',
    'maus'          => 'Maus',
    'tastatur'      => 'Tastatur'
];
$slugToCat  = [           // wenn du ganze Hauptkategorien filtern willst
    'pc'        => 'PC',
    'laptop'    => 'Laptop',
    'zubehör'   => 'Zubehör'
];

$whereSql   = '';         // wird gleich gebaut
$params     = [];         // für prepared statement

if ($param && isset($slugToSub[$param])) {
    /* nach Subkategorie filtern */
    $whereSql = 'WHERE s.name = ?';
    $params[] = $slugToSub[$param];
    $breadcrumb = $slugToSub[$param];
} elseif ($param && isset($slugToCat[$param])) {
    /* nach Hauptkategorie filtern */
    $whereSql = 'WHERE c.name = ?';
    $params[] = $slugToCat[$param];
    $breadcrumb = $slugToCat[$param];
} else {
    /* keine / unbekannte Kategorie → alles anzeigen */
    $breadcrumb = 'Alle Produkte';
}

/* ------------------ 3)  Produkte holen ------------------------ */
$sql = "
    SELECT p.*, s.name AS subcat, c.name AS cat
    FROM   product      p
    JOIN   subcategory  s ON p.subcategory_id = s.subcategory_id
    JOIN   category     c ON s.category_id    = c.category_id
    $whereSql
    ORDER BY p.price DESC
";

$stmt = mysqli_prepare($conn, $sql);
if ($params) {
    /* Bindet beliebig viele Strings an das Statement */
    mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$products = mysqli_fetch_all($result, MYSQLI_ASSOC);
mysqli_close($conn);

/* ------------------ 4)  Hilfsfunktionen ----------------------- */
function formatPrice(float $price): string {
    $str = number_format($price, 2, ',', '.');          // 1.234,56
    return preg_replace('/,00$/', ',-', $str);          // 1.234,-
}

function badge(bool $sale): string {
    return $sale ? 'SALE' : 'TOP';
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR – <?= htmlentities($breadcrumb) ?></title>
    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/mystyle.css">
    <!-- deine weiteren CSS-Dateien … -->
</head>
<body>
    <?php include 'components/header.html'; ?>

    <!-- Breadcrumb ------------------------------------------- -->
    <div class="breadcrumb">
        <a href="/">MLR</a> › <span><?= htmlentities($breadcrumb) ?></span>
    </div>

    <div class="main-content">
        <div class="products">
            <h3 class="section-title"><?= htmlentities(strtoupper($breadcrumb)) ?></h3>

            <div class="products-grid">
            <?php if (!$products): ?>
                <p style="grid-column: 1 / -1; padding: 40px; text-align:center;">
                    Keine Produkte gefunden 🙁
                </p>
            <?php else: ?>
                <?php foreach ($products as $p): ?>
                    <div class="product">
                        <span class="product-badge"><?= badge((bool)$p['sale']) ?></span>

                        <div class="product-image">
                            <!-- nimmt das erste Bild dieser Produkt-ID -->
                            <img src="/assets/images/products/<?= $p['product_id'] ?>/vorne.png"
                                 alt="<?= htmlentities($p['alt_text']) ?>">
                        </div>

                        <div class="product-details">
                            <h4 class="product-title"><?= htmlentities($p['name']) ?></h4>

                            <!-- Spezifikationen als Liste (falls gewünscht) -->
                            <?php
                            $specs = [];
                            if ($p['cpu_id'])      $specs[] = 'CPU ID '  . $p['cpu_id'];
                            if ($p['gpu_id'])      $specs[] = 'GPU ID '  . $p['gpu_id'];
                            if ($p['ram_id'])      $specs[] = 'RAM ID '  . $p['ram_id'];
                            if ($p['storage_id'])  $specs[] = 'SSD ID '  . $p['storage_id'];
                            ?>
                            <ul class="product-specs">
                                <?php foreach ($specs as $s): ?>
                                    <li><?= htmlentities($s) ?></li>
                                <?php endforeach; ?>
                            </ul>

                            <div class="price"><span class="price-prefix">€</span><?= formatPrice($p['price']) ?></div>
                            <div class="financing">Jetzt mit 0% Finanzierung</div>

                            <a href="/productPages/product.php?id=<?= $p['product_id'] ?>"
                               class="buy-btn">Jetzt konfigurieren</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            </div>
        </div>
    </div>

    <?php include 'components/footer.html'; ?>
</body>
</html>
