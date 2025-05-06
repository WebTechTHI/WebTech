
/* Skript für Login */

function validierung() {
    const Benutzername = document.getElementById("usernamelogin").value;


    // Der gewählte Benutzername muss aus mindestens fünf Zeichen bestehen
    if(usernamelogin.Input.value.length >= 5 && userpasswordlogin.Input.value.length >= 10) {

    }
    
    // Der gewählte Benutzername muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten


    // Das Passwort muss aus mindestens 10 Zeichen bestehen


    // Die Wiederholung des Passworts muss mit dem Passwort übereinstimmen



  }
  
  document.getElementById("usernamelogin").addEventListener("input", validierung);
  document.getElementById("userpasswordlogin").addEventListener("input", validierung);
  

















/* Skript für Registration */






/* Script für pop up fenster (direction button von registration zurück zu startseite zu kommen)*/
function confirmAction(message) {
    if (confirm(message)) {
        window.location = "../index.html";
    }
}




