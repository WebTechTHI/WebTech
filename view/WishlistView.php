<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Wunschliste</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" />

    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/components/header.css" />
    <link rel="stylesheet" href="/assets/css/colors.css" />
    <link rel="stylesheet" href="/assets/css/loginregistration.css" /> <!-- Für Meldungs-Design -->
    <link rel="stylesheet" href="/assets/css/wishlistPage.css" />
    <link rel="stylesheet" href="/assets/css/footer.css" />

    <!-- Scripts -->
    <script src="/assets/javascript/toggleTheme.js"></script>
</head>

<body>

    <?php include './components/header.php'; ?>

    <main class="wishlist-page">
        <h1>Deine Wunschliste</h1>

        <!-- Meldungsblock für JS -->
        <div id="meldung-block"></div>

        <?php if (empty($wishlistItems)): ?>
            <p>Deine Wunschliste ist leer.</p>
        <?php else: ?>
            <div class="wishlist-container">
                <?php foreach ($wishlistItems as $item): ?>
                    <div class="wishlist-card">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">

                        <div class="wishlist-info">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p class="price"><?= number_format($item['price'], 2, ',', '.') ?> €</p>
                        </div>

                        <div class="wishlist-actions">
                            <button class="move-to-cart-btn" data-id="<?= htmlspecialchars($item['product_id']) ?>">
                                In den Warenkorb
                            </button>
                            <button class="remove-btn" data-product-id="<?= htmlspecialchars($item['product_id']) ?>">
                                Entfernen
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="wishlist-actions-bottom">
                <button id="move-all-to-cart-btn">Alle Produkte in den Warenkorb</button>
            </div>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.html'; ?>

    <!-- Wishlist Logik -->
    <script src="/assets/javascript/wishlist.js"></script>

</body>
</html>
