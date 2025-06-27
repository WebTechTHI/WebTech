document.addEventListener('DOMContentLoaded', () => {
    const removeBtns = document.querySelectorAll('.remove-from-wishlist-btn');

    removeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const pid = btn.dataset.id;

            // Hol Cookie
            let wishlist = [];
            if (document.cookie.split('; ').find(row => row.startsWith('wishlist='))) {
                wishlist = JSON.parse(decodeURIComponent(document.cookie.split('; ').find(row => row.startsWith('wishlist=')).split('=')[1]));
            }

            // Entferne das Produkt
            wishlist = wishlist.filter(id => id != pid);

            // Cookie neu setzen (1 Jahr)
            document.cookie = `wishlist=${encodeURIComponent(JSON.stringify(wishlist))}; path=/; max-age=${60 * 60 * 24 * 365}`;

            // Seite neu laden
            location.reload();
        });
    });

    //Wishlist.js ist für das entfernen des Produkts da aus dem cookie (hinzufügen auf Product view)
});
