
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
    var bruttoPreis= priceWOTax * 1.19;
    return bruttoPreis.toFixed(2).replace(".", ",");
}

/*function berechneBrutto() {
    var netto = document.getElementById("nettoPreis");
    var brutto = document.getElementById("bruttoPreis");

    var text = netto.textContent;
    var nettoText = text.replace("€", "").replace(",", ".").replace("Netto:", "").trim();

    if (document.getElementById("bruttoPreisBox").checked) {
        const bruttoWert = getTotalPrice(parseFloat(nettoText));
        let bruttoAnzeigen;

        if (Number.isInteger(bruttoWert)) {
            bruttoAnzeigen = bruttoWert + ',-';
        } else {
            bruttoAnzeigen = bruttoWert.toFixed(2).replace(".", ",");
        }

        brutto.innerHTML = "<strong>Preis: </strong>€ " + bruttoAnzeigen;
        brutto.style.display = "block";
    } else {
        brutto.style.display = "none";
    }
}
    */

function getPriceWOTax(priceWTax) {
    var nettoPreis = priceWTax *100 / 119;
    return nettoPreis.toFixed(2);
    
}

function berechneNetto(){
    var brutto = document.getElementById("bruttoPreis");
    var netto = document.getElementById("nettoPreis");

    var text = brutto.textContent;
    var bruttoText = text.replace("€", "").replace(",", ".").replace("Preis: ","").replace(".-","").trim();

    if (document.getElementById("nettoPreisBox").checked) {
        netto.innerHTML = "<strong>Netto: </strong>" + "€ " + getPriceWOTax(bruttoText).replace(".", ",") ;
        netto.style.display = "block";  
    } else {
        netto.style.display = "none";  
    }

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




// damit das Herz weiß wird beim Hovern beim Produkt
function favoriteImage() {
    document.querySelectorAll('.favorite-btn').forEach(favBtn => {
        const favImg = favBtn.querySelector('img');

        favBtn.addEventListener('mouseenter', () => {
            favImg.src = '/assets/images/icons/favorite.svg';
        });

        favBtn.addEventListener('mouseleave', () => {
            favImg.src = '/assets/images/icons/favorite-border.svg';
        });
    });
}


 


 {
        
document.addEventListener('DOMContentLoaded', () => {
    //erstmal wichtig beim Start der Seite kurz Funktion aufzurufen damit die Herzen weiß werden
    favoriteImage();


    // ab hier geht normal weiter
// Wichtige Funktion/EventListener zum Filtern der Produkte !!!!!!!!!!!!!!!
  document.getElementById('applyFilterBtn').addEventListener('click', () => {
            document.querySelector('.products-grid').style.opacity = '0.3'; // Ladeanimation
            document.querySelector('.filters').style.opacity = '0.3';

            const selectedFilters = {};

            document.querySelectorAll('.filter-checkbox:checked').forEach(cb => {
                const filterName = cb.dataset.filter;
                const value = cb.value;

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
                })
                .catch(err => {
                    console.error('Fehler beim Filtern:', err);
                });
        });     




    //Um die Filter Zurücksetzen
    document.getElementById('resetFilterBtn').addEventListener('click', () => {
            document.querySelectorAll('.filter-checkbox').forEach(cb => {
                cb.checked = false;
            });

            
            document.getElementById('applyFilterBtn').click();
        });  






    }); 


}




