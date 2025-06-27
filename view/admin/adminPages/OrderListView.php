<!-- MICHAEL PIETSCH -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin | Bestellungen</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

<!-- javascript -->
  <script src="/assets/javascript/admin/changeStatusOrder.js"></script>
    <!-- Styling -->
    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>


    <div class="page-wrapper">

        <a class="back-last-page" href="/index.php?page=admin">Zurück</a>

        <div class="orders-wrapper">
            <?php foreach ($orders as $order): ?>
                <div class="order">
                    <div class="order-header">
                        <div class="order-id">#<?= $order['order_id'] ?></div>
                        <div class="order-date"><?= date("d.m.Y", strtotime($order['order_date'])) ?></div>
                    </div>
                    <div class="order-body">
                        <div class="order-info">
                            <div><strong>Benutzer-ID:</strong> <?= $order['user_id'] ?></div>
                            <div><strong>Gesamt:</strong> <?= number_format($order['total_amount'], 2, ',', '.') ?> €</div>
                            <div><strong>Adresse:</strong> <?= htmlspecialchars($order['shipping_address']) ?></div>
                        </div>
                        <div class="order-status-more">
                            <div class="status-color" id="status-color-order-<?= $order['order_id']?>" style="background-color:
                                <?= $order['status_id'] == 1 ? 'darkorange' : '' ?>
                                <?= $order['status_id'] == 2 ? 'cornflowerblue' : '' ?>
                                <?= $order['status_id'] == 3 ? 'dodgerblue' : '' ?>
                                <?= $order['status_id'] == 4 ? 'forestgreen' : '' ?>
                                <?= $order['status_id'] == 5 ? 'peru' : '' ?>
                                <?= $order['status_id'] == 6 ? 'sienna' : '' ?>
                                <?= $order['status_id'] == 7 ? 'crimson' : '' ?>"></div>
                            <div class="status-more-wrapper">
                                <div class="status-styling">
                                    <label for="status-select-order-<?= $order['order_id'] ?>">Status:</label>
                                    <select id="status-select-order-<?= $order['order_id'] ?>" onchange="changeOrderStatus(<?= $order['order_id'] ?>)">
                                        <option value="1" <?= $order['status_id'] == 1 ? 'selected' : '' ?>>Eingegangen
                                        </option>
                                        <option value="2" <?= $order['status_id'] == 2 ? 'selected' : '' ?>>Wird bearbeitet
                                        </option>
                                        <option value="3" <?= $order['status_id'] == 3 ? 'selected' : '' ?>>Verschickt</option>
                                        <option value="4" <?= $order['status_id'] == 4 ? 'selected' : '' ?>>Geliefert</option>
                                        <option value="5" <?= $order['status_id'] == 5 ? 'selected' : '' ?>>Storniert</option>
                                        <option value="6" <?= $order['status_id'] == 6 ? 'selected' : '' ?>>Zurückgesendet
                                        </option>
                                        <option value="7" <?= $order['status_id'] == 7 ? 'selected' : '' ?>>Fehlgeschlagen
                                        </option>
                                    </select>
                                </div>
                                <a class="more"
                                    href="index.php?page=admin&action=order&order-id=<?= $order['order_id'] ?>">Mehr</a>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>


</html>