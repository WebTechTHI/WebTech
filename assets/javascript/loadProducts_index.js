async function loadProducts() {
    try {
        const response = await fetch('assets/json/productList.json');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        const products = await response.json();
        const container = document.querySelector('.productMainContainer');
        
        // Leere den Container, falls bereits Inhalte vorhanden sind
        container.innerHTML = '';

        products.forEach(product => {
            const productDiv = document.createElement('div');
            if (product.sale === true) {
            productDiv.className = 'product sale';
            }else{
            productDiv.className = 'product';
            }

            // Greife auf das erste Bild im Array zu
            const productImage = product.bild[0]; 

            productDiv.innerHTML = `
                <div class="productPictureContainer">
                    <a href="${product.seitenlink}?id=${product.id}">
                        <img src="${productImage}" alt="${product.alt}">
                    </a>
                </div>
                <div class="productDetails">
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
                            <img src="assets/images/icons/shoppingcart.png" alt="Zum Warenkorb hinzufügen">
                        </button>
                        <a class="more" href="${product.seitenlink}?id=${product.id}">Mehr zum Produkt</a>
                    </div>
                </div>
            `;
            
            container.appendChild(productDiv);
        });
    } catch (error) {
        console.error('Error loading products:', error);
        const container = document.getElementById('productMainContainer');
        container.innerHTML = '<p>Fehler beim Laden der Produkte.</p>';
    }
}

// Load products when the page loads
window.onload = loadProducts;
