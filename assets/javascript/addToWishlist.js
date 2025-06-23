document.addEventListener('DOMContentLoaded', () => {
    const wishlistBtn = document.querySelector('.wishlist-btn');

    // Funktion für Meldung im Block
    function showMessage(type, message) {
        const block = document.getElementById('meldung-block');
        block.innerHTML = `<div class="meldung-container ${type}">${message}</div>`;
    }

    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', () => {
            const pid = wishlistBtn.dataset.id;

            // Wishlist aus Cookie lesen
            let wishlist = [];
            const cookie = document.cookie.split('; ').find(row => row.startsWith('wishlist='));
            if (cookie) {
                wishlist = JSON.parse(decodeURIComponent(cookie.split('=')[1]));
            }

            // Prüfen ob schon drin
            if (!wishlist.includes(pid)) {
                wishlist.push(pid);
                document.cookie = `wishlist=${encodeURIComponent(JSON.stringify(wishlist))}; path=/; max-age=${60 * 60 * 24 * 365}`;
                showMessage('meldung-erfolg', 'Produkt wurde zur Wunschliste hinzugefügt.');
            } else {
                showMessage('meldung-fehler', 'Produkt ist bereits in der Wunschliste.');
            }
        });
    }
});
