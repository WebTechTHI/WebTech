async function loadProducts(kategorie) {
    try {
        const response = await fetch('assets/json/productList.json');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const products = await response.json();
        const container = document.querySelector('.products-grid');

        if (!container) {
            console.error('Kein Container ".products-grid" gefunden.');
            return;
        }

        container.innerHTML = ''; // Alte Inhalte leeren


        products.forEach(product => {
            let istTreffer = false;

            if (kategorie === 'angebote') {
                istTreffer = product.sale === true;
            } else {
                istTreffer = product.kategorie === kategorie || product.unterkategorie === kategorie;
            }

            if (istTreffer) {
                const preis = product.preis;
                let preisMitKomma = preis.toFixed(2).replace('.', ',');
                if (preisMitKomma.endsWith(',00')) {
                    preisMitKomma = preisMitKomma.slice(0, -3) + ',-';
                }

                const div = document.createElement('div');
                div.className = 'product';
                div.innerHTML = `
                    <span class="product-badge">${product.sale ? 'SALE' : 'TOP'}</span>
                    <div class="product-image">
                        <img src="${product.bild[0]}" alt="${product.alt}">
                    </div>
                    <div class="product-details">
                        <h4 class="product-title">${product.name}</h4>
                        <ul class="product-specs">
                            ${product.spezifikationen.map(spec => `<li>${spec}</li>`).join('')}
                        </ul>
                        <div class="price"><span class="price-prefix">€</span>${preisMitKomma}</div>
                        <div class="financing">Jetzt mit 0% Finanzierung</div>
                        <a href="${product.seitenlink}?id=${product.id}" class="buy-btn">Jetzt konfigurieren</a>
                    </div>
                `;

                container.appendChild(div);
            }
        });

    } catch (error) {
        console.error('Fehler beim Laden der Produkte:', error);
    }
}
