<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Warenkorb</title>
    <link rel="icon" href="../assets/images/logo/favicon.png" />

    <link rel="stylesheet" href="../assets/css/colors.css" />
    <link rel="stylesheet" href="/assets/css/cartPage.css">    
    <link rel="stylesheet" href="../assets/css/specialHeader.css" />
    <link rel="stylesheet" href="../assets/css/footer.css" />

    <link rel="stylesheet" href="../assets/css/warenkorbSide.css" />

    <script src="/assets/javascript/toggleTheme.js"></script>

</head>

<body class="darkMode">

    <header>
        <a href="/index.php">
            <img src="../assets/images/logo/logoDarkmode.png" alt="Logo" class="logoHeader" />
        </a>
        <img id="themeToggleBtn" class="toggleThemeSpecial" src="/assets/images/icons/darkmode-btn.png"
            onclick="toggleTheme()" />
    </header>


    <main class="cart-page">
        <h1>Dein Warenkorb</h1>

        <?php if (empty($cartItems)): ?>
            <p>Dein Warenkorb ist leer.</p>
        <?php else: ?>
            <div class="cart-items">
                <?php $total = 0; ?>
                <?php foreach ($cartItems as $item): ?>
                    <?php $lineTotal = $item['price'] * $item['quantity']; ?>
                    <?php $total += $lineTotal; ?>

                    <div class="cart-item">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                        <div class="details">
                            <h3><?= htmlspecialchars($item['name']) ?></h3>
                            <p>Preis: <?= number_format($item['price'], 2, ',', '.') ?> €</p>
                            <p>Menge: <?= $item['quantity'] ?></p>
                            <p>Summe: <?= number_format($lineTotal, 2, ',', '.') ?> €</p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <h2>Gesamt: <?= number_format($total, 2, ',', '.') ?> €</h2>

            <a href="checkout.php" class="btn-checkout">Zur Kasse</a>
        <?php endif; ?>
    </main>

    <?php include 'components/footer.html'; ?>

    <footer>
        <nav>
            <p>© 2025 MLR | <a href="/index.php?page=about">Impressum</a></p>
        </nav>
    </footer>


</body>

</html>