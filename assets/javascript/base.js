//RINOR STUBLLA

/* Skript zum verschieben des Headers */
var oldPos = 0;
window.onscroll = function () {

    //Aktuelle Scroll Position
    var position = window.pageYOffset;


    //Wenn Nutzer mehr als 123px nach unten scrollt --> stecke header
    if (position - oldPos > 123) {

        document.getElementById("header").style.top = "-124px";
        oldPos = position;

    } else if (position - oldPos < -123) {

        document.getElementById("header").style.top = "0px";
        oldPos = position;
    }
}

/* Placeholder */

//	Rechnet einen Netto-Preis in einen Brutto-Preis mit 19 % MwSt um
function getTotalPrice(priceWOTax) {
    var bruttoPreis = priceWOTax * 1.19;
    return bruttoPreis.toFixed(2).replace(".", ",");
}


//	Rechnet einen Brutto-Preis zurück in Netto (Basis: 119 % = 100 % + 19 %)
function getPriceWOTax(priceWTax) {
    var nettoPreis = priceWTax * 100 / 119;
    return nettoPreis.toFixed(2);

}


//RINOR STUBLLA ENDE







