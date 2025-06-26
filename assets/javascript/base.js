
/* Skript zum verschieben des Headers */
var oldPos = 0;
window.onscroll = function () {

    var position = window.pageYOffset;

    if (position - oldPos > 123) {

        document.getElementById("header").style.top = "-124px";
        oldPos = position;

    } else if (position - oldPos < -123) {

        document.getElementById("header").style.top = "0px";
        oldPos = position;
    }
}

/* Placeholder */


function getTotalPrice(priceWOTax) {
    var bruttoPreis = priceWOTax * 1.19;
    return bruttoPreis.toFixed(2).replace(".", ",");
}



function getPriceWOTax(priceWTax) {
    var nettoPreis = priceWTax * 100 / 119;
    return nettoPreis.toFixed(2);

}




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

//Sortier funktion das es abwechselt zwischen Aufsteigend und Absteigend
function toggleSort() {
    const btn = document.getElementById('sortButton');

    if (btn.textContent === 'Aufsteigend') {
        btn.textContent = 'Absteigend';
    } else {
        btn.textContent = 'Aufsteigend';
    }

}




// damit das Herz weiß wird beim Hovern beim Produkt (ABER macht die WISHLIST KAPUTT)
/* function favoriteImage() {
    document.querySelectorAll('.favorite-btn').forEach(favBtn => {
        const favImg = favBtn.querySelector('img');

        favBtn.addEventListener('mouseenter', () => {
            favImg.src = '/assets/images/icons/favorite.svg';
        });

        favBtn.addEventListener('mouseleave', () => {
            favImg.src = '/assets/images/icons/favorite-border.svg';
        });
    });
} */

function initFilterUi() {


    const applyBtn = document.getElementById('applyFilterBtn');
    const checkboxes = document.querySelectorAll('.filter-checkbox');


    function updateApplyButtonState() {
        // true, wenn mindestens eine Checkbox gecheckt ist
        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
        applyBtn.disabled = !anyChecked;
        applyBtn.title = anyChecked ? 'Filter anwenden' : 'Bitte mindestens einen Filter auswählen';

    }

    // Event-Listener an jede Checkbox hängen
    checkboxes.forEach(cb => cb.addEventListener('change', updateApplyButtonState));

    // Initialer Zustand
    updateApplyButtonState();
    
}





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


            const category = new URLSearchParams(window.location.search).get('category') || 'alle';

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
                    favoriteImage();
                    initFilterUi();
                })
                .catch(err => {
                    console.error('Fehler beim Filtern:', err);
                });
}


{

    document.addEventListener('DOMContentLoaded', () => {
  initFilterUi();
  favoriteImage();

  // Klick auf „Anwenden“
  document.getElementById('applyFilterBtn')
          .addEventListener('click', applyFilters);

  // Klick auf „Zurücksetzen“
  document.getElementById('resetFilterBtn')
          .addEventListener('click', () => {
    // 1) Alle Checkboxen zurücksetzen
    document.querySelectorAll('.filter-checkbox')
            .forEach(cb => cb.checked = false);
    // 2) Button-State neu setzen
    initFilterUi();
    // 3) Filter-Logik direkt aufrufen 
    applyFilters();
  });
});


}




