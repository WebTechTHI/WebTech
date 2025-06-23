document.addEventListener('DOMContentLoaded', function () {
    const mengeInput = document.getElementById("mengenValue");
    const toggleBtn = document.querySelector('.warenkorbToggle');
    const container = document.querySelector('.warenkorbContainer');
    const closeBtn = document.querySelector('.warenkorbSchliessen');
    const overlay = document.querySelector('.warenkorbOverlay');
    const hinzufuegenBtn = document.querySelector('.buy-btn');
    const headerAnzahl = document.querySelector('.cart-badge');

    // Funktion, um den Warenkorb vom Server zu holen und die Anzeige zu aktualisieren
    function ladeUndZeigeWarenkorb() {
        fetch('/api/getCart.php') 
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success' && data.cart) {
                    aktualisiereWarenkorb(data.cart);
                }
            })
            .catch(err => console.error("Fehler beim Laden des Warenkorbs:", err));
    }

    // Funktion, um die Anzeige basierend auf den Server-Daten zu rendern
    function aktualisiereWarenkorb(warenkorbItems) {
        const inhalt = document.querySelector('.warenkorbInhalt');
        const gesamtEl = document.querySelector('.warenkorbGesamt span:last-child');
        const anzahlEl = document.querySelector('.warenkorbAnzahl');
        const leerText = document.querySelector('.leerNachricht');

        // Zähler aktualisieren
        const gesamtMenge = warenkorbItems.reduce((sum, item) => sum + parseInt(item.quantity), 0);
        if (anzahlEl) anzahlEl.textContent = warenkorbItems.length;
        if (headerAnzahl) headerAnzahl.textContent = warenkorbItems.length;

        inhalt.innerHTML = '';
        if (warenkorbItems.length === 0) {
            if (leerText) inhalt.appendChild(leerText);
            if (gesamtEl) gesamtEl.textContent = '0,00 €';
            return;
        }

        let gesamtPreis = 0;
        warenkorbItems.forEach(artikel => {
            gesamtPreis += parseFloat(artikel.price) * parseInt(artikel.quantity);
            
            const element = document.createElement('div');
            element.className = 'warenkorbArtikel';
            // ACHTUNG: Die ID kommt jetzt aus artikel.product_id, nicht artikel.id
            element.dataset.productId = artikel.product_id; 
            element.innerHTML = `
                <img src="${artikel.image}" alt="${artikel.name}" class="artikelBild">
                <div class="artikelDetails">
                    <div class="artikelTitel">${artikel.name}</div>
                    <div class="artikelPreis">${parseFloat(artikel.price).toFixed(2).replace('.', ',')} €</div>
                    <div class="artikelMenge">
                        <button class="mengenButton menge-minus">-</button>
                        <input type="text" class="mengenEingabe" value="${artikel.quantity}" readonly>
                        <button class="mengenButton menge-plus">+</button>
                    </div>
                    <button class="artikelEntfernen">Entfernen</button>
                </div>
            `;
            inhalt.appendChild(element);
        });

        if (gesamtEl) gesamtEl.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
    }

    // Event Delegation für dynamisch erstellte Buttons
    document.querySelector('.warenkorbInhalt').addEventListener('click', function(event) {
        const target = event.target;
        const artikelElement = target.closest('.warenkorbArtikel');
        if (!artikelElement) return;

        const productId = artikelElement.dataset.productId;

   const mengeInput = artikelElement.querySelector('.mengenEingabe');
    let aktuelleMenge = parseInt(mengeInput.value, 10);

    if (target.classList.contains('menge-plus')) {
        const neueMenge = aktuelleMenge + 1;
        fetch('/api/updateCart.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId, quantity: 1 }) // +1 hinzufügen!
        }).then(() => ladeUndZeigeWarenkorb());
    }

    if (target.classList.contains('menge-minus')) {
        if (aktuelleMenge > 1) {
            const neueMenge = aktuelleMenge - 1;
            fetch('/api/updateCart.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ product_id: productId, quantity: -1 }) // -1 abziehen!
            }).then(() => ladeUndZeigeWarenkorb());
        } else {
            // Optional: gleich entfernen, wenn Menge = 1 -> -1 gedrückt wird
            fetch('/api/removeCartItem.php', {
                method: 'POST', headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({ product_id: productId })
            }).then(() => ladeUndZeigeWarenkorb());
        }
    }

    if (target.classList.contains('artikelEntfernen')) {
        fetch('/api/removeCartItem.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId })
        }).then(() => ladeUndZeigeWarenkorb());
        }
    });

    // Produkt auf der Produktseite hinzufügen
    hinzufuegenBtn?.addEventListener('click', () => {
        const consentCookie = document.cookie.split('; ').find(row => row.startsWith('cookie_consent='));
        const consentGiven = consentCookie ? consentCookie.split('=')[1] === 'accepted' : false;

        if (!consentGiven) {
            const banner = document.getElementById('cookie-consent-banner');
            alert("Für die Funktionalität des Warenkorbes müssen Sie die Cookies akzeptieren.");
            if (banner) banner.style.display = 'block';
            return; 
        }

        const menge = parseInt(mengeInput.value);
        const produktId = hinzufuegenBtn.dataset.id;
        
        // HIER DIE DRITTE KORREKTUR: Verwende die neue, intelligente API
        fetch('/api/updateCart.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: produktId, quantity: menge })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                ladeUndZeigeWarenkorb(); // Warenkorb neu laden, um Anzeige zu aktualisieren
                container.classList.add('warenkorbOffen');
                overlay.style.display = 'block';
            } else {
                console.error('Fehler:', data.message);
            }
        });
    });

    // Initiales Laden des Warenkorbs beim Seitenaufruf
    ladeUndZeigeWarenkorb();

    // Event Listener für die Sidebar (unverändert)
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
    
    document.querySelector('.zurKasseButton')?.addEventListener('click', () => { 
        window.location.href = '/index.php?page=cart';
    });
});


function updateQtyValue(operation) {
    // KORREKTUR: Muss auf die ID 'mengenValue' zugreifen, nicht die Klasse 'mengenEingabe'
    const mengeInput = document.getElementById("mengenValue"); 
    if(!mengeInput) return;

    let currentValue = parseInt(mengeInput.value, 10);
    if (operation === "increase") {
        mengeInput.value = currentValue + 1; // Du musst dem .value-Attribut zuweisen
    }
    if (operation === "decrease" && currentValue > 1) {
        mengeInput.value = currentValue - 1; // Du musst dem .value-Attribut zuweisen
    }
}