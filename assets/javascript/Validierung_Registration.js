
/* Skript für Registration */
   
function ValidierungRegistration() {

    const benutzername = document.getElementById("usernameregistration").value;
    const passwort = document.getElementById("userpasswordregistration").value;
    const passwortWdh = document.getElementById("userpasswordregistrationconfirmation").value;


    const benutzernameFeld = document.getElementById("usernameregistration");
    const passwortFeld = document.getElementById("userpasswordregistration");
    const passwortWdhFeld = document.getElementById("userpasswordregistrationconfirmation");


    const button = document.querySelector(".submitButton");



//Prüfbedingungen ab HIER:


    //Wir prüfen erst ob pw mit pwWdh übereinstimmt und dann noch ob auch passwortWdh länger als 0 ist da sonst auch 
    // leerer string mit leerem string übereinstimmt also ohne eine eingabe ist auch korrekt und das darf nicht!
    const pwStimmt = passwort === passwortWdh && passwortWdh.length > 0;




    
    const langGenug = benutzername.length >= 5;

  
    const hatGroßBuchstabe = /[A-Z]/.test(benutzername);
    const hatKleinBuchstabe = /[a-z]/.test(benutzername);               //RegEx = Regelprüfer für Texte
                                                                        // [A-Z] = mindestens 1 Großbuchstabe
                                                                        //[a-z] = mindestens 1 Kleinbuchstabe
                                                                        //.test(...) = prüft, ob das im Text vorkommt

 
    const passwortlänge = passwort.length >= 10;




//Abfrage der Prüfbedingungen ab HIER:

   
    if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe && passwortlänge && pwStimmt) {
        button.disabled = false;
    }
    else{
        button.disabled = true;
    }


    
    //Prüfen ob schon was eingegeben wurde den wenn nicht soll es mit standart modus grün angezeigt werden !
    if (benutzername.length === 0) {
        benutzernameFeld.classList.remove("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
    //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv, also entweder: input ok oder 
    // input fehler sonst kämpfen die gegeneinander an !!


    }
   
    else if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
        benutzernameFeld.classList.add("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
    }
    else {
        benutzernameFeld.classList.add("inputFehler");
        benutzernameFeld.classList.remove("inputOk");
    }

    
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


    
    if(passwortWdh.length === 0) {
        passwortWdhFeld.classList.remove("inputOk");
        passwortWdhFeld.classList.remove("inputFehler");
    }
    else if (pwStimmt){
        passwortWdhFeld.classList.add("inputOk");
        passwortWdhFeld.classList.remove("inputFehler");
    }
    else {
        passwortWdhFeld.classList.add("inputFehler");
        passwortWdhFeld.classList.remove("inputOk");    
    }





}


  //Muss auserhalb der Funktion stehen !!!!
  //Hier Können die inputs quasi in den felder hören/listen wenn eine eingabe passiert bzw. sich was ändert !       --> Event Listener 
  // 
  //Statt als Event input = liveprüfung geht auch change (das ist wenn erst das feld verlässt nicht sofort) oder click als event !
    document.getElementById("usernameregistration").addEventListener("input", ValidierungRegistration);
    document.getElementById("userpasswordregistration").addEventListener("input", ValidierungRegistration)
    document.getElementById("userpasswordregistrationconfirmation").addEventListener("input", ValidierungRegistration)




/* Script für pop up fenster (direction button von registration zurück zu startseite zu kommen)*/
function confirmAction(message) {
    if (confirm(message)) {
        window.location = "../index.html";
    }
}

