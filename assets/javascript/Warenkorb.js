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

    let warenkorb = ladeWarenkorbAusLocalStorage();
    aktualisiereWarenkorb();

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

    // ✅ Produkt einzeln hinzufügen (sofort in DB)
    hinzufuegenBtn?.addEventListener('click', () => {
        if (!window.USER_ID || window.USER_ID === null || window.USER_ID === "null") {
            window.location.href = '/index.php?page=login';
            return;
        }

        const menge = parseInt(mengeInput.value);
        const produkt = {
            id: hinzufuegenBtn.dataset.id,
            name: hinzufuegenBtn.dataset.name,
            price: parseFloat(hinzufuegenBtn.dataset.price),
            image: hinzufuegenBtn.dataset.image
        };

        const index = warenkorb.findIndex(e => e.id === produkt.id);
        if (index > -1) {
            warenkorb[index].quantity += menge;
        } else {
            warenkorb.push({ ...produkt, quantity: menge });
        }

        aktualisiereWarenkorb();
        speichereWarenkorbInLocalStorage(warenkorb);

        // einzelnes Produkt sofort speichern
        fetch('/api/addToCart.php', {
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
                console.log('Produkt in DB gespeichert');
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

    // ✅ GANZEN LocalStorage-Warenkorb speichern & weiterleiten
    zurKasseButton?.addEventListener('click', () => {
        if (!window.USER_ID || window.USER_ID === null || window.USER_ID === "null") {
            window.location.href = '/index.php?page=login';
            return;
        }

        // localStorage Warenkorb laden
        const warenkorb = ladeWarenkorbAusLocalStorage();

        fetch('/api/addToCart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ items: warenkorb })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                // Danach weiterleiten zur Cart-Seite
                window.location.href = '/index.php?page=cart';
            } else {
                console.error('Fehler:', data.message);
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
                speichereWarenkorbInLocalStorage(warenkorb);
            });

            element.querySelector('.menge-plus').addEventListener('click', () => {
                warenkorb[i].quantity++;
                aktualisiereWarenkorb();
                speichereWarenkorbInLocalStorage(warenkorb);
            });

            element.querySelector('.artikelEntfernen').addEventListener('click', () => {
                warenkorb.splice(i, 1);
                aktualisiereWarenkorb();
                speichereWarenkorbInLocalStorage(warenkorb);
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

function ladeWarenkorbAusLocalStorage() {
    const data = localStorage.getItem('warenkorb');
    return data ? JSON.parse(data) : [];
}

function speichereWarenkorbInLocalStorage(warenkorb) {
    localStorage.setItem('warenkorb', JSON.stringify(warenkorb));
}
