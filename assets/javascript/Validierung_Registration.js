
/* Skript für Registration */
    
    // (REGISTRATION HIER)  Die Wiederholung des Passworts muss mit dem Passwort übereinstimmen
    const passwortregistration = document.getElementById("userpasswordregistration").value;
    const passwortregistrationconfirm = document.getElementById("userpasswordregistrationconfirmation").value;

  




/* Script für pop up fenster (direction button von registration zurück zu startseite zu kommen)*/
function confirmAction(message) {
    if (confirm(message)) {
        window.location = "../index.html";
    }
}

