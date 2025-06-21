document.addEventListener('DOMContentLoaded', function () {
  const wishlistBtn = document.querySelector('.wishlist-btn');

  function showMessage(type, message) {
    const block = document.getElementById('meldung-block');
    block.innerHTML = `<div class="meldung-container ${type}">${message}</div>`;
  }

  if (wishlistBtn) {
    wishlistBtn.addEventListener('click', function () {
      const pid = this.dataset.id;

      fetch('/api/addToWishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + encodeURIComponent(pid)
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          showMessage('meldung-erfolg', 'Produkt wurde zur Wunschliste hinzugefügt.');
        } else {
          showMessage('meldung-fehler', 'Fehler: ' + data.message);
        }
      })
      .catch(err => {
        console.error('Fehler:', err);
        showMessage('meldung-fehler', 'Ein unerwarteter Fehler ist aufgetreten.');
      });
    });
  }
});
