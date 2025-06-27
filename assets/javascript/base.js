//LAURIN SCHNIZER

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


//LAURIN SCHNIZER ENDE







