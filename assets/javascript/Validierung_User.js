
/* Skript für User */
function ValidierungUser() {

    const benutzername = document.getElementById("username").value;
    const passwort = document.getElementById("userpassword").value;
  
 
    const benutzernameFeld = document.getElementById("username");
    const passwortFeld = document.getElementById("userpassword");

  
    const button = document.querySelector(".submitButton");
  


//Prüfbedingungen ab HIER:

    const langGenug = benutzername.length >= 5;

    const hatGroßBuchstabe = /[A-Z]/.test(benutzername);
    const hatKleinBuchstabe = /[a-z]/.test(benutzername);

    const passwortlänge = passwort.length >= 10;
  
   

//Abfrage der Prüfbedingungen ab HIER:


    if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe && passwortlänge) {
        button.disabled = false;
      } else {
        button.disabled = true;
      }


   
    if (benutzername.length === 0) {
        benutzernameFeld.classList.remove("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
         //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv, also entweder: 
         // input ok oder input fehler sonst kämpfen die gegeneinander an !!

    } else if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
      benutzernameFeld.classList.add("inputOk");
      benutzernameFeld.classList.remove("inputFehler");

    } else {
      benutzernameFeld.classList.add("inputFehler");
      benutzernameFeld.classList.remove("inputOk");
    }
  
   
    if (passwort.length === 0) {
        passwortFeld.classList.remove("inputOk");
        passwortFeld.classList.remove("inputFehler");
    } else if (passwortlänge) {
      passwortFeld.classList.add("inputOk");
      passwortFeld.classList.remove("inputFehler");
    } else {
      passwortFeld.classList.add("inputFehler");
      passwortFeld.classList.remove("inputOk");
    }
  

  }
  //Muss auserhalb der Funktion stehen !!!!
  //Hier Können die inputs quasi in den felder hören/listen wenn eine eingabe passiert bzw. sich was ändert !       --> Event Listener 
  // 
  //Statt als Event input = liveprüfung geht auch change (das ist wenn erst das feld verlässt nicht sofort) oder click als event !
    document.getElementById("username").addEventListener("input", ValidierungUser);
    document.getElementById("userpassword").addEventListener("input", ValidierungUser);

  

