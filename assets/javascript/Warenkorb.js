// Warenkorb-Funktionalität (ursprünglich aus productInfo.js)
// Stellt sicher, dass das DOM vollständig geladen ist, bevor auf Elemente zugegriffen wird.
// Passt auf, dass diese Logik global oder über Events aufgerufen wird, wenn sie produktseiten-spezifische Elemente direkt anspricht.
// Gegebenenfalls muss die Initialisierung (produktId, etc.) angepasst werden, wenn diese Datei global eingebunden wird.

let warenkorb = JSON.parse(localStorage.getItem('warenkorb')) || [];

// Funktion, die von den Produktseiten aufgerufen wird, um einen Artikel hinzuzufügen
// Diese Funktion muss global verfügbar sein, wenn sie von Inline-onclick-Attributen aufgerufen wird.
function addToCart(produktId, produktName, produktPreis, produktBild, menge = 1) {
    const produkt = {
        id: parseInt(produktId),
        name: produktName,
        price: parseFloat(produktPreis),
        image: produktBild, // Pfad zum Bild, relativ zum Root oder absolute URL
    };
    menge = parseInt(menge);

    const index = warenkorb.findIndex(e => e.id === produkt.id);

    if (index > -1) {
        warenkorb[index].quantity += menge;
    } else {
        warenkorb.push({ ...produkt, quantity: menge });
    }
    localStorage.setItem('warenkorb', JSON.stringify(warenkorb));
    aktualisiereWarenkorbAnzeige(); // Universal function to update cart display
    
    // Optional: Warenkorb-Sidebar öffnen, wenn ein Artikel hinzugefügt wird
    const container = document.querySelector('.warenkorbContainer');
    const overlay = document.querySelector('.warenkorbOverlay');
    if (container && overlay) {
        container.classList.add('warenkorbOffen');
        overlay.style.display = 'block';
    }
}


// Diese Funktion aktualisiert die Anzeige des Warenkorbs (z.B. Sidebar)
function aktualisiereWarenkorbAnzeige() {
    const anzahlElement = document.querySelector('.warenkorbAnzahl');
    const inhaltElement = document.querySelector('.warenkorbInhalt');
    const gesamtElement = document.querySelector('.warenkorbGesamt span:last-child');
    const leerTextElement = document.querySelector('.leerNachricht'); // Muss im HTML existieren

    const artikelZahl = warenkorb.reduce((sum, e) => sum + e.quantity, 0);
    if (anzahlElement) anzahlElement.textContent = artikelZahl;

    if (inhaltElement) {
        inhaltElement.innerHTML = ''; // Alte Inhalte leeren

        if (warenkorb.length === 0) {
            if (leerTextElement) {
                 inhaltElement.appendChild(leerTextElement); // Stellt sicher, dass die Nachricht wieder angezeigt wird
                 leerTextElement.style.display = 'block'; 
            } else {
                 // Fallback, falls .leerNachricht nicht existiert
                 inhaltElement.innerHTML = '<div class="leerNachricht">Ihr Warenkorb ist leer.</div>';
            }
            if (gesamtElement) gesamtElement.textContent = '0,00 €';
            return;
        }
        
        if (leerTextElement) leerTextElement.style.display = 'none'; // Nachricht ausblenden, wenn Artikel vorhanden

        let gesamtPreis = 0;

        warenkorb.forEach((artikel, i) => {
            const einzelpreis = artikel.price;
            gesamtPreis += einzelpreis * artikel.quantity;

            const element = document.createElement('div');
            element.className = 'warenkorbArtikel';
            element.innerHTML = `
              <img src="${artikel.image}" alt="${artikel.name}" class="artikelBild">
              <div class="artikelDetails">
                <div class="artikelTitel">${artikel.name}</div>
                <div class="artikelPreis">${einzelpreis.toFixed(2).replace('.', ',')} €</div>
                <div class="artikelMenge">
                  <button class="mengenButton menge-minus" data-index="${i}">-</button>
                  <input type="text" class="mengenEingabe" value="${artikel.quantity}" readonly>
                  <button class="mengenButton menge-plus" data-index="${i}">+</button>
                </div>
                <button class="artikelEntfernen" data-index="${i}">Entfernen</button>
              </div>
            `;
            inhaltElement.appendChild(element);

            element.querySelector('.menge-minus').addEventListener('click', function () {
              const index = parseInt(this.dataset.index);
              if (warenkorb[index].quantity > 1) {
                warenkorb[index].quantity--;
              } else {
                // Optional: Artikel entfernen, wenn Menge auf 0 reduziert wird
                // warenkorb.splice(index, 1); 
              }
              localStorage.setItem('warenkorb', JSON.stringify(warenkorb));
              aktualisiereWarenkorbAnzeige();
            });

            element.querySelector('.menge-plus').addEventListener('click', function () {
              const index = parseInt(this.dataset.index);
              warenkorb[index].quantity++;
              localStorage.setItem('warenkorb', JSON.stringify(warenkorb));
              aktualisiereWarenkorbAnzeige();
            });

            element.querySelector('.artikelEntfernen').addEventListener('click', function () {
              const index = parseInt(this.dataset.index);
              warenkorb.splice(index, 1);
              localStorage.setItem('warenkorb', JSON.stringify(warenkorb));
              aktualisiereWarenkorbAnzeige();
            });
        });

        if (gesamtElement) gesamtElement.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
    }
}

// Event Listener für das Öffnen und Schließen des Warenkorb-Sidebars
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.querySelector('.warenkorbToggle');
    const container = document.querySelector('.warenkorbContainer');
    const closeBtn = document.querySelector('.warenkorbSchliessen');
    const overlay = document.querySelector('.warenkorbOverlay');
    const zurKasseBtn = document.querySelector('.zurKasseButton');


    if (toggleBtn) {
        toggleBtn.addEventListener('click', () => {
            if (container) container.classList.add('warenkorbOffen');
            if (overlay) overlay.style.display = 'block';
            aktualisiereWarenkorbAnzeige(); // Warenkorb aktualisieren, wenn er geöffnet wird
        });
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            if (container) container.classList.remove('warenkorbOffen');
            if (overlay) overlay.style.display = 'none';
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            if (container) container.classList.remove('warenkorbOffen');
            if (overlay) overlay.style.display = 'none';
        });
    }
    
    if (zurKasseBtn) {
        zurKasseBtn.addEventListener('click', () => {
            // Weiterleitung zur Kassenseite oder Implementierung der Kassenlogik
            window.location.href = '../pages/shoppingBasket.php'; // Beispiel für Weiterleitung
        });
    }


    // Initialen Warenkorb anzeigen, falls schon Artikel drin sind (z.B. aus localStorage)
    aktualisiereWarenkorbAnzeige();
});
