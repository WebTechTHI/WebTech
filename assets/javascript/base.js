
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
    return priceWOTax * 1.19;
}

function berechneBrutto(){
    var brutto = document.getElementById("")
}

function getPriceWOTax(priceWTax) {
    var bruttoPreis = priceWTax *100 / 119;
    return bruttoPreis.toFixed(2);
    
}

function berechneNetto(){
    var brutto = document.getElementById("bruttoPreis");
    var netto = document.getElementById("nettoPreis");

    var text = brutto.textContent;
    var bruttoText = text.replace("€", "").replace(",", ".").replace("Preis: ","");
    console.log(bruttoText);

    if (document.getElementById("nettoPreisBox").checked) {
        netto.innerHTML = "<strong>Netto: </strong>" + getPriceWOTax(bruttoText).replace(".", ",") + "€";
        netto.style.display = "block";  
    } else {
        netto.style.display = "none";  
    }

}
