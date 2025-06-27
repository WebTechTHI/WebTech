<!-- MICHAEL PIETSCH -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Pübersicht</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <!-- Styling -->
    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>


    <div class="page-wrapper">

        <a class="back-last-page" href="/index.php?page=admin">Zurück</a>

        <div class="products-wrapper">

            <?php foreach ($products as $product):
                
                //Produktild laden
                $firstImage = !empty($product['images']) ? $product['images'][0]['file_path'] : 'assets/images/placeholder.png';
                ?>

                <!--  Produktcontainer zusammenbauen -->
                <div class="product">

                <!--       Button zum Löschen des Produkts     -->
                    <a href="/index.php?page=admin&action=delete&id=<?php echo $product['product_id']; ?>"
                        class="delete-button" title="Produkt löschen"
                        onclick="return confirm('Willst du dieses Produkt wirklich löschen?');">
                        ✖
                    </a>

                    <?php if ($product['sale']): ?>
                        <span class="product-badge">SALE</span>
                    <?php endif; ?>

                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($firstImage); ?>"
                            alt="<?php echo htmlspecialchars($product['alt_text'] ?? $product['name']); ?>">
                    </div>
                    <div class="product-details">
                        <h4 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                        <ul class="product-specs">
                            <?php foreach ($product['specs'] as $spec): ?>
                                <li><?php echo htmlspecialchars($spec); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="product-footer">
                            <div class="price">
                                <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                            </div>
                        </div>
                        <a href="/index.php?page=admin&action=edit&id=<?php echo $product['product_id']; ?>"
                            class="edit-product">Produkt bearbeiten</a>
                    </div>
                </div>

            <?php endforeach; ?>

        </div>
    </div>

</body>

</html>