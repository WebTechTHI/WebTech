async function loadProducts(kategorie) {
    try {
        const response = await fetch('../assets/json/productList.json');
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const products = await response.json();
        const container = document.querySelector('.products-grid');

        if (!container) {
            console.error('Kein Container ".products-grid" gefunden.');
            return;
        }

        erstelleDesign(kategorie);
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
                        <img src="../${product.bild[0]}" alt="${product.alt}">
                    </div>
                    <div class="product-details">
                        <h4 class="product-title">${product.name}</h4>
                        <ul class="product-specs">
                            ${product.spezifikationen.map(spec => `<li>${spec}</li>`).join('')}
                        </ul>
                        <div class="price"><span class="price-prefix">€</span>${preisMitKomma}</div>
                        <div class="financing">Jetzt mit 0% Finanzierung</div>
                        <a href="../${product.seitenlink}?id=${product.id}" class="buy-btn">Jetzt konfigurieren</a>
                    </div>
                `;

                container.appendChild(div);
            }
        });

    } catch (error) {
        console.error('Fehler beim Laden der Produkte:', error);
    }
}






async function erstelleDesign(kategorie) {
    try {
        // Beide JSON-Dateien laden
        const [produktResponse, beschreibungResponse] = await Promise.all([
            fetch('../assets/json/productList.json'),
            fetch('../assets/json/produktBeschreibung.json')
        ]);
        
        const products = await produktResponse.json();
        const kategorieInhalte = await beschreibungResponse.json();

        // Prüfen ob Kategorie existiert
        if (!kategorieInhalte[kategorie]) {
            console.error(`Kategorie '${kategorie}' nicht gefunden`);
            return;
        }

        const inhalt = kategorieInhalte[kategorie];

        // Breadcrumb aktualisieren
        const breadcrumb = document.querySelector('.breadcrumb');
        if (breadcrumb) {
            breadcrumb.innerHTML = `<a href="#">MLR</a> › <span>${inhalt.breadcrumb}</span>`;
        }

        // Sidebar aktualisieren
        const sidebarTitle = document.querySelector('.sidebar-title');
        if (sidebarTitle) {
            sidebarTitle.textContent = inhalt.sidebarTitel;
        }

        const sidebarMenu = document.querySelector('.sidebar-menu');
        if (sidebarMenu) {
            sidebarMenu.innerHTML = inhalt.unterkategorien
                .map(uk => `<li><a href="../${uk.link}">${uk.name}</a></li>`)
                .join('');
        }

        // Banner aktualisieren
        const bannerContent = document.querySelector('.banner-content');
        if (bannerContent) {
            bannerContent.innerHTML = `
                <h1><span>${inhalt.titel}</span> ${inhalt.untertitel}</h1>
                <p>${inhalt.beschreibung}</p>
            `;
        }

        // Section Info aktualisieren
        const sectionInfo = document.querySelector('.section-info');
        if (sectionInfo) {
            sectionInfo.innerHTML = `
                <h2>${inhalt.infoTitel}</h2>
                <p class="subtext">${inhalt.infoText}</p>
                <div class="read-more">
                    <a href="#">mehr lesen ▼</a>
                </div>
            `;
        }

        // Unterkategorien nur bei Hauptkategorien anzeigen
        const categories = document.querySelector('.categories');
        if (categories) {
            if (inhalt.unterkategorien && inhalt.unterkategorien.length > 0) {
                // Hauptkategorien: Unterkategorien anzeigen
                categories.innerHTML = inhalt.unterkategorien
                    .map(uk => `
                        <a href="../${uk.link}">
                            <div class="category">
                                <div class="category-title">${uk.name.toUpperCase()}</div>
                                <div class="category-image">
                                    <img src="../${uk.bild}" alt="${uk.name}">
                                </div>
                            </div>
                        </a>
                    `).join('');
                
                // Section-Title anzeigen
                const sectionTitle = document.querySelector('.section-title');
                if (sectionTitle) {
                    sectionTitle.textContent = 'UNTERKATEGORIE WÄHLEN:';
                    sectionTitle.style.display = 'block';
                }
            } else {
                // Unterkategorien und Angebote: Kategorie-Auswahl verstecken
                categories.style.display = 'none';
                
                // Section-Title verstecken
                const sectionTitle = document.querySelector('.section-title');
                if (sectionTitle) {
                    sectionTitle.style.display = 'none';
                }
            }
        }

        // Filter nur bei Kategorien mit Produkten erstellen
        const kategorien = ['pc', 'laptop', 'gamingPc', 'officePc', 'gamingLaptop', 'officeLaptop', 'monitor','maus','tastatur'];
        if (kategorien.includes(kategorie)) {
            erstelleFilter(products, kategorie);
        } else {
            // Bei Unterkategorien und Angeboten Filter ausblenden/leeren
            const filterSection = document.querySelector('.filter-section');
            if (filterSection) {
                const filterGroups = filterSection.querySelectorAll('.filter-group');
                filterGroups.forEach(group => group.remove());
            }
        }

    } catch (error) {
        console.error('Fehler beim Erstellen des Designs:', error);
    }
}

function erstelleFilter(products, kategorie) {
    // Nur Produkte der aktuellen Kategorie filtern
    const relevanteProdukte = products.filter(p => p.kategorie === kategorie || p.unterkategorie === kategorie);
    
    if (relevanteProdukte.length === 0) return;

    // Mapping für bessere Bezeichnungen
    const bezeichnungMapping = {
        'Prozessor': 'CPU',
        'RAM': 'Arbeitsspeicher',
        'Grafik': 'Grafikkarte',
        'Speicher': 'Festplatte',
        'Display': 'Bildschirm',
        'Betriebssystem': 'OS',
        'Displaytyp': 'Display',
        'Auflösung': 'Auflösung',
        'Bildwiederholrate': 'Hz',
        'Reaktionszeit': 'Response Time',
        'Sensor': 'DPI',
        'Layout': 'Tastatur-Layout',
        'Typ': 'Typ'
    };

    // Alle technischen Details sammeln
    const filterKategorien = new Map();

    relevanteProdukte.forEach(produkt => {
        if (produkt.technischeDetails) {
            produkt.technischeDetails.forEach(detail => {
                Object.entries(detail).forEach(([key, value]) => {
                    const filterName = bezeichnungMapping[key] || key;
                    
                    if (!filterKategorien.has(filterName)) {
                        filterKategorien.set(filterName, new Set());
                    }

                    // Wert bis zum ersten Komma nehmen
                    let filterWert = value.split(',')[0].trim();
                    
                    // Spezielle Behandlung für verschiedene Werttypen
                    if (key === 'Prozessor') {
                        // z.B. "Intel Core i7-12700K" -> "Intel Core i7"
                        const match = filterWert.match(/(Intel Core i\d+|AMD Ryzen \d+|AMD Athlon)/i);
                        if (match) {
                            filterWert = match[1];
                        }
                    } else if (key === 'RAM') {
                        // z.B. "32GB DDR4" -> "32GB"
                        const match = filterWert.match(/(\d+GB)/);
                        if (match) {
                            filterWert = match[1];
                        }
                    } else if (key === 'Grafik') {
                        // z.B. "NVIDIA GeForce RTX 4060" -> "RTX 4060"
                        const match = filterWert.match(/(RTX \d+|GTX \d+|RX \d+|Intel UHD|Radeon)/i);
                        if (match) {
                            filterWert = match[1];
                        }
                    } else if (key === 'Speicher') {
                        // z.B. "1TB M.2 SSD" -> "1TB SSD"
                        const match = filterWert.match(/(\d+(?:GB|TB))\s*.*?(SSD|HDD)?/i);
                        if (match) {
                            filterWert = match[1] + (match[2] ? ' ' + match[2] : '');
                        }
                    }

                    filterKategorien.get(filterName).add(filterWert);
                });
            });
        }
    });

    // Filter HTML generieren
    const filterContainer = document.querySelector('.filter-section');
    if (!filterContainer) return;

    // Bestehende Filter-Gruppen außer dem Reset-Button entfernen
    const existingGroups = filterContainer.querySelectorAll('.filter-group');
    existingGroups.forEach(group => group.remove());

    // Neue Filter-Gruppen erstellen
    filterKategorien.forEach((werte, kategorieName) => {
        if (werte.size > 0) { // Nur anzeigen, wenn mehrere Optionen vorhanden
            const filterGroup = document.createElement('div');
            filterGroup.className = 'filter-group';
            
            const werteArray = Array.from(werte).sort();
            const optionsHtml = werteArray
                .map(wert => {
                    const id = `${kategorieName.toLowerCase()}-${wert.replace(/\s+/g, '-').toLowerCase()}`;
                    const count = relevanteProdukte.filter(p => 
                        p.technischeDetails.some(detail => 
                            Object.entries(detail).some(([key, value]) => {
                                const filterName = bezeichnungMapping[key] || key;
                                return filterName === kategorieName && value.includes(wert);
                            })
                        )
                    ).length;
                    
                    return `<li><input type="checkbox" id="${id}"> <label for="${id}">${wert} (${count})</label></li>`;
                })
                .join('');

            filterGroup.innerHTML = `
                <div class="filter-header">
                    <span>${kategorieName}</span>
                    <span>▼</span>
                </div>
                <ul class="filter-options">
                    ${optionsHtml}
                </ul>
            `;

            filterContainer.appendChild(filterGroup);
        }
    });
}