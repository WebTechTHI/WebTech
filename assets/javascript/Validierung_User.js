function change(userinformation) {
    let newInfo = prompt("Bitte geben Sie einen neuen Wert ein:");
  
    if (!newInfo || newInfo.trim().length === 0) {
      alert("Eingabe darf nicht leer sein.");
      return;
    }
  
    if (userinformation.id === "userpassword") {
      if (newInfo.length < 8 || newInfo.length > 16) {
        alert("Passwort muss zwischen 8 und 16 Zeichen lang sein.");
      } else {
        userinformation.value = newInfo;
        userinformation.classList.add("inputOk");
        userinformation.classList.remove("inputFehler");
      }
    } else if (userinformation.id === "username") {
      if (newInfo.length > 20) {
        alert("Benutzername darf maximal 20 Zeichen haben.");
      } else {
        userinformation.value = newInfo;
        userinformation.classList.add("inputOk");
        userinformation.classList.remove("inputFehler");
      }
    }
  }
  