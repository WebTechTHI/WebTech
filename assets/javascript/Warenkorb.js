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

    // Initialisiere den Warenkorb basierend auf dem Benutzerstatus
    if (window.USER_ID && window.USER_ID !== null && window.USER_ID !== "null" && window.USER_ID !== 0 && window.USER_ID !== "0") {
        aktualisiereWarenkorbVomServer(); // Lädt vom Server, speichert in localStorage und aktualisiert UI
    } else {
        warenkorb = ladeWarenkorbAusLocalStorage(); // Für Gäste aus localStorage laden
        aktualisiereWarenkorb(); // UI für Gäste aktualisieren
    }

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

    // Produkt einzeln hinzufügen
    hinzufuegenBtn?.addEventListener('click', () => {
        // Login check removed - guests can add to cart (session cart)

        const menge = parseInt(mengeInput?.value || '1'); // Default to 1 if mengeInput is not found or value is invalid
        const produktId = hinzufuegenBtn.dataset.id;
        const produktName = hinzufuegenBtn.dataset.name;
        const produktPrice = parseFloat(hinzufuegenBtn.dataset.price);
        const produktImage = hinzufuegenBtn.dataset.image;

        if (!produktId || !produktName || isNaN(produktPrice) || !produktImage) {
            console.error("Produkt-Daten unvollständig oder fehlerhaft:", hinzufuegenBtn.dataset);
            alert("Fehler: Produktdaten konnten nicht geladen werden.");
            return;
        }

        const produkt = {
            id: produktId, // Ensure 'id' is used consistently
            name: produktName,
            price: produktPrice,
            image: produktImage
        };

        const index = warenkorb.findIndex(e => e.id === produkt.id);
        if (index > -1) {
            warenkorb[index].quantity += menge;
        } else {
            warenkorb.push({ ...produkt, quantity: menge });
        }

        aktualisiereWarenkorb();
        speichereWarenkorbInLocalStorage(warenkorb); // Update localStorage for guests' immediate UI consistency

        // Produkt an Backend senden (speichert in DB für User, in Session für Gäste)
        const payload = {
            product_id: produkt.id, // Original key, backend might still use it for DB operations
            id: produkt.id,         // For guest session cart consistency (id, name, price, image, quantity)
            name: produkt.name,
            price: produkt.price,
            image: produkt.image,
            quantity: menge
        };

        fetch('/api/addToCart.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(payload)
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

    // GANZEN LocalStorage-Warenkorb mit Backend synchronisieren & weiterleiten zur Cart-Seite
    zurKasseButton?.addEventListener('click', () => {
        // Login check removed. Guests will have their localStorage cart synced to their session cart.
        // Logged-in users will have their localStorage cart merged with their DB cart.

        const currentCartData = ladeWarenkorbAusLocalStorage();

        // Ensure there's something to sync, though an empty array is fine for the API.
        // if (currentCartData.length === 0) {
        //     // If cart is empty, maybe just redirect? Or let API handle empty array.
        //     // For now, we'll always sync, an empty cart sync might clear the session/db cart if not handled carefully by API.
        //     // The current api/addToCart.php with `items` array expects to set quantities,
        //     // so sending an empty items array might not do anything or might be interpreted as "clear cart"
        //     // if the backend logic for items:[] is to replace the cart.
        //     // Given current backend: `foreach ($data['items'] as $item)` - an empty array will simply do nothing.
        // }

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

// Globale Variable warenkorb, damit sie von aktualisiereWarenkorbVomServer aktualisiert werden kann
let warenkorb = [];

async function aktualisiereWarenkorbVomServer() {
    try {
        const response = await fetch('/api/getCart.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();

        if (data.status === 'success' && Array.isArray(data.items)) {
            warenkorb = data.items.map(item => ({
                ...item,
                // Ensure quantity is a number, price is a number.
                // getCart.php for logged-in users already casts price to float.
                // getCart.php for guests also casts id, price, quantity.
                // This is an additional safety net.
                id: String(item.id), // Ensure ID is string, matching dataset and localStorage expectations
                quantity: parseInt(item.quantity, 10),
                price: parseFloat(item.price)
            }));
            speichereWarenkorbInLocalStorage(warenkorb);
        } else {
            console.error('Failed to fetch cart from server or invalid format:', data.message);
            // Fallback or error handling: If server fails, maybe load from local storage?
            // For now, if server call fails for logged-in user, they might see an empty/stale cart.
            // Consider loading from localStorage as a fallback:
            // warenkorb = ladeWarenkorbAusLocalStorage();
        }
    } catch (error) {
        console.error('Error fetching cart from server:', error);
        // Fallback for network errors etc.
        // warenkorb = ladeWarenkorbAusLocalStorage();
    }
    aktualisiereWarenkorb(); // Update UI in all cases (success or error, to show empty or fallback)
}
