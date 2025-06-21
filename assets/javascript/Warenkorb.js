document.addEventListener('DOMContentLoaded', function () {
    const mengeInput = document.getElementById("mengenValue");
    const toggleBtn = document.querySelector('.warenkorbToggle');
    const container = document.querySelector('.warenkorbContainer');
    const closeBtn = document.querySelector('.warenkorbSchliessen');
    const overlay = document.querySelector('.warenkorbOverlay');
    const anzahl = document.querySelector('.warenkorbAnzahl');
    const inhalt = document.querySelector('.warenkorbInhalt');
    const gesamt = document.querySelector('.warenkorbGesamt span:last-child');
    const leerText = document.querySelector('.leerNachricht');
    const hinzufuegenBtn = document.querySelector('.buy-btn');
    const zumWarenkorbBtn = document.querySelector('.zurKasseButton');

    let warenkorb = [];

    // ✅ Session-Warenkorb vom Server laden (jetzt mit Produktdetails)
fetch('/api/getCartCookie.php') // <-- Geändert
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            warenkorb = data.cart || [];
            aktualisiereWarenkorb();
        }
    })

    // Öffnen/Schließen der Seitenleiste
    toggleBtn?.addEventListener('click', () => {
        container.classList.add('warenkorbOffen');
        overlay.style.display = 'block';
    });

    closeBtn?.addEventListener('click', () => {
        container.classList.remove('warenkorbOffen');
        overlay.style.display = 'none';
    });

    overlay?.addEventListener('click', () => {
        container.classList.remove('warenkorbOffen');
        overlay.style.display = 'none';
    });

    // Produkt hinzufügen
    hinzufuegenBtn?.addEventListener('click', () => {
        const menge = parseInt(mengeInput.value);
        const produkt = {
            id: hinzufuegenBtn.dataset.id,
            name: hinzufuegenBtn.dataset.name,
            price: parseFloat(hinzufuegenBtn.dataset.price),
            image: hinzufuegenBtn.dataset.image
        };

        // Sofort im Client aktualisieren
        const index = warenkorb.findIndex(e => e.id === produkt.id);
        if (index > -1) {
            warenkorb[index].quantity += menge;
        } else {
            warenkorb.push({ ...produkt, quantity: menge });
        }

        aktualisiereWarenkorb();

        // Session updaten
      fetch('/api/addToCartCookie.php', { // <-- Geändert
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            product_id: produkt.id,
            quantity: menge
        })
    })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                console.log('Produkt in Cookie gespeichert');
            } else {
                console.error('Fehler:', data.message);
            }
        })
        .catch(err => {
            console.error('API Fehler:', err);
        });

        container.classList.add('warenkorbOffen');
        overlay.style.display = 'block';
    });

    zumWarenkorbBtn?.addEventListener('click', () => { 
        window.location.href = '/index.php?page=cart';
    });

    function aktualisiereWarenkorb() {
         if (anzahl) anzahl.textContent = warenkorb.length;

         const headerBadge = document.querySelector('.cart-badge');
        if (headerBadge) headerBadge.textContent = warenkorb.length;

        inhalt.innerHTML = '';
        if (warenkorb.length === 0) {
            inhalt.appendChild(leerText);
            gesamt.textContent = '0,00 €';
            return;
        }

        let gesamtPreis = 0;

        warenkorb.forEach((artikel, i) => {
            gesamtPreis += artikel.price * artikel.quantity;

            const element = document.createElement('div');
            element.className = 'warenkorbArtikel';
            element.innerHTML = `
                <img src="${artikel.image}" alt="${artikel.name}" class="artikelBild">
                <div class="artikelDetails">
                    <div class="artikelTitel">${artikel.name}</div>
                    <div class="artikelPreis">${artikel.price.toFixed(2).replace('.', ',')} €</div>
                    <div class="artikelMenge">
                        <button class="mengenButton menge-minus" data-index="${i}">-</button>
                        <input type="text" class="mengenEingabe" value="${artikel.quantity}" readonly>
                        <button class="mengenButton menge-plus" data-index="${i}">+</button>
                    </div>
                    <button class="artikelEntfernen" data-index="${i}">Entfernen</button>
                </div>
            `;
            inhalt.appendChild(element);

            element.querySelector('.menge-minus').addEventListener('click', () => {
                if (warenkorb[i].quantity > 1) {
                    warenkorb[i].quantity--;
                    aktualisiereWarenkorb();

                    // Session updaten
                    fetch('/api/addToCartCookie.php', { // <-- Geändert
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        product_id: artikel.id,
                        quantity: -1 // Sende -1 zum Reduzieren
                    })
                });
                }
            });

            element.querySelector('.menge-plus').addEventListener('click', () => {
                warenkorb[i].quantity++;
                aktualisiereWarenkorb();

               fetch('/api/addToCartCookie.php', { // <-- Geändert
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    product_id: artikel.id,
                    quantity: 1 // Sende +1 zum Erhöhen
                })
            });
            });

            element.querySelector('.artikelEntfernen').addEventListener('click', () => {
                warenkorb.splice(i, 1);
                aktualisiereWarenkorb();

               fetch('/api/removeFromCartCookie.php', { // <-- Geändert
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({
                    product_id: artikel.id
                })
                });
            });
        });

        gesamt.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
    }
});

function updateQtyValue(operation) {
    let menge = document.getElementById("mengenValue");
    if (operation == "increase") {
        menge.value++;
    }
    if (operation == "decrease" && menge.value > 1) {
        menge.value--;
    }
}