<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MLR - Gaming PCs, Laptops & High-End Computer</title>

    <link rel="stylesheet" href="/assets/css/index.css">
    <link rel="stylesheet" href="/assets/css/mystyle.css">

    <script src="/assets/javascript/toggleTheme.js"></script>
    <script src="/assets/javascript/changeHero.js"></script>

</head>

<body>

    <?php include 'components/header.html'; ?>


    <!-- Hero Section -->
    <section class="hero">
        <div id="heroImage" class="hero-image"></div>
        <div class="hero-overlay-down"></div>
        <div class="hero-overlay-right"></div>
        <div class="hero-content">
            <h1 class="hero-title">GAMING PCs mit <span>RTX 5000</span> SERIE</h1>
            <p class="hero-text">Entdecke unsere neue Generation an High-Performance Gaming-PCs mit den neuesten NVIDIA
                RTX 5000 Grafikkarten für ultimative Gaming-Power.</p>
            <div class="hero-buttons">
                <a href="category.php" class="hero-btn main-btn">JETZT ENTDECKEN</a>
                <a href="#" class="hero-btn configure-btn">KONFIGURATOR</a>
            </div>
        </div>
    </section>


    <!-- Bestseller Section -->
    <section class="container">
        <h2 class="section-title">BESTSEEEEEEEEEEELLER</h2>



        <div class="product-carousel-wrapper"> <!-- Button zum scrollen der produkte nach links -->
            <button id="scroll-left" class="scroll-button">‹</button>
            <!-- Button zum scrollen der produkte nach links -->


            <div class="products-grid" id="product-container">

                <?php require_once 'db_verbindung.php';
                require_once 'categoryFunctions.php';

                $products = getProductsByCategory($conn, '', 'sales', 'desc'); ?>

                <?php foreach ($products as $product): ?>
                    <?php
                    // Erstes Bild laden
                    $images = getProductImages($conn, $product['product_id']);

                    $firstImage = !empty($images) ? $images[0]['file_path'] : 'assets/images/placeholder.png';

                    // Spezifikationen aufbauen
                    $specs = buildSpecifications($product);
                    ?>

                    <div class="product">
                        <span class="product-badge"><?php echo $product['sale'] ? 'SALE' : 'TOP'; ?></span>
                        <div class="product-image">
                            <img src="<?php echo htmlspecialchars($firstImage); ?>"
                                alt="<?php echo htmlspecialchars($product['alt_text'] ?? $product['name']); ?>">
                        </div>
                        <div class="product-details">
                            <h4 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h4>
                            <ul class="product-specs">
                                <?php foreach ($specs as $spec): ?>
                                    <li><?php echo htmlspecialchars($spec); ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="product-footer">
                                <div class="price">
                                    <span class="price-prefix">€</span><?php echo formatPrice($product['price']); ?>
                                </div>
                                <div class="financing"><span>Jetzt mit 0% Finanzierung</span></div>
                                <a href="/product.php?id=<?php echo $product['product_id']; ?>"
                                    class="buy-btn">Mehr
                                    zum produkt</a>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            </div>


            <button id="scroll-right" class="scroll-button">›</button>
            <!-- Button zum scrollen der produkte nach rechts -->
        </div> <!-- Button zum scrollen der produkte nach rechts -->
    </section>


    <script>
        document.addEventListener('DOMContentLoaded', function () {              //-----------------------
            const container = document.getElementById('product-container');              //-----------------------
            const scrollAmount = 1; // Anzahl der Items            
            const itemWidth = document.querySelector('.product').offsetWidth + 15; // Breite + Gap              //-----------------------

            document.getElementById('scroll-left').addEventListener('click', () => {              //-----------------------
                container.scrollBy({ left: -itemWidth * scrollAmount, behavior: 'smooth' });              //-----------------------
            });

            document.getElementById('scroll-right').addEventListener('click', () => {              //-----------------------
                container.scrollBy({ left: itemWidth * scrollAmount, behavior: 'smooth' });              //-----------------------
            });
        });
    </script>



    <!-- Categories Section -->
    <section class="container">
        <h2 class="section-title">PRODUKTKATEGORIEN</h2>
        <div class="categories">
            <div class="category-card">
                <img src="/assets/images/subcategory_images/gamingPc.png" alt="Gaming PCs">
                <div class="category-overlay"></div>
                <div class="category-content">
                    <h3 class="category-title">PCs</h3>
                    <p class="category-description">Power für jeden Tag</p>
                    <a href="category.php?category=pc" class="category-link">MEHR ERFAHREN</a>
                </div>
            </div>

            <div class="category-card">
                <img src="/assets/images/subcategory_images/officeLaptop.png" alt="Gaming Laptops">
                <div class="category-overlay"></div>
                <div class="category-content">
                    <h3 class="category-title">Laptops</h3>
                    <p class="category-description">Leistung zum Mitnehmen</p>
                    <a href="category.php?category=laptop" class="category-link">MEHR ERFAHREN</a>
                </div>
            </div>

            <div class="category-card">
                <img src="/assets/images/subcategory_images/maus.png" alt="Creator PCs">
                <div class="category-overlay"></div>
                <div class="category-content">
                    <h3 class="category-title">zubehör</h3>
                    <p class="category-description">Perfekte Ergänzung für dein Setup</p>
                    <a href="category.php?category=zubehör" class="category-link">MEHR ERFAHREN</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Promos Section -->
    <section class="container">
        <div class="promos">
            <div class="main-promo">
                <div class="main-promo-content">
                    <h3 class="main-promo-title">KONFIGURIERE DEINEN <span>TRAUMPC</span></h3>
                    <p class="main-promo-text">Mit unserem PC-Konfigurator kannst du deinen persönlichen Gaming- oder
                        Arbeits-PC nach deinen Wünschen zusammenstellen.</p>
                    <a href="#" class="hero-btn main-btn">ZUM KONFIGURATOR</a>
                </div>
            </div>

            <div class="side-promo">
                <span class="side-promo-badge">NEU!</span>
                <h3 class="side-promo-title">RTX 5000 <span>SERIE</span></h3>
                <p class="side-promo-text">Die neuesten Grafikkarten von NVIDIA sind jetzt verfügbar. Sichere dir jetzt
                    die Leistung der nächsten Generation.</p>
                <a href="#" class="buy-btn">JETZT ENTDECKEN</a>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials">
        <div class="container">
            <h2 class="section-title">WAS UNSERE KUNDEN SAGEN</h2>
            <div class="testimonials-grid">
                <div class="testimonial">
                    <p class="testimonial-text">"Mein Gaming PC läuft seit über einem Jahr ohne Probleme. Die Beratung
                        war top und die Konfiguration perfekt auf meine Bedürfnisse zugeschnitten."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image"></div>
                        <div>
                            <div class="testimonial-author-name">Markus S.</div>
                            <div class="testimonial-stars">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <p class="testimonial-text">"Die Lieferung war super schnell und der PC war perfekt verpackt. Alles
                        funktioniert einwandfrei und die Performance ist besser als erwartet."</p>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image"></div>
                        <div>
                            <div class="testimonial-author-name">Laura K.</div>
                            <div class="testimonial-stars">★★★★★</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <p class="testimonial-text">"Der Kundenservice ist erstklassig. Hatte ein kleines Problem und
                        innerhalb von 24 Stunden wurde mir geholfen. Kann MLR nur weiterempfehlen!"</p>
                    <div class="testimonial-author">
                        <div class="testimonial-author-image"></div>
                        <div>
                            <div class="testimonial-author-name">Thomas B.</div>
                            <div class="testimonial-stars">★★★★★</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <div class="newsletter-inner">
                <div class="newsletter-content">
                    <h3 class="newsletter-title">BLEIB AUF DEM <span>LAUFENDEN</span></h3>
                    <p class="newsletter-text">Melde dich für unseren Newsletter an und erhalte exklusive Angebote,
                        Neuigkeiten zu kommenden Produkten und Tech-News.</p>
                </div>
                <form class="newsletter-form">
                    <input type="email" class="newsletter-input" placeholder="Deine E-Mail-Adresse">
                    <button type="submit" class="newsletter-btn">ABONNIEREN</button>
                </form>
            </div>
        </div>
    </section>

    <?php include 'components/footer.html'; ?>

</body>

</html>