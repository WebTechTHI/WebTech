//Michael Pietsch

function updatePriceTag(selectorId, pricetagId) {


    //komponentenselektor, preistag anzeige und gesamtbetrag speichern
    const selectorContainer = document.getElementById(selectorId);
    //ausgewählte option
    const selectedOption = selectorContainer.selectedOptions[0];
    const tagContainer = document.getElementById(pricetagId);

    const totalContainer = document.getElementById('total-amount');

    //preis der komponente auslesen
    const componentPrice = selectedOption.dataset.price;

    //preis in tag setzen, falls nichts ausgewählt wurde auf leeren string setzen
    if (componentPrice != '') {
        tagContainer.innerHTML = '+ ' + componentPrice + '€';
        tagContainer.style.backgroundColor = 'rgb(211, 21, 21)';
    } else {
        tagContainer.innerHTML = '';
        tagContainer.style.backgroundColor = 'transparent';
    }


    //berechnung gesamtSumme
    var totalSum = 0;

    const allComponents = document.getElementsByClassName('component-select');

    //für jede komponente wird der preis errechnet, geprüft ob es sich um eine nummer handelt
    //oder einen wert hat der nan entspricht (bei auswahl von 'keine'). dann summiert
    Array.from(allComponents).forEach(element => {

        var componentprice = parseFloat(element.selectedOptions[0].dataset.price);
        if (isNaN(componentprice)) {
            totalSum += 0;
        } else {
            totalSum += componentprice;
        }
    });


    //summe setzen 
    totalContainer.innerHTML = totalSum.toFixed(2) + '€';




}


document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('configure-form');
    const selects = form.querySelectorAll('.component-select');
    const submitBtn = document.getElementById('submit-button');

    function checkFormValidity() {
        const allValid = Array.from(selects).every(select => select.value !== "");
        submitBtn.disabled = !allValid;
        submitBtn.style.opacity = allValid ? "100%" : "70%";
    }

    // Initialprüfung beim Laden
    checkFormValidity();

    // funktionsaufruf sobald ein select geändert wird
    selects.forEach(select => {
        select.addEventListener('change', checkFormValidity);
    });
});
