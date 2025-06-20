document.addEventListener('DOMContentLoaded', () => {
  const cartContainer = document.querySelector('.cart-container');
  if (!cartContainer) return;

  // Helper: neue Summen berechnen und eintragen
  function recalcSummary() {
    let total = 0;
    document.querySelectorAll('.cart-item').forEach(itemEl => {
      const price = parseFloat(itemEl.querySelector('.price').textContent.replace('.', '').replace(',', '.'));
      const qty   = parseInt(itemEl.querySelector('.quantity-display').value);
      const line  = price * qty;
      total += line;
      itemEl.querySelector('.item-total').textContent = line.toFixed(2).replace('.', ',') + ' €';
    });

    const netto = total / 1.19;
    document.querySelector('.summary-row .summary-value').textContent = netto.toFixed(2).replace('.', ',') + ' €';

    // Versand
    const shipEl = document.querySelectorAll('.summary-row .summary-value')[1];
    if (total > 29.99) {
      shipEl.textContent = 'Kostenlos';
    } else {
      shipEl.textContent = '4,99 €';
      total += 4.99;
    }

    // MwSt.
    const mwst = total * 0.19;
    document.querySelectorAll('.summary-row .summary-value')[2].textContent = mwst.toFixed(2).replace('.', ',') + ' €';

    // Gesamt
    document.querySelector('.summary-row.total .summary-value').textContent = total.toFixed(2).replace('.', ',') + ' €';
  }

  // Event-Handler für +/- Buttons
   cartContainer.querySelectorAll('.qty-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const itemEl = btn.closest('.cart-item');
      const pid    = itemEl.dataset.productId;
      const action = btn.dataset.action;
      const input  = itemEl.querySelector('.quantity-display');
      let currentQty = parseInt(input.value);
      let delta = action === 'increase' ? 1 : -1;

      if (currentQty + delta < 1) return; // Nicht unter 1 gehen lassen

      // Cookie updaten durch Senden der Mengenänderung (+1 oder -1)
      fetch('/api/addToCartCookie.php', { // <-- Geändert & Korrigiert
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
          product_id: pid,
          quantity: delta // Sende die Änderung, nicht die absolute Menge
        })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          // UI aktualisieren
          input.value = currentQty + delta;
          recalcSummary();
        }
      });
    });
  });

  // Event-Handler für Entfernen-Button
   cartContainer.querySelectorAll('.remove-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const itemEl = btn.closest('.cart-item');
      const pid    = itemEl.dataset.productId;

      fetch('/api/removeFromCartCookie.php', { // <-- Geändert
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ product_id: pid })
      })
      .then(res => res.json())
      .then(data => {
        if (data.status === 'success') {
          itemEl.remove();
          recalcSummary();
          if (document.querySelectorAll('.cart-item').length === 0) {
            document.querySelector('.cart-items').innerHTML = '<p>Dein Warenkorb ist leer.</p>';
            document.querySelector('.cart-summary').remove();
          }
        }
      });
    });
  });
  
  // Initiale Berechnung
  recalcSummary();
});
