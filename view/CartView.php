<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Warenkorb</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" />
    <link rel="stylesheet" href="/assets/css/cartPage.css">

    <script src="/assets/javascript/toggleTheme.js"></script>

    <script src="/assets/javascript/mainWarenkorb.js"></script>

</head>

<body>

    <?php include './components/header.php'; ?>

    <main class="cart-page">
        <h1>Dein Warenkorb</h1>

        <?php if (empty($cartItems)): ?>
            <p>Dein Warenkorb ist leer.</p>
        <?php else: ?>
            <div class="cart-container">
                <div class="cart-items">
                    <?php $total = 0; ?>
                    <?php foreach ($cartItems as $item): ?>
                        <?php
                        $lineTotal = $item['price'] * $item['quantity'];
                        $total += $lineTotal;
                        ?>
                        <div class="cart-item" data-product-id="<?= htmlspecialchars($item['product_id']) ?>">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">

                            <div class="product-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <p class="price"><?= number_format($item['price'], 2, ',', '.') ?> €</p>
                            </div>

                            <div class="quantity-controls">
                                <button class="qty-btn" data-action="decrease">-</button>
                                <input class="quantity-display" value="<?= $item['quantity'] ?>" readonly>
                                <button class="qty-btn" data-action="increase">+</button>
                            </div>

                            <div class="item-total">
                                <?= number_format($lineTotal, 2, ',', '.') ?> €
                            </div>

                            <button class="remove-btn">
                                Entfernen
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="cart-summary">
                    <h3 class="summary-title">Bestellübersicht</h3>

                    <div class="summary-row">
                        <span class="summary-label">Zwischensumme (Netto):</span>
                        <span class="summary-value"><?= number_format($total-$total*0.19, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">Versandkosten:</span>
                        <?php if ($total > 29.99): ?>
                            <span class="summary-value">Kostenlos</span>
                        <?php else: ?>
                            <span class="summary-value">4,99 €</span>
                         <?php   $total += 4.99; ?>
                        <?php endif; ?>        
                        
                    </div>

                    <div class="summary-row">
                        <span class="summary-label">MwSt. (19%):</span>
                        <span class="summary-value"><?= number_format($total * 0.19, 2, ',', '.') ?> €</span>
                    </div>

                    <div class="summary-row total">
                        <span class="summary-label">Gesamt:</span>
                        <span class="summary-value"><?= number_format($total, 2, ',', '.') ?> €</span>
                    </div>

                    <a href="/api/checkout.php" class="btn-checkout">Zur Kasse</a>
                </div>
            </div>

        <?php endif; ?>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>