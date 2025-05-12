function change(userinformation) {
    let newInfo = prompt("Bitte geben Sie einen neuen Wert ein:");
  
    if (!newInfo || newInfo.trim().length === 0) {
      alert("Eingabe darf nicht leer sein.");
      return;
    }
  
    if (userinformation.id === "userpassword") {
      if (newInfo.length < 3 || newInfo.length > 25) {
        alert("Passwort muss zwischen 3 und 25 Zeichen lang sein.");
      } else {
        userinformation.value = newInfo;
        userinformation.classList.add("inputOk");
        userinformation.classList.remove("inputFehler");
      }
    } else if (userinformation.id === "username") {
      if (newInfo.length > 25) {
        alert("Benutzername darf maximal 25 Zeichen haben.");
      } else {
        userinformation.value = newInfo;
        userinformation.classList.add("inputOk");
        userinformation.classList.remove("inputFehler");
      }
    }
  }
  