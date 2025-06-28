//MICHAEL PIETSCH 


//skript, um die farben der status codes bei der admin bestellubersicht zu ändern
function changeUserStatus(id) {

    //id des <select> elements, aus dem der value ausgelesen werden soll
    const selectorId = "status-select-user-" + id;
    //id der farbigen statusanzeige die abhängig vom value des selektors verändert werden soll
    const statusColorId = "status-color-user-" + id;

    //selektor element
    const selector = document.getElementById(selectorId);
    //farbige statusanzeige
    const statusColor = document.getElementById(statusColorId);

    //wert des selektors
    const value = selector.value;

    //setzen der entsprechenden farbe
    switch (value) {
        case "1":
            statusColor.style.backgroundColor = "crimson"; 
            break;
        case "2":
            statusColor.style.backgroundColor = "forestgreen"; 
            break;
    }

    fetch("/index.php?page=admin&action=updateUserStatus", {

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
