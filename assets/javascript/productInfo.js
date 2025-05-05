  // Produktdaten laden und anzeigen
  document.addEventListener("DOMContentLoaded", async () => {
    const params = new URLSearchParams(window.location.search);
    const produktId = parseInt(params.get("id"));

    if (!produktId) {
      console.error("Keine Produkt-ID in der URL gefunden (z. B. ?id=1)");
      return;
    }

    try {
      const response = await fetch("../assets/json/productList.json");
      const produkte = await response.json();

      const produkt = produkte.find(p => p.id === produktId);
      if (!produkt) {
        console.error(`Kein Produkt mit ID ${produktId} gefunden.`);
        return;
      }

      // Seite mit Produktdaten füllen
      document.title = `MLR | ${produkt.name}`;
      document.querySelectorAll(".produktName").forEach(el => el.textContent = produkt.name);
      document.querySelector(".kurzbeschreibung").textContent = produkt.kurzbeschreibung;
      document.querySelector(".beschreibung").textContent = produkt.beschreibung;
      document.querySelector(".topPrice").textContent = `€ ${produkt.preis},-`;
      document.querySelector(".kaufpreis").innerHTML = `<strong>Preis: </strong> € ${produkt.preis}`;

      // Bilder einfügen
      const mainImage = document.querySelector(".mainImage");
const thumbnailContainer = document.querySelector(".thumbnailContainer");

// Sicherstellen, dass Container existieren
if (!mainImage) {
console.error("mainImage nicht gefunden.");
return;
}

// Hauptbild initial setzen
mainImage.src = "../" + produkt.bild[0];
mainImage.alt = `${produkt.name} Hauptbild`;

// Thumbnails leeren und neu füllen
thumbnailContainer.innerHTML = "";

produkt.bild.forEach((src, index) => {
const thumb = document.createElement("img");
thumb.src = "../" + src;
thumb.alt = `${produkt.name} Thumbnail ${index + 1}`;
thumb.classList.add("productImage", "thumbnail");
if (index === 0) thumb.classList.add("active");

// Beim Klick wird das Hauptbild gewechselt
thumb.addEventListener("click", () => {
  document.querySelectorAll(".thumbnail").forEach(t => t.classList.remove("active"));
  thumb.classList.add("active");
  mainImage.src = "../" + src;
});

thumbnailContainer.appendChild(thumb);
});

      // Technische Details einfügen
      const technikTabelle = document.querySelector(".technicalDetails");
      technikTabelle.innerHTML = "";
      produkt.technischeDetails.forEach(detail => {
        const [key, value] = Object.entries(detail)[0];
        const tr = document.createElement("tr");
        tr.innerHTML = `<td><strong>${key}:</strong></td><td>${value}</td>`;
        technikTabelle.appendChild(tr);
      });

    } catch (err) {
      console.error("Fehler beim Laden der Produktdaten:", err);
    }
  });



    // Warenkorb-Funktionalität
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.querySelector('.warenkorbToggle');
        const container = document.querySelector('.warenkorbContainer');
        const closeBtn = document.querySelector('.warenkorbSchliessen');
        const overlay = document.querySelector('.warenkorbOverlay');
        const anzahl = document.querySelector('.warenkorbAnzahl');
        const inhalt = document.querySelector('.warenkorbInhalt');
        const gesamt = document.querySelector('.warenkorbGesamt span:last-child');
        const leerText = document.querySelector('.leerNachricht');
        const hinzufuegenBtn = document.querySelector('.warenkorbButton');
        const mengeInput = document.querySelector('.anzahl');
        const minusBtn = document.getElementById('decrease');
        const plusBtn = document.getElementById('increase');

        let warenkorb = [];

        toggleBtn.addEventListener('click', () => {
            container.classList.add('warenkorbOffen');
            overlay.style.display = 'block';
        });

        closeBtn.addEventListener('click', () => {
            container.classList.remove('warenkorbOffen');
            overlay.style.display = 'none';
        });

        overlay.addEventListener('click', () => {
            container.classList.remove('warenkorbOffen');
            overlay.style.display = 'none';
        });

        minusBtn.addEventListener('click', alterValue('decrease'));
        plusBtn.addEventListener('click', alterValue('increase'));


        hinzufuegenBtn.addEventListener('click', () => {
            const name = document.querySelector('.productHeader h1').textContent;
            const preis = 869;
            const menge = parseInt(mengeInput.value);
            const bild = document.querySelector('.mainImage').src;

            const index = warenkorb.findIndex(e => e.name === name);
            if (index > -1) {
                warenkorb[index].quantity += menge;
            } else {
                warenkorb.push({ name, price: preis, quantity: menge, image: bild });
            }

            aktualisiereWarenkorb();
            container.classList.add('warenkorbOffen');
            overlay.style.display = 'block';
        });

        function aktualisiereWarenkorb() {
            const artikelZahl = warenkorb.reduce((sum, e) => sum + e.quantity, 0);
            anzahl.textContent = artikelZahl;

            while (inhalt.firstChild) inhalt.removeChild(inhalt.firstChild);

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
                            <input type="number" class="mengenEingabe" value="${artikel.quantity}" readonly>
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

            gesamt.textContent = `${gesamtPreis.toFixed(2).replace('.', ',')} €`;
        }

        aktualisiereWarenkorb();

        const hauptBild = document.querySelector('.mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail');
        thumbnails.forEach(t => {
            t.addEventListener('click', function () {
                hauptBild.src = this.src;
                thumbnails.forEach(thumb => thumb.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    
    
    
    
    
    //Funktion zum Erhöhen und Verringern der Anzahl

    function alterValue(operation) {

        var value = document.getElementById("anzahl").value;
  
        if (operation == 'decrease' && value > 1) {
          document.getElementById("anzahl").value = (parseInt(value) - 1);
        }
  
        if (operation == 'increase' && value < 10) {
          document.getElementById("anzahl").value = (parseInt(value) + 1);
        }
      }