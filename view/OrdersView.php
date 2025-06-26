<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MLR | Meine Bestellungen</title>

    <link rel="stylesheet" href="/assets/css/ordersPage.css"> <!-- Du musst diese CSS-Datei noch erstellen -->
</head>

<body>
    <?php include './components/header.php'; ?>
    <main class="orders-page-container">
        <h1>Meine Bestellungen</h1>

        <div class="orders-list">
            <?php if (empty($orders)): ?>
                <p class="no-orders-message">Sie haben noch keine Bestellungen getätigt.</p>
                <a href="/index.php" class="btn-shop-now">Jetzt einkaufen</a>
            <?php else: ?>
                <!-- Tabellenkopf für die Übersicht -->
                <div class="order-list-header">
                    <span>Bestell-Nr.</span>
                    <span>Datum</span>
                    <span>Status</span>
                    <span>Gesamt</span>
                </div>

                <?php foreach ($orders as $order): ?>
                    <!-- Jeder Container ist ein Link zur Detailseite -->
                    <a href="/index.php?page=order_success&id=<?= $order['order_id'] ?>" class="order-item-container">
                        <div class="order-data">#<?= htmlspecialchars($order['order_id']) ?></div>
                        <div class="order-data"><?= date('d.m.Y', strtotime($order['order_date'])) ?></div>

                        <!-- KORREKTUR HIER: Klassen direkt auf das div anwenden -->
                        <div class="order-data">
                            <?= $model->getStatus($order['status_name']) ?>
                        </div>

                        <div class="order-data"><?= number_format($order['total_amount'], 2, ',', '.') ?> €</div>
                        <div class="order-data order-arrow">›</div>
                    </a>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>