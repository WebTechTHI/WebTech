// RINOR STUBLLA 

document.addEventListener('DOMContentLoaded', () => {
    // Elemente holen
    const banner = document.getElementById('cookie-consent-banner');
    const acceptBtn = document.getElementById('btn-accept-cookies');
    const declineBtn = document.getElementById('btn-decline-cookies');
    const manageSettingsBtn = document.getElementById('manage-cookie-settings'); 

    // Funktion zum Setzen des Consent-Cookies
    const setConsentCookie = (value) => {
        const expiryDate = new Date();
        expiryDate.setFullYear(expiryDate.getFullYear() + 1); // 1 Jahr gültig
        document.cookie = `cookie_consent=${value}; path=/; expires=${expiryDate.toUTCString()}; SameSite=Lax`;
    };

    // Event Listener nur hinzufügen, wenn die Buttons wirklich da sind
    acceptBtn?.addEventListener('click', () => {
        setConsentCookie('accepted');
        if(banner) banner.style.display = 'none';
        // Seite neu laden, damit alles sofort funktioniert
        window.location.reload(); 
    });

    declineBtn?.addEventListener('click', () => {
        setConsentCookie('declined');
        if(banner) banner.style.display = 'none';
        window.location.reload(); 
        document.cookie = 'mlr_cart=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    });

    // Event Listener für den Footer-Link
    manageSettingsBtn?.addEventListener('click', (e) => {
        e.preventDefault();
        // Zeigt das Banner wieder an.
        if (banner) {
             banner.style.display = 'block';
        }
    });
});

// RINOR STUBLLA ENDE
