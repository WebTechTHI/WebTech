document.addEventListener('DOMContentLoaded', function () {

    function showMessage(type, message) {
        const block = document.getElementById('meldung-block');
        block.innerHTML = `<div class="meldung-container ${type}">${message}</div>`;
    }

    document.querySelectorAll('.move-to-cart-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const pid = this.dataset.id;

            fetch('/api/moveToCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'product_id=' + encodeURIComponent(pid)
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status === 'success') {
                    showMessage('meldung-erfolg', 'Produkt wurde in den Warenkorb verschoben.');
                    location.reload();
                } else {
                    showMessage('meldung-fehler', 'Fehler beim Verschieben.');
                }
            })
            .catch(function (error) {
                console.error('Fehler:', error);
                showMessage('meldung-fehler', 'Ein unerwarteter Fehler ist aufgetreten.');
            });
        });
    });

    const moveAllBtn = document.getElementById('move-all-to-cart-btn');
    if (moveAllBtn) {
        moveAllBtn.addEventListener('click', function () {
            fetch('/api/moveAllToCart.php', {
                method: 'POST'
            })
            .then(function (response) {
                return response.json();
            })
            .then(function (data) {
                if (data.status === 'success') {
                    showMessage('meldung-erfolg', 'Alle Produkte wurden in den Warenkorb verschoben.');
                    location.reload();
                } else if (data.status === 'empty') {
                    showMessage('meldung-fehler', 'Die Wunschliste ist leer.');
                } else {
                    showMessage('meldung-fehler', 'Fehler beim Verschieben.');
                }
            })
            .catch(function (error) {
                console.error('Fehler:', error);
                showMessage('meldung-fehler', 'Ein unerwarteter Fehler ist aufgetreten.');
            });
        });
    }

});
