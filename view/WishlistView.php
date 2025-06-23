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
  <link rel="stylesheet" href="/assets/css/wishlist.css" />

  <!-- Scripts -->
  <script src="/assets/javascript/toggleTheme.js"></script>
</head>

<body class="wishlist-body">

  <?php include './components/header.php'; ?>

  <main class="wishlist-page">
    <h1>Deine Wunschliste</h1>

    <?php if (empty($wishlistItems)): ?>
      <div class="empty-wishlist">
        Deine Wunschliste ist aktuell leer.
      </div>
    <?php else: ?>
      <div class="wishlist-container">
        <?php foreach ($wishlistItems as $item): ?>
          <div class="wishlist-card">
            <a href="/index.php?page=product&id=<?= htmlspecialchars($item['product_id']) ?>">
              <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
              <div class="wishlist-info">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p class="price"><?= number_format($item['price'], 2, ',', '.') ?> €</p>
              </div>
            </a>
            <button class="remove-from-wishlist-btn" data-id="<?= htmlspecialchars($item['product_id']) ?>">Entfernen</button>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </main>

  <?php include 'components/footer.php'; ?>

  <script src="/assets/javascript/wishlist.js"></script>

</body>

</html>
