//RINOR STUBLLA

document.addEventListener('DOMContentLoaded', function() {
    // --- Elemente holen ---
    const couponBtn = document.getElementById('coupon-btn');
    const couponInput = document.getElementById('coupon-input');
    const couponMessage = document.getElementById('coupon-message');
    const appliedCouponInput = document.getElementById('applied-coupon-code');

    // Elemente für die Preisanzeige
    const originalTotalSpan = document.getElementById('original-total-amount');
    const originalTotalValue = parseFloat(originalTotalSpan.getAttribute('data-original-total'));
    
    const discountRow = document.getElementById('discount-row');
    const discountLabelSpan = document.getElementById('discount-label');
    const discountAmountSpan = document.getElementById('discount-amount');
    
    const newTotalRow = document.getElementById('new-total-row');
    const newTotalAmountSpan = document.getElementById('new-total-amount');
    
    // --- Event Listener für den Button ---
    couponBtn.addEventListener('click', async function(event) {
        event.preventDefault(); 
        const code = couponInput.value.trim().toUpperCase(); // Umwandlung in Großbuchstaben ist oft sinnvoll

        if (code === '') {
            couponMessage.textContent = 'Bitte einen Code eingeben.';
            couponMessage.style.color = 'red';
            return;
        }

        try {
            const response = await fetch('/model/ajax_validate_coupon.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ code: code })
            });

            const data = await response.json();

            if (data.success) {
                // --- ERFOLG: Gutschein ist gültig ---
                couponMessage.textContent = data.message;
                couponMessage.style.color = 'green';

                // Berechnungen durchführen
                const discountPercent = data.percent;
                const discountValue = originalTotalValue * (discountPercent / 100);
                const newTotal = originalTotalValue - discountValue;

                // Anzeige aktualisieren - genau nach deinem Wunsch
                discountLabelSpan.textContent = `Rabatt (${data.code} -${discountPercent}%)`;
                discountAmountSpan.textContent = `- ${discountValue.toFixed(2).replace('.', ',')} €`;
                newTotalAmountSpan.textContent = `${newTotal.toFixed(2).replace('.', ',')} €`;

                // Versteckte Zeilen sichtbar machen
                discountRow.style.display = 'flex'; // oder 'block', je nach CSS
                newTotalRow.style.display = 'flex';

                // Originalen Gesamtbetrag als "durchgestrichen" markieren (optional, aber schick)
                originalTotalSpan.style.textDecoration = 'line-through';
                originalTotalSpan.style.opacity = '0.7';

                // Gültigen Code im versteckten Feld speichern
                appliedCouponInput.value = data.code;

                // Eingabe blockieren, um Mehrfachanwendung zu verhindern
                couponInput.disabled = true;
                couponBtn.disabled = true;

            } else {
                // --- FEHLER: Gutschein ungültig ---
                couponMessage.textContent = data.message;
                couponMessage.style.color = 'red';
                appliedCouponInput.value = ''; 
            }

        } catch (error) {
            couponMessage.textContent = 'Ein technischer Fehler ist aufgetreten.';
            couponMessage.style.color = 'red';
            console.error('Fehler bei der Gutschein-Validierung:', error);
        }
    });
});