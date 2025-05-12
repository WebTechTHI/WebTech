
/* Skript für User */
function ValidierungUser() {
    //Werte von Input feldern bekommn
    const benutzername = document.getElementById("username").value;
    const passwort = document.getElementById("userpassword").value;
  
    //Für css styling dom elemtne holen
    const benutzernameFeld = document.getElementById("username");
    const passwortFeld = document.getElementById("userpassword");

    //button
    const button = document.querySelector(".submitButton");
  


//Prüfbedingungen ab HIER:

    const langGenug = benutzername.length >= 5;

    const hatGroßBuchstabe = /[A-Z]/.test(benutzername);
    const hatKleinBuchstabe = /[a-z]/.test(benutzername);

    const passwortlänge = passwort.length >= 10;
  
   

//Abfrage der Prüfbedingungen ab HIER:

    // Button aktivieren nur wenn alles erfüllt ist
    if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe && passwortlänge) {
        button.disabled = false;
      } else {
        button.disabled = true;
      }


    // Benutzername prüfen
    if (benutzername.length === 0) {
        benutzernameFeld.classList.remove("inputOk");
        benutzernameFeld.classList.remove("inputFehler");
         //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv, also entweder: input ok oder input fehler sonst kämpfen die gegeneinander an !!

    } else if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
      benutzernameFeld.classList.add("inputOk");
      benutzernameFeld.classList.remove("inputFehler");

    } else {
      benutzernameFeld.classList.add("inputFehler");
      benutzernameFeld.classList.remove("inputOk");
    }
  
    // Passwort prüfen
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
    document.getElementById("username").addEventListener("input", ValidierungUser);
    document.getElementById("userpassword").addEventListener("input", ValidierungUser);

  




    function change(userinformation) {
        userinformation.disabled = false; // Feld aktivieren
        userinformation.focus(); // optional: Cursor ins Feld setzen
      
        let newInfo = prompt("Bitte geben Sie einen neuen Wert ein:");
      
        if (!newInfo || newInfo.trim().length === 0) {
          alert("Eingabe darf nicht leer sein.");
          return;
        }
      
        if (userinformation.id === "userpassword") {
          if (newInfo.length < 10) {
            alert("Passwort muss mindestens 10 Zeichen lang sein.");
          } else {
            userinformation.value = newInfo;
          }
        } else if (userinformation.id === "username") {
          const langGenug = newInfo.length >= 5;
          const hatGroßBuchstabe = /[A-Z]/.test(newInfo);
          const hatKleinBuchstabe = /[a-z]/.test(newInfo);
      
          if (langGenug && hatGroßBuchstabe && hatKleinBuchstabe) {
            userinformation.value = newInfo;
          } else {
            alert("Benutzername muss mindestens 5 Zeichen enthalten und einen Groß- sowie Kleinbuchstaben.");
          }
        }
      
        ValidierungUser(); // nach Änderung nochmal prüfen
      }
      
      // Initiale Prüfung beim Laden der Seite (falls Felder schon vorausgefüllt sind)
window.addEventListener("DOMContentLoaded", ValidierungUser);
