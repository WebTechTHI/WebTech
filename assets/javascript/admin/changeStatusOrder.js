//MICHAEL PIETSCH


//skript, um die farben der status codes bei der admin bestellubersicht zu ändern
function changeOrderStatus(id) {

    //id des <select> elements, aus dem der value ausgelesen werden soll
    const selectorId = "status-select-order-" + id;
    //id der farbigen statusanzeige die abhängig vom value des selektors verändert werden soll
    const statusColorId = "status-color-order-" + id;

    //selektor element
    const selector = document.getElementById(selectorId);
    //farbige statusanzeige
    const statusColor = document.getElementById(statusColorId);

    //wert des selektors
    const value = selector.value;

    //setzen der entsprechenden farbe
    switch (value) {
        case "1":
            statusColor.style.backgroundColor = "darkorange"; 
            break;
        case "2":
            statusColor.style.backgroundColor = "cornflowerblue"; 
            break;
        case "3":
            statusColor.style.backgroundColor = "dodgerblue";
            break;
        case "4":
            statusColor.style.backgroundColor = "forestgreen";
            break;
        case "5":
            statusColor.style.backgroundColor = "peru";
            break;
        case "6":
            statusColor.style.backgroundColor = "sienna";
            break;
        case "7":
            statusColor.style.backgroundColor = "crimson";
            break;
    }


    //datenbankajktualisierung
    fetch("/index.php?page=admin&action=updateOrderStatus", {

        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id,
            value
        })
    });
}

//MICHAEL PIETSCH ENDE
