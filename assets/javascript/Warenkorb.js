// Warenkorb-Funktionalität
document.addEventListener('DOMContentLoaded', async function () {
  const params = new URLSearchParams(window.location.search);
  const produktId = parseInt(params.get("id"));
  const mengeInput = document.querySelector('.qty-value'); // Angepasst an neue Klasse
  const minusBtn = document.querySelector('.qty-btn'); // Erstes Button-Element
  const plusBtn = document.querySelectorAll('.qty-btn')[1]; // Zweites Button-Element

  if (!produktId) {
    console.error("Keine Produkt-ID in der URL gefunden.");
    return;
  }

  const response = await fetch("../assets/json/productList.json");
  const produkte = await response.json();
  const produkt = produkte.find(p => p.id === produktId);

  if (!produkt) {
    console.error(`Produkt mit ID ${produktId} nicht gefunden.`);
    return;
  }

  const toggleBtn = document.querySelector('.warenkorbToggle');
  const container = document.querySelector('.warenkorbContainer');
  const closeBtn = document.querySelector('.warenkorbSchliessen');
  const overlay = document.querySelector('.warenkorbOverlay');
  const anzahl = document.querySelector('.warenkorbAnzahl');
  const inhalt = document.querySelector('.warenkorbInhalt');
  const gesamt = document.querySelector('.warenkorbGesamt span:last-child');
  const leerText = document.querySelector('.leerNachricht');
  const hinzufuegenBtn = document.querySelector('.buy-btn'); // Angepasst an neue Klasse

  let warenkorb = [];

  if (toggleBtn) {
    toggleBtn.addEventListener('click', () => {
      container.classList.add('warenkorbOffen');
      overlay.style.display = 'block';
    });
  }

  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      container.classList.remove('warenkorbOffen');
      overlay.style.display = 'none';
    });
  }

  if (overlay) {
    overlay.addEventListener('click', () => {
      container.classList.remove('warenkorbOffen');
      overlay.style.display = 'none';
    });
  }

  if (minusBtn) {
    minusBtn.addEventListener('click', function () {
      alterValue('decrease');
    });
  }

  if (plusBtn) {
    plusBtn.addEventListener('click', function () {
      alterValue('increase');
    });
  }

  if (hinzufuegenBtn) {
    hinzufuegenBtn.addEventListener('click', () => {
      const menge = parseInt(mengeInput.value);
      const index = warenkorb.findIndex(e => e.id === produkt.id);

      if (index > -1) {
        warenkorb[index].quantity += menge;
      } else {
        warenkorb.push({
          id: produkt.id,
          name: produkt.name,
          price: parseFloat(produkt.preis),
          quantity: menge,
          image: "../" + produkt.bild[0]
        });
      }

      aktualisiereWarenkorb();
      if (container) {
        container.classList.add('warenkorbOffen');
        overlay.style.display = 'block';
      }
    });
  }

  function aktualisiereWarenkorb() {
    const artikelZahl = warenkorb.reduce((sum, e) => sum + e.quantity, 0);
    if (anzahl) anzahl.textContent = artikelZahl;

    if (inhalt) {
      while (inhalt.firstChild) inhalt.removeChild(inhalt.firstChild);

      if (warenkorb.length === 0) {
        if (leerText) inhalt.appendChild(leerText);
        if (gesamt) gesamt.textContent = '0,00 €';
        return;
      }

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
        inhalt.appendChild(element);

        element.querySelector('.menge-minus').addEventListener('click', function () {
          const index = parseInt(this.dataset.index);
          if (warenkorb[index].quantity > 1) warenkorb[index].quantity--;
          aktualisiereWarenkorb();
        });

        element.querySelector('.menge-plus').addEventListener('click', function () {
          const index = parseInt(this.dataset.index);
          warenkorb[index].quantity++;
          aktualisiereWarenkorb();
        });

        element.querySelector('.artikelEntfernen').addEventListener('click', function () {
          const index = parseInt(this.dataset.index);
          warenkorb.splice(index, 1);
          aktualisiereWarenkorb();
        });
      });

      if (gesamt) gesamt.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
    }
  }

  aktualisiereWarenkorb();
});

// Funktion zum Erhöhen und Verringern der Anzahl
function alterValue(operation) {
  var valueElement = document.querySelector(".qty-value"); // Angepasst an neue Klasse
  
  if (!valueElement) {
    console.error("Quantity input field not found");
    return;
  }
  
  var value = valueElement.value;

  if (operation == 'decrease' && value > 1) {
    valueElement.value = (parseInt(value) - 1);
  }

  if (operation == 'increase' && value < 10) {
    valueElement.value = (parseInt(value) + 1);
  }
}

// Tab-Funktionalität hinzufügen
document.addEventListener('DOMContentLoaded', function() {
  const tabItems = document.querySelectorAll('.tab-item');
  const tabContent = document.querySelector('.tab-content');
  
  tabItems.forEach((tab, index) => {
    tab.addEventListener('click', function() {
      // Alle Tabs deaktivieren
      tabItems.forEach(t => t.classList.remove('active'));
      // Aktuellen Tab aktivieren
      this.classList.add('active');
      
      // Tab-Content entsprechend ändern (vereinfacht)
      if (index === 1) { // Technische Daten Tab
        const specsTable = document.querySelector('.specs-table-container');
        if (specsTable) {
          specsTable.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  });
});