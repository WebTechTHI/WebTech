//LAURIN SCHNIZER


document.addEventListener('DOMContentLoaded', () => {
    const wishlistBtn = document.querySelector('.wishlist-btn');

    // Funktion für Meldung im Block
    function showMessage(type, message) {
        const block = document.getElementById('meldung-block');
        block.innerHTML = `<div class="meldung-container ${type}">${message}</div>`;
    }

    if (wishlistBtn) {          //Falls der Button gefunden wurde, hängen wir eine Click-Funktion dran
        wishlistBtn.addEventListener('click', () => {
            const pid = wishlistBtn.dataset.id;



            // Wishlist aus Cookie lesen


            //Array vorbereiten (is standartmäßig leer)
            let wishlist = [];

            //im DOM, document.cookie nach eintrag wishlist= suchen 
            const cookie = document.cookie.split('; ').find(row => row.startsWith('wishlist='));

            //Wenn cookie existiert -> den wert (%34sf%sdfv&... usw.) mit decode URI den encodeden Zeichen wieder normal machen (1,2,3... etc.)
            if (cookie) {

                //Json parse --> String in JavaScript-Array umwandeln
                wishlist = JSON.parse(decodeURIComponent(cookie.split('=')[1]));
            }

            // Prüfen ob schon drin
            if (!wishlist.includes(pid)) {
                wishlist.push(pid);

                //Json stringify: wandeln wir Array in String um und Kodieren Sonderzeichen für cookies mit path
                document.cookie = `wishlist=${encodeURIComponent(JSON.stringify(wishlist))}; path=/; max-age=${60 * 60 * 24 * 365}`;
                showMessage('meldung-erfolg', 'Produkt wurde zur Wunschliste hinzugefügt.');
            } else {
                showMessage('meldung-fehler', 'Produkt ist bereits in der Wunschliste.');
            }
        });
    }
});

//LAURIN SCHNIZER ENDE

