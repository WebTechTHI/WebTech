<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>MLR | Kasse</title>
 

    <link rel="stylesheet" href="/assets/css/checkoutPage.css">
</head>

<body>

    <?php include 'components/header.php'; ?>


    <main class="checkout-page">
        <h1>Bestellung überprüfen</h1>

        <?php if (isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="order-summary">
                <h2>Ihre Bestellung</h2>
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>"
                            class="item-image">


                        <div class="item-details">
                            <div class="item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="item-quantity">Menge: <?= htmlspecialchars($item['quantity']) ?></div>
                        </div>
                        <div class="item-price"><?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?> €
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="summary-total">
                    <p><span>Zwischensumme (Netto):</span> <span><?= number_format($netto, 2, ',', '.') ?> €</span></p>
                    <p><span>inkl. 19% MwSt.:</span> <span><?= number_format($tax, 2, ',', '.') ?> €</span></p>
                    <p><span>Versandkosten:</span>
                        <span><?= $shippingCost == 0 ? 'Kostenlos' : number_format($shippingCost, 2, ',', '.') . ' €' ?></span>
                    </p>
                    
                    <p class="grand-total"><span>Gesamt:</span> <span><?= number_format($total, 2, ',', '.') ?> €</span>
                    </p>
                </div>
            </div>

            <div class="order-action">
                <h2>Bestellung abschließen</h2>

                <div class="order-adress">
                    <p><strong>Lieferadresse:</strong></p>
                    <p>
                        <?= htmlspecialchars($_SESSION['user']['richtiger_name'] ?? '') ?>
                    </p>

                    <p>
                        <?= htmlspecialchars($_SESSION['user']['straße'] ?? '') ?>
                    </p>
                    <p>
                        <?= htmlspecialchars($_SESSION['user']['plz'] ?? '') ?>
                        <?= htmlspecialchars($_SESSION['user']['stadt'] ?? '') ?>
                    </p>


                    <p> <strong>Kontakt:</strong></p>
                    <p> <?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?> </p>

                </div>
                <!-- Zahlungsarten -->
                <div class="payment-methods">
                    <h3>Zahlungsart wählen</h3>
                    <div class="payment-option">
                        <input type="radio" id="paypal" name="payment_method" value="paypal" checked>
                        <label for="paypal">PayPal</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="credit_card" name="payment_method" value="credit_card">
                        <label for="credit_card">Kreditkarte</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer">
                        <label for="bank_transfer">Überweisung</label>
                    </div>
                    <div class="payment-option">
                        <input type="radio" id="sofort" name="payment_method" value="sofort">
                        <label for="sofort">Sofortüberweisung</label>
                    </div>
                </div>

                <!-- Das Formular sendet die Bestellung ab -->
                <form method="POST" action="/index.php?page=checkout">
                    <p class="terms-text">Mit dem Klick auf "Jetzt kostenpflichtig bestellen" gehen Sie einen
                        rechtsverbindlichen Kaufvertrag ein.</p>
                    <button type="submit" class="btn-order-final">Jetzt kostenpflichtig bestellen</button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>