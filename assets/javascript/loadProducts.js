async function loadProducts(kategorie) {
    try {
        const response = await fetch('../assets/json/productList.json');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const products = await response.json();
        const container = document.querySelector('.productMainContainer');
        
        // Leere den Container, falls bereits Inhalte vorhanden sind
        container.innerHTML = '';
        
            
        
        products.forEach(product => {
            const istSale = product.sale === true;
            const istZubehoer = product.kategorie === 'zubehör';
        
            let istTreffer = false;
        
            // Wenn Kategorie "angebote", zeige nur Produkte mit sale: true
            if (kategorie === 'angebote') {
                istTreffer = istSale;
            } else {
                istTreffer = product.kategorie === kategorie || product.unterkategorie === kategorie;
            }
        
            if (istTreffer) {
                const productDiv = document.createElement('div');
        
                // Klassen nur bei normalen Kategorien prüfen
                let className = 'product';
                if (kategorie !== 'angebote') {
                    if (istZubehoer) className += ' zubehoer';
                }
                if (istSale) className += ' sale';
                productDiv.className = className;
        
                const productImage = product.bild[0];
        
                // Auch hier: bei "angebote" keine zubehoer-Klasse
                const pictureClass = (kategorie !== 'angebote' && istZubehoer)
                    ? 'productPictureContainer zubehoer'
                    : 'productPictureContainer';
        
                const detailsClass = (kategorie !== 'angebote' && istZubehoer)
                    ? 'productDetails zubehoer'
                    : 'productDetails';
        
                productDiv.innerHTML = `
                    <div class="${pictureClass}">
                        <a href="../${product.seitenlink}?id=${product.id}">
                            <img src="../${productImage}" alt="${product.alt}">
                        </a>
                    </div>
                    <div class="${detailsClass}">
                        <h1>${product.name}</h1>
                        <h2>${product.kurzbeschreibung}</h2>
                        <ul class="productSpecs">
                            ${product.spezifikationen.map(spec => `<li>${spec}</li>`).join('')}
                        </ul>
                        <div>
                            <p class="productPrice"><strong>Preis: </strong>€ ${product.preis},-</p>
                        </div>
                        <div class="buttonContainer">
                            <button class="zumWarenkorb">
                                <img src="../assets/images/icons/shoppingcart.png" alt="Zum Warenkorb hinzufügen">
                            </button>
                            <a class="more" href="../${product.seitenlink}?id=${product.id}">Mehr zum Produkt</a>
                        </div>
                    </div>
                `;
        
                container.appendChild(productDiv);
            }
        });
        
        
               
    } catch (error) {
        console.error('Error loading products:', error);
        const container = document.getElementById('productMainContainer');
        container.innerHTML = '<p>Fehler beim Laden der Produkte.</p>';
    }
}



