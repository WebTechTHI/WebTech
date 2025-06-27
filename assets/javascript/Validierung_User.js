// LAURIN SCHNITZER


/* Skript für User */
function ValidierungUser() {
  const username = document.getElementById("username").value;
  const password = document.getElementById("userpassword").value;

  const usernameField = document.getElementById("username");
  const passwordField = document.getElementById("userpassword");
  const submitBtn = document.querySelector(".submitButton");

  //Prüfbedingungen  HIER:
  const usernameValid =
    username.length >= 5 && /[A-Z]/.test(username) && /[a-z]/.test(username);
  const passwordValid = password.length === 0 || password.length >= 10;

  //Abfrage der Prüfbedingungen  HIER:
  // Farben Username
  if (username.length === 0) {
    usernameField.classList.remove("inputOk", "inputFehler");
  } else if (usernameValid) {
    usernameField.classList.add("inputOk");
    usernameField.classList.remove("inputFehler");
  } else {
    usernameField.classList.add("inputFehler");
    usernameField.classList.remove("inputOk");
  }

  // Farben Passwort
  if (password.length === 0) {
    passwordField.classList.remove("inputOk", "inputFehler");
  } else if (passwordValid) {
    passwordField.classList.add("inputOk");
    passwordField.classList.remove("inputFehler");
  } else {
    passwordField.classList.add("inputFehler");
    passwordField.classList.remove("inputOk");
  }
  //--> mit remove von rot wollen wir das --> immer nur EINE Klasse aktiv, also entweder:
  // input ok oder input fehler sonst kämpfen die gegeneinander an !!

  // Button sperren/freigeben
  submitBtn.disabled = !(usernameValid && passwordValid);
}

//Muss auserhalb der Funktion stehen !!!!
//Hier Können die inputs quasi in den felder hören/listen wenn eine eingabe passiert bzw. sich was ändert !       --> Event Listener
//
//Statt als Event input = liveprüfung geht auch change (das ist wenn erst das feld verlässt nicht sofort) oder click als event !

// Events binden
document.addEventListener("DOMContentLoaded", () => {
  ValidierungUser();  // sofort prüfen!
  document.getElementById("username").addEventListener("input", ValidierungUser);
  document.getElementById("userpassword").addEventListener("input", ValidierungUser);
});

// LAURIN SCHNITZER ENDE

