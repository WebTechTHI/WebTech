document.addEventListener('DOMContentLoaded', () => {
    const wishlistBtn = document.querySelector('.wishlist-btn');

    if (wishlistBtn) {
        wishlistBtn.addEventListener('click', () => {
            const pid = wishlistBtn.dataset.id;

            // Cookie lesen
            let wishlist = [];
            if (document.cookie.split('; ').find(row => row.startsWith('wishlist='))) {
                wishlist = JSON.parse(decodeURIComponent(
                    document.cookie.split('; ').find(row => row.startsWith('wishlist=')).split('=')[1]
                ));
            }

            // Prüfen ob schon drin
            if (!wishlist.includes(pid)) {
                wishlist.push(pid);
                document.cookie = `wishlist=${encodeURIComponent(JSON.stringify(wishlist))}; path=/; max-age=${60 * 60 * 24 * 365}`;
                alert('Produkt wurde zur Wunschliste hinzugefügt.');
            } else {
                alert('Produkt ist bereits auf der Wunschliste.');
            }
        });
    }
});
