
/* Skript für Login */

function validierung() {
    //Werte von Input feldern bekommn
    const benutzername = document.getElementById("usernamelogin").value;
    const passwort = document.getElementById("userpasswordlogin").value;

    //Für css styling dom elemtne holen
    const benutzernameFeld = document.getElementById("usernamelogin");
    const passwortFeld = document.getElementById("userpasswordlogin");

    //Button holen für aktivieren deaktivieren
    const button = document.querySelector(".submitButton");


    // Der gewählte Benutzername muss aus mindestens fünf Zeichen bestehen
    const langGenug = benutzername.length >= 5;

    // Der gewählte Benutzername muss mindestens einen Großbuchstaben und einen Kleinbuchstaben enthalten
    const hatGroßBuchstabe = /[A-Z]/.test(benutzername);
    const hatKleinBuchstabe = /[a-z]/.test(benutzername);               //RegEx = Regelprüfer für Texte
                                                                        // [A-Z] = mindestens 1 Großbuchstabe
                                                                        //[a-z] = mindestens 1 Kleinbuchstabe
                                                                        //.test(...) = prüft, ob das im Text vorkommt

    // Das Passwort muss aus mindestens 10 Zeichen bestehen
    const passwortlänge = passwort.length >= 10;



    
   //Prüfung hier ob benutzername falsch eingegeben wurde
    if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
        benutzernameFeld.classList.add("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
        //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv, also entweder: input ok oder input fehler sonst kämpfen die gegeneinander an !!
    }
    else{
        benutzernameFeld.classList.add("inputFehler");
        benutzernameFeld.classList.remove("inputOk");
    }

    //Prüfung hier ob Paswort falsh eingegeben wurde
    if (passwortlänge){
        passwortFeld.classList.add("inputOk");
        passwortFeld.classList.remove("inputFehler");
    }
    else{
        passwortFeld.classList.add("inputFehler");
        passwortFeld.classList.remove("inputOk");
    }



     //Buttons disablen oder nicht --> Prüfung also
    if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe && passwortlänge) {
        button.disabled = false;
    }
    else{
        button.disabled = true;
    }



    
    //Prüfen ob schon was eingegeben wurde den wenn nicht soll es mit standart modus grün angezeigt werden !
    if (benutzername.length === 0) {
        benutzernameFeld.classList.remove("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
    }
    else if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
        benutzernameFeld.classList.add("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
    }
    else {
        benutzernameFeld.classList.add("inputFehler");
        benutzernameFeld.classList.remove("inputOk");
    }

    //Prüfen ob bei passwort auch schon was eingegeben wurde !
    if (passwort.length === 0) {
        passwortFeld.classList.remove("inputOk");
        passwortFeld.classList.remove("inputFehler");
    }
    else if (passwortlänge) {
        passwortFeld.classList.add("inputOk");
        passwortFeld.classList.remove("inputFehler");
    }
    else {
        passwortFeld.classList.add("inputFehler");
        passwortFeld.classList.remove("inputOk");
    }


}



    



  //Muss auserhalb der Funktion stehen !!!!
  //Hier Können die inputs quasi in den felder hören/listen wenn eine eingabe passiert bzw. sich was ändert !       --> Event Listener
  document.getElementById("usernamelogin").addEventListener("input", validierung);
  document.getElementById("userpasswordlogin").addEventListener("input", validierung);
  









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

