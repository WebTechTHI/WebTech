
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

/* Placeholder 😍*/


function getTotalPrice(priceWOTax) {
    var bruttoPreis= priceWOTax * 1.19;
    return bruttoPreis.toFixed(2).replace(".", ",");
}

function berechneBrutto() {
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

function getPriceWOTax(priceWTax) {
    var nettoPreis = priceWTax *100 / 119;
    return nettoPreis.toFixed(2);
    
}

function berechneNetto(){
    var brutto = document.getElementById("bruttoPreis");
    var netto = document.getElementById("nettoPreis");

    var text = brutto.textContent;
    var bruttoText = text.replace("€", "").replace(",", ".").replace("Preis: ","").replace(".-","").trim();
    console.log(bruttoText);

    if (document.getElementById("nettoPreisBox").checked) {
        netto.innerHTML = "<strong>Netto: </strong>" + "€ " + getPriceWOTax(bruttoText).replace(".", ",") ;
        netto.style.display = "block";  
    } else {
        netto.style.display = "none";  
    }

}
