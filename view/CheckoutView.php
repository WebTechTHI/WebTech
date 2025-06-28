<!-- RINOR STUBLLA -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <title>MLR | Kasse</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="/assets/css/checkoutPage.css">
    <script src="/assets/javascript/coupon_validation.js"></script>
</head>

<body>

    <?php include 'components/header.php'; ?>
<!-- FALLS JEMAND KEINE VOLLSTÄNDIGEN LIEFERDATEN DANN ERST FORMULAR AUSFÜLLEN-->
<!-- Im controller wird dann die Session mit richtigen werten aktualisiert und in in die Datenbank eingetragen-->
<?php if(!$hasValidShippingInfo ):?>
    <div class="address-update-modal">
    <h3>Bitte vervollständigen Sie Ihre Lieferdaten</h3>
    <form method="POST" action="">
        <div class="form-group">
            <label for="richtiger_name">Vor und Nachname:</label>
            <input type="text" id="richtiger_name" name="richtiger_name"
                value="<?= htmlspecialchars($_SESSION['user']['richtiger_name'] ?? '') ?>"
                required>
        </div>
        <div class="form-group">
            <label for="straße">Straße:</label>
            <input type="text" id="straße" name="straße"
                value="<?= htmlspecialchars($_SESSION['user']['straße'] ?? '') ?>"
                required>
        </div>
        <div class="form-group">
            <label for="plz">PLZ:</label>
            <input type="text" id="plz" name="plz"
                value="<?= htmlspecialchars($_SESSION['user']['plz'] ?? '') ?>"
                required>
        </div>
        <div class="form-group">
            <label for="stadt">Stadt:</label>
            <input type="text" id="stadt" name="stadt"
                value="<?= htmlspecialchars($_SESSION['user']['stadt'] ?? '') ?>"
                required>
        </div>
        <div class="form-group">
            <label for="email">E-Mail:</label>
            <input type="email" id="email" name="email"
                value="<?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>"
                required>
        </div>
        <button type="submit" name="save_shipping_info" class="btn-save-address">
            Daten speichern
        </button>
    </form>
</div>
<?php else: ?>

  

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

                    <p id="original-total-row" class="grand-total">
                        <span>Gesamt:</span>
                        <!-- Wir speichern den Originalwert in einem data-Attribut -->
                        <span id="original-total-amount" data-original-total="<?= $total ?>">
                            <?= number_format($total, 2, ',', '.') ?> €
                        </span>
                    </p>

                    <!-- Diese Zeile ist für den Rabatt (standardmäßig versteckt) -->
                    <p id="discount-row" style="display: none; color: green;">
                        <span id="discount-label"></span> 
                        <span id="discount-amount"></span> 
                    </p>

                    <!-- Diese Zeile ist für den neuen Gesamtpreis (standardmäßig versteckt) -->
                    <p id="new-total-row" class="grand-total"
                        style="display: none; border-top: 1px solid #ccc; padding-top: 10px;">
                        <span>Neuer Gesamtbetrag:</span>
                        <span id="new-total-amount"></span>
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

                <div class="coupons">
                    <h3>Rabattcode eingeben:</h3>
                    <input type="text" id="coupon-input" class="discounter-input" placeholder="MLR2025">
                    <button id="coupon-btn" class="dicounter-btn">Einlösen</button>
                    <div id="coupon-message" style="margin-top: 10px;"></div>
                </div>


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


                <form method="POST" action="/index.php?page=checkout">
                    <input type="hidden" name="applied_coupon_code" id="applied-coupon-code" value="">

                    <p class="terms-text">Mit dem Klick auf "Jetzt kostenpflichtig bestellen" gehen Sie einen
                        rechtsverbindlichen Kaufvertrag ein.</p>
                    <button type="submit" class="btn-order-final">Jetzt kostenpflichtig bestellen</button>
                </form>
            </div>
        </div>
    </main>

    <?php endif; ?>

    <?php include 'components/footer.php'; ?>

    <script>

    </script>

</body>

</html>