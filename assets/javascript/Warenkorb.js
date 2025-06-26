document.addEventListener('DOMContentLoaded', function () {
    // Elemente initialisieren (holen von ProductView auf Produktseite nicht sidebar Warenkorb)
    const mengeInput = document.getElementById("mengenValue");
    const toggleBtn = document.querySelector('.warenkorbToggle');
    const container = document.querySelector('.warenkorbContainer');
    const closeBtn = document.querySelector('.warenkorbSchliessen');
    const overlay = document.querySelector('.warenkorbOverlay');
    const hinzufuegenBtn = document.querySelector('.buy-btn');
    const headerAnzahl = document.querySelector('.cart-badge');

    // direkt beim Laden der Seite den Warenkorb vom Server laden
    // und die Anzeige aktualisieren
    ladeUndZeigeWarenkorb();

    // Funktion, um den Warenkorb vom Server zu holen und die Anzeige zu aktualisieren mit der Funktion ladeUndZeigeWarenkorb
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

        // Elemente für die Anzeige holen aus ProductView Sidebar(DOM Elemente)
        const inhalt = document.querySelector('.warenkorbInhalt');
        const gesamtEl = document.querySelector('.warenkorbGesamt span:last-child');
        const anzahlEl = document.querySelector('.warenkorbAnzahl');
        const leerText = document.querySelector('.leerNachricht');

        // Zähler aktualisieren         Lauf durch alle Warenkorb-Artikel und rechne die quantity-Werte zusammen. Fang bei 0 an
        const gesamtMenge = warenkorbItems.reduce((sum, item) => sum + parseInt(item.quantity), 0);

        //Hier wird die Anzahl der Artikel im SideWarenkorb und Warenkorb im header aktualisiert
        if (anzahlEl) anzahlEl.textContent = warenkorbItems.length;
        if (headerAnzahl) headerAnzahl.textContent = warenkorbItems.length;

        // Warenkorb-Inhalt aktualisieren
        inhalt.innerHTML = '';
        // Wenn der Warenkorb leer ist, zeige eine Nachricht an
        if (warenkorbItems.length === 0) {
            if (leerText) inhalt.appendChild(leerText);
            if (gesamtEl) gesamtEl.textContent = '0,00 €';
            return;
        }

        // Ansonsten Warenkorb-Artikel anzeigen
        let gesamtPreis = 0;

        // Hier wird der Warenkorb mit den Artikeln gefüllt (jeder artikel einzelnd)
        warenkorbItems.forEach(artikel => {

            // Hier wird der Gesamtpreis berechnet indem Preis jedes Artikels mit der Menge multipliziert
            gesamtPreis += parseFloat(artikel.price) * parseInt(artikel.quantity);

            // Hier wird der Artikel in den Warenkorb eingefügt
            const element = document.createElement('div');
            element.className = 'warenkorbArtikel';
            element.dataset.productId = artikel.product_id; 


            //Artikel darstellen in Sidebar
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
            // hier wird das erstelle Element in den Warenkorb eingefügt, sollte es weitere Artikel geben
            // wird es an das Ende des Warenkorbs angehängt
            inhalt.appendChild(element);
        });
        // Hier wird der Gesamtpreis im Warenkorb aktualisiert
        if (gesamtEl) gesamtEl.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
    }

    // Event Listener für die Warenkorb-Interaktionen
    // Hier wird der Warenkorb aktualisiert, wenn auf die Menge +/- oder Entfernen geklickt wird
    document.querySelector('.warenkorbInhalt').addEventListener('click', function(event) {

        const target = event.target;
        const artikelElement = target.closest('.warenkorbArtikel');

        if (!artikelElement) return;

        const productId = artikelElement.dataset.productId;

        // Hier wird die Menge des Artikels aktualisiert, wenn auf die Menge +/- oder Entfernen geklickt wird
        const mengeInput = artikelElement.querySelector('.mengenEingabe');
        let aktuelleMenge = parseInt(mengeInput.value, 10);



            //1. Fall wenn + gedrückt wird
    //wenn die Menge geändert wird (in dem Fall auf + geklickt wird), dann wird die api aufgerufen 
    if (target.classList.contains('menge-plus')) {
        const neueMenge = aktuelleMenge + 1;
        fetch('/api/updateCart.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId, quantity: 1 }) // +1 hinzufügen!
        }).then(() => ladeUndZeigeWarenkorb());
    }



            //2. Fall wenn - gedrückt wird
//wenn die Menge geändert wird (in dem Fall auf - geklickt wird), dann wird die api aufgerufen 
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




            //3. Fall wenn entfernen gedrückt wird
    // Hier wird der Artikel entfernt, wenn auf Entfernen geklickt wird
    // wenn auf Entfernen geklickt wird, dann wird die api aufgerufen
    if (target.classList.contains('artikelEntfernen')) {
        fetch('/api/removeCartItem.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: productId })
        }).then(() => ladeUndZeigeWarenkorb());
        }
    });



    // Wenn man auf den Zum Warenkorb-Button klickt, wird die Menge aus dem Input-Feld genommen
    // und die API aufgerufen, um den Artikel zum Warenkorb hinzuzufügen
    hinzufuegenBtn?.addEventListener('click', () => {

        // Cookie-Zustimmung prüfen 
        const consentCookie = document.cookie.split('; ').find(row => row.startsWith('cookie_consent='));
        const consentGiven = consentCookie ? consentCookie.split('=')[1] === 'accepted' : false;

        // wenn kein Cookie-Zustimmung gegeben wurde, dann wird eine Nachricht angezeigt und der Banner wird angezeigt
        // und die Funktion wird abgebrochen, damit der Warenkorb nicht aktualisiert wird
        if (!consentGiven) {
            const banner = document.getElementById('cookie-consent-banner');
            alert("Für die Funktionalität des Warenkorbes müssen Sie die Cookies akzeptieren.");
            if (banner) banner.style.display = 'block';
            return; 
        }



        // Hier wird die Menge aus dem Input-Feld genommen und die API aufgerufen, um den Artikel zum Warenkorb hinzuzufügen
        const menge = parseInt(mengeInput.value);
        const produktId = hinzufuegenBtn.dataset.id;
        
        // Hier findet die API-Interaktion statt, um den Artikel zum Warenkorb hinzuzufügen, wir übergeben die Produkt-ID und die Menge aus dem Input-Feld
        fetch('/api/updateCart.php', {
            method: 'POST', headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({ product_id: produktId, quantity: menge })
        })

        // Hier wird die Antwort der API verarbeitet
        // und der Warenkorb neu geladen, um die Anzeige zu aktualisieren
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                ladeUndZeigeWarenkorb(); // Warenkorb neu laden, um Anzeige zu aktualisieren
                container.classList.add('warenkorbOffen');
                // Hier wird das Overlay angezeigt, wenn der Warenkorb aktualisiert wurde
                overlay.style.display = 'block';
            } else {
                console.error('Fehler:', data.message);
            }
        });
    });

    

    // Event Listener für die Sidebar 

    // Hier wird der Warenkorb geöffnet, wenn auf den Toggle-Button geklickt wird
    // und das Overlay wird angezeigt, wenn der Warenkorb geöffnet wird
toggleBtn?.addEventListener('click', () => {
    container.classList.add('warenkorbOffen');
    overlay.style.display = 'block';
});

// Hier wird der Warenkorb geschlossen, wenn auf den Schließen-Button oder das Overlay geklickt wird
closeBtn?.addEventListener('click', () => {
    container.classList.remove('warenkorbOffen');
    overlay.style.display = 'none';
});

// Hier wird der Warenkorb geschlossen, wenn auf das Overlay geklickt wird
overlay?.addEventListener('click', () => {
    container.classList.remove('warenkorbOffen');
    overlay.style.display = 'none';
});
   
    document.querySelector('.zurKasseButton')?.addEventListener('click', () => { 
        window.location.href = '/index.php?page=cart';
    });
});

//kleine Hilfsfunktion, um die Menge im Input-Feld zu aktualisieren
// Diese Funktion wird aufgerufen, wenn die Menge im Input-Feld geändert wird (Funktion wird aufgerufen von ProductView)
function updateQtyValue(operation) {
    const mengeInput = document.getElementById("mengenValue"); 
    if(!mengeInput) return;

    let currentValue = parseInt(mengeInput.value, 10);
    if (operation === "increase") {
        mengeInput.value = currentValue + 1; 
    }
    if (operation === "decrease" && currentValue > 1) {
        mengeInput.value = currentValue - 1; 
    }
}