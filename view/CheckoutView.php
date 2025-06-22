<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>MLR | Kasse</title>
    <!-- Binde hier deine globalen CSS-Dateien und Header ein -->
    <?php include './components/header.php'; ?>
    <link rel="stylesheet" href="/assets/css/checkoutPage.css"> <!-- Du musst diese CSS-Datei noch erstellen -->
</head>
<body>
    <main class="checkout-page">
        <h1>Bestellung überprüfen</h1>
        
        <?php if(isset($error)): ?>
            <p class="error-message"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <div class="checkout-container">
            <div class="order-summary">
                <h2>Ihre Bestellung</h2>
                <?php foreach ($cartItems as $item): ?>
                    <div class="summary-item">
                        <span class="item-name"><?= htmlspecialchars($item['quantity']) ?>x <?= htmlspecialchars($item['name']) ?></span>
                        <span class="item-price"><?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?> €</span>
                    </div>
                <?php endforeach; ?>
                <hr>
                <div class="summary-total">
                    <p><span>Zwischensumme:</span> <span><?= number_format($subTotal, 2, ',', '.') ?> €</span></p>
                    <p><span>Versandkosten:</span> <span><?= $shippingCost == 0 ? 'Kostenlos' : number_format($shippingCost, 2, ',', '.') . ' €' ?></span></p>
                    <p><span>inkl. 19% MwSt.:</span> <span><?= number_format($tax, 2, ',', '.') ?> €</span></p>
                    <p class="grand-total"><span>Gesamt:</span> <span><?= number_format($total, 2, ',', '.') ?> €</span></p>
                </div>
            </div>

            <div class="order-action">
                <h2>Ihre Daten</h2>
                <p><strong>Lieferadresse:</strong></p>
                <p>
        <!-- Wir zeigen die Felder an, die in deiner User-Tabelle existieren -->
        <?= htmlspecialchars($_SESSION['user']['richtiger_name'] ?? '') ?><br>
        <?= htmlspecialchars($_SESSION['user']['stadt'] ?? '') ?><br>
        <?= htmlspecialchars($_SESSION['user']['land'] ?? '') ?><br>
         <strong>Kontakt:</strong><br>
        <?= htmlspecialchars($_SESSION['user']['email'] ?? '') ?>
                </p>
                
                <!-- Das Formular sendet die Bestellung ab -->
                <form method="POST" action="/index.php?page=checkout">
                    <p>Mit dem Klick auf "Jetzt kostenpflichtig bestellen" gehen Sie einen rechtsverbindlichen Kaufvertrag ein.</p>
                    <button type="submit" class="btn-order-final">Jetzt kostenpflichtig bestellen</button>
                </form>
            </div>
        </div>
    </main>

    <?php include 'components/footer.html'; ?>
</body>
</html>