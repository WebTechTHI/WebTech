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
    const zurKasseButton = document.querySelector('.zurKasseButton');

    let warenkorb = [];

    // ✅ NEU: Session-Warenkorb vom Server laden statt LocalStorage
    fetch('/api/getCartSession.php')
        .then(res => res.json())
        .then(data => {
            warenkorb = [];
            for (const pid in data.cart) {
                warenkorb.push({
                    id: pid,
                    quantity: data.cart[pid],
                    // Optional: ohne Name/Preis/Image, wenn du das brauchst => hier befüllen
                    name: '', 
                    price: 0,
                    image: ''
                });
            }
            aktualisiereWarenkorb();
        });

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

    // Produkt hinzufügen ➜ NEU: nur Session verwenden!
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

        // Sofort ins PHP Session schreiben
        fetch('/api/addToCartSession.php', {
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
                console.log('Produkt in Session gespeichert');
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

    // Checkout ➜ NEU: nur Session-Check & Weiterleitung
    zurKasseButton?.addEventListener('click', () => {
        fetch('/api/checkout.php')
            .then(res => res.json())
            .then(data => {
                if (data.status === 'not_logged_in') {
                    window.location.href = '/index.php?page=login';
                } else {
                    window.location.href = '/index.php?page=payment';
                }
            })
            .catch(err => {
                console.error('API Fehler:', err);
            });
    });

    function aktualisiereWarenkorb() {
        const artikelZahl = warenkorb.reduce((sum, e) => sum + e.quantity, 0);
        if (anzahl) anzahl.textContent = artikelZahl;

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
                if (warenkorb[i].quantity > 1) warenkorb[i].quantity--;

                aktualisiereWarenkorb();

                // SOFORT Session updaten
                fetch('/api/addToCartSession.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        product_id: artikel.id,
                        quantity: -1 // Minus-Logik, du kannst auch eigene updateCartSession.php machen
                    })
                });
            });

            element.querySelector('.menge-plus').addEventListener('click', () => {
                warenkorb[i].quantity++;
                aktualisiereWarenkorb();

                fetch('/api/addToCartSession.php', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({
                        product_id: artikel.id,
                        quantity: 1
                    })
                });
            });

            element.querySelector('.artikelEntfernen').addEventListener('click', () => {
                warenkorb.splice(i, 1);
                aktualisiereWarenkorb();

                fetch('/api/removeFromCartSession.php', {
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
