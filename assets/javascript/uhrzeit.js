/* Uhrzeit javascript im Header einfügen bei Login Registration und User */

function startLiveClock() {
  const clockDiv = document.getElementById("liveClock");

  function updateClock() {
    const now = new Date();
    const hours = now.getHours();
    const minutes = now.getMinutes().toString().padStart(2, "0");
    const seconds = now.getSeconds().toString().padStart(2, "0");

    let greeting = "Platzhalter";
    if (hours < 12) greeting = "Guten Morgen";
    else if (hours < 18) greeting = "Guten Tag ";
    else greeting = "Guten Abend";

    clockDiv.textContent = greeting + ", es ist " + hours + ":" + minutes + ":" + seconds;

  }

  updateClock(); // direkt aufrufen
  setInterval(updateClock, 1000); // jede Sekunde aktualisieren
}

document.addEventListener("DOMContentLoaded", startLiveClock);
