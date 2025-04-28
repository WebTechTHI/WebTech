
/* Skript zum verschieben des Headers */
var oldPos = 0;
window.onscroll = function () {

    var position = window.pageYOffset;

    if (position - oldPos > 50) {

        document.getElementById("header").classList.add("hideHeader");
        oldPos = position;

    } else if (position - oldPos < -50) {

        document.getElementById("header").classList.remove("hideHeader");
        oldPos = position;
    }
}

/* Placeholder */