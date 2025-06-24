
/* Skript für Login */
function ValidierungLogin() {
    //Werte von Input feldern bekommn
    const benutzername = document.getElementById("usernamelogin").value;
    const passwort = document.getElementById("userpasswordlogin").value;

    //Für css styling dom elemtne holen
    const benutzernameFeld = document.getElementById("usernamelogin");
    const passwortFeld = document.getElementById("userpasswordlogin");

  
    const button = document.querySelector(".submitButton");


//Prüfbedingungen ab HIER:

   
    const langGenug = benutzername.length >= 5;


    const hatGroßBuchstabe = /[A-Z]/.test(benutzername);
    const hatKleinBuchstabe = /[a-z]/.test(benutzername);               //RegEx = Regelprüfer für angegebnen text
                                                                        //.test = prüft, ob das im benutzername vorkommt

   
    const passwortlänge = passwort.length >= 10;



//Abfrage der Prüfbedingungen ab HIER:


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
 //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv,
 //  also entweder: input ok oder input fehler sonst kämpfen die gegeneinander an !!


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
     //Prüfung hier ob Paswort falsh eingegeben wurde
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
  //  (ruft dann Funktion validierung auf und führ aus)
  // 
  //Statt als Event input = liveprüfung geht auch change (das ist wenn erst das feld verlässt nicht sofort) oder click als event !
  document.getElementById("usernamelogin").addEventListener("input", ValidierungLogin);
  document.getElementById("userpasswordlogin").addEventListener("input", ValidierungLogin);
  






