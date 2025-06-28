<!-- RINOR STUBLLA -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Bestellung erfolgreich</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" />
    <link rel="stylesheet" href="/assets/css/successfulOrder.css">
    <script src="/assets/javascript/toggleTheme.js"></script>
</head>

<body>

    <?php include './components/header.php'; ?>

    <main class="order-success-container">

        <div class="success-card">
            <div class="order-status"><?= $orderStatus ?></div>

            <div class="order-center">
                <div class="success-icon">

                    <img src="/assets/images/haken.svg" alt="Bestellung erfolgreich" />
                </div>

                <h1>Vielen Dank für Ihre Bestellung!</h1>

                <p class="confirmation-text">
                    Ihre Bestellung mit der Nummer <strong>#<?= htmlspecialchars($order['order_id']) ?></strong> wurde
                    erfolgreich entgegengenommen.
                    Eine Bestätigungs-E-Mail wird in Kürze an
                    <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong> gesendet.
                </p>

                <div class="order-details-box">
                    <h2>Bestellübersicht</h2>

                    <div class="info-section">
                        <div>
                            <strong>Bestelldatum:</strong>
                            <p><?= date('d.m.Y, H:i', strtotime($order['order_date'])) ?> Uhr</p>
                        </div>
                        <div>
                            <strong>Gesamtbetrag:</strong>
                            <p><?= number_format($order['total_amount'], 2, ',', '.') ?> €</p>
                        </div>
                    </div>

                    <div class="info-section">
                        <div>
                            <strong>Lieferadresse:</strong>
                            <p><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                        </div>
                    </div>

                    <h3>Ihre Artikel</h3>
                    <div class="ordered-items-list">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="ordered-item">
                                <a href="index.php?page=product&id=<?php echo htmlspecialchars($item['product_id']) ?>">
                                    <img src="<?= htmlspecialchars($item['image'] ?? '/assets/images/placeholder.jpg') ?>"
                                        alt="<?= htmlspecialchars($item['name']) ?>" class="item-image"></a>
                                <div class="item-details">
                                    <span class="item-name"><?= htmlspecialchars($item['name']) ?></span>
                                    <span class="item-quantity">Menge: <?= htmlspecialchars($item['quantity']) ?></span>
                                </div>
                                <span
                                    class="item-price"><?= number_format($item['price_per_item'] * $item['quantity'], 2, ',', '.') ?>
                                    €</span>
                            </div>
                        <?php endforeach; ?>

                        <!-- FALLS EIN PRODUKT IM SALE IST DANN SOLL DAS HIER ANGEZEIGT WERDEN-->
                        <?php if (isset($order['coupon_id']) && isset($coupon)): ?>
                            <div class="sale-item">
                                <img src="\assets\images\icons\sale-coupon.png" alt="SALE" class="sale-image">
                                <div class="sale-details">
                                    <span class="sale-name"><?= htmlspecialchars($coupon['coupon_code']) ?> (Couponcode)</span>
                                    <span class="sale-percent"><?= $coupon['percent'] ?> %</span>
                                </div>
                                <?php $sale = ($order['total_amount'] * 100/ (100-$coupon['percent'])) - $order['total_amount'] ?>
                                <span class="sale-price">- <?= number_format($sale, 2, ',', '.')?> €</span>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

                <a href="/index.php?page=orders" class="btn btn-primary">Meine Bestellungen ansehen</a>
                <a href="/index.php" class="btn btn-secondary">Weiter einkaufen</a>
            </div>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>