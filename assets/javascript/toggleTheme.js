
function toggleTheme() {

    const isDark = document.body.classList.toggle('darkMode');

    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    document.getElementById('themeToggleBtn').src = isDark ? '/WebTech/assets/images/icons/lightmode-btn.png' : '/WebTech/assets/images/icons/darkmode-btn.png';
    document.getElementById('visaIconFooter').src = isDark ? '/WebTech/assets/images/icons/paymentMethods/visa-dark.svg' : '/WebTech/assets/images/icons/paymentMethods/visa-light.svg';
    document.getElementById('mastercardIconFooter').src = isDark ? '/WebTech/assets/images/icons/paymentMethods/mastercard-dark.svg' : '/WebTech/assets/images/icons/paymentMethods/mastercard-light.svg';
    document.getElementById('paypalIconFooter').src = isDark ? '/WebTech/assets/images/icons/paymentMethods/paypal-dark.svg' : '/WebTech/assets/images/icons/paymentMethods/paypal-light.svg';
    document.getElementById('bitcoinIconFooter').src = isDark ? '/WebTech/assets/images/icons/paymentMethods/bitcoin-dark.svg' : '/WebTech/assets/images/icons/paymentMethods/bitcoin-light.svg';
}

    // beim Laden der Seite das gespeicherte Theme anwenden
window.addEventListener('DOMContentLoaded', () => {

    /*  */
    const darkmodeBtn = document.getElementById('themeToggleBtn');

    /* Payment Icons */
    const visaIcon = document.getElementById('visaIconFooter');
    const mastercardIcon = document.getElementById('mastercardIconFooter');
    const paypalIcon = document.getElementById('paypalIconFooter');
    const klarnaIcon = document.getElementById('klarnaIconFooter');
    const bitocinIcon = document.getElementById('bitcoinIconFooter');

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('darkMode');
        darkmodeBtn.src = '/WebTech/assets/images/icons/lightmode-btn.png';
        visaIcon.src = '/WebTech/assets/images/icons/paymentMethods/visa-dark.svg';
        mastercardIcon.src = '/WebTech/assets/images/icons/paymentMethods/mastercard-dark.svg';
        paypalIcon.src = '/WebTech/assets/images/icons/paymentMethods/paypal-dark.svg';
        bitocinIcon.src = '/WebTech/assets/images/icons/paymentMethods/bitcoin-dark.svg';
    } else {
        darkmodeBtn.src = '/WebTech/assets/images/icons/darkmode-btn.png';
        visaIcon.src = '/WebTech/assets/images/icons/paymentMethods/visa-light.svg';
        mastercardIcon.src = '/WebTech/assets/images/icons/paymentMethods/mastercard-light.svg';
        paypalIcon.src = '/WebTech/assets/images/icons/paymentMethods/paypal-light.svg';
        bitocinIcon.src = '/WebTech/assets/images/icons/paymentMethods/bitcoin-light.svg';
    }
});