//RINOR STUBLLA 

//----------AB HIER ALLES FÜR KATEGORIEN SEITE------------------//
// -----------RINOR STUBLLA------------------//
function toggleFilter(header) {
    const options = header.nextElementSibling;
    options.classList.toggle('collapsed');
    header.classList.toggle('open');
}

// Um die Sidebar zu toggeln, wenn der Button geklickt wird
function toggleSidebar() {
    const sidebar = document.getElementById('sidebarContent');
    const icon = document.querySelector('.toggle-icon');

    sidebar.classList.toggle('closed');
    icon.classList.toggle('rotated');
}

//Sortier funktion das es abwechselt zwischen Aufsteigend und Absteigend (Aber hier nur Optik tatsächliche "funktion" wird in categoryFunctions gemacht mit direction variable)
function toggleSort() {
    const btn = document.getElementById('sortButton');

    if (btn.textContent === 'Aufsteigend') {
        btn.textContent = 'Absteigend';
    } else {
        btn.textContent = 'Aufsteigend';
    }

}




//initialize Filter User Interface (initFilterUi) ist dafür da das der Anwenden Button bei Filter nur aktiv ist 
// wenn mind. 1 Filter geklickt wurde => sonst deaktiviert
//initialize Filter User Interface
function initFilterUi() {


    const applyBtn = document.getElementById('applyFilterBtn');
    const checkboxes = document.querySelectorAll('.filter-checkbox');


    function updateApplyButtonState() {
        // true, wenn mindestens eine Checkbox gecheckt ist (anyChecked ist boolean)
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);

        //die zeile greift nur wenn anyChecked (boolean) false ist also wenn keine checkbox angeclickt wurde (wird aber immer ausgeführt)
        applyBtn.disabled = !anyChecked;

        //Prüft boolean ob true (dann button deaktiviert =)
        applyBtn.title = anyChecked ? 'Filter anwenden' : 'Bitte mindestens einen Filter auswählen';

    }

    // Event-Listener an jede Checkbox hängen
    checkboxes.forEach(cb => cb.addEventListener('change', updateApplyButtonState));

    // Initialer Zustand
    updateApplyButtonState();
    
}





//Kernfunktion für die AJAX- Filterung
function applyFilters() {
    document.querySelector('.products-grid').style.opacity = '0.3'; // Ladeanimation
    document.querySelector('.filters').style.opacity = '0.3';

          const selectedFilters = {};

            document.querySelectorAll('.filter-checkbox:checked').forEach(checkbox => {
                const filterName = checkbox.dataset.filter;
                const value = checkbox.value;

                if (!selectedFilters[filterName]) {
                    selectedFilters[filterName] = [];
                }
                selectedFilters[filterName].push(value);
            });


            //Window.Location.search ist BOM zugriff auf Browser infos sozusagen
            const category = new URLSearchParams(window.location.search).get('category') || 'alle';


            //Ajax Fetch Request mit JavaScript
            fetch('filterProducts.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    category: category,
                    filters: selectedFilters
                })
            })
                .then(res => res.json())
                .then(data => {
                    document.querySelector('.products-grid').innerHTML = data.productsHtml;
                    document.querySelector('.filters').innerHTML = data.filtersHtml;
                    document.querySelector('.products-grid').style.opacity = '1';
                    document.querySelector('.filters').style.opacity = '1';
                 

                    //Checkboxen wieder aktivieren (neu legen)
                    initFilterUi();
                })
                .catch(err => {
                    console.error('Fehler beim Filtern:', err);
                });
}


{


    //Das hier ausführen wenn seite vollständig geladen ist (DOMContentLoaded)
    document.addEventListener('DOMContentLoaded', () => {
  initFilterUi();      // Setzt den Zustand der Filterbuttons
 

  // Klick auf „Anwenden“
  document.getElementById('applyFilterBtn')
          .addEventListener('click', applyFilters);

  // Klick auf „Zurücksetzen“
  document.getElementById('resetFilterBtn')
          .addEventListener('click', () => {
    // 1) Alle Checkboxen zurücksetzen
    document.querySelectorAll('.filter-checkbox')
            .forEach(cb => cb.checked = false);             // alle Checkboxen deaktivieren
    // 2) Button-State neu setzen
    initFilterUi();                                         // Button-Status neu setzen
    // 3) Filter-Logik direkt aufrufen 
    applyFilters();                                         // Neue Abfrage ohne Filter (und das dann anzeigen)
  });
});


}

//RINOR STUBLLA ENDE