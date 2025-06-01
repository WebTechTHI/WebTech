
function toggleTheme() {

    const isDark = document.body.classList.toggle('darkMode');

    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    document.getElementById('themeToggleBtn').src = isDark ? '/assets/images/icons/lightmode-btn.png' : '/assets/images/icons/darkmode-btn.png';
    document.getElementById('visaIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/visa-dark.svg' : '/assets/images/icons/paymentMethods/visa-light.svg';
    document.getElementById('mastercardIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/mastercard-dark.svg' : '/assets/images/icons/paymentMethods/mastercard-light.svg';
    document.getElementById('paypalIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/paypal-dark.svg' : '/assets/images/icons/paymentMethods/paypal-light.svg';
    document.getElementById('bitcoinIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/bitcoin-dark.svg' : '/assets/images/icons/paymentMethods/bitcoin-light.svg';
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
        darkmodeBtn.src = '/assets/images/icons/lightmode-btn.png';
        visaIcon.src = '/assets/images/icons/paymentMethods/visa-dark.svg';
        mastercardIcon.src = '/assets/images/icons/paymentMethods/mastercard-dark.svg';
        paypalIcon.src = '/assets/images/icons/paymentMethods/paypal-dark.svg';
        bitocinIcon.src = '/assets/images/icons/paymentMethods/bitcoin-dark.svg';
    } else {
        darkmodeBtn.src = '/assets/images/icons/darkmode-btn.png';
        visaIcon.src = '/assets/images/icons/paymentMethods/visa-light.svg';
        mastercardIcon.src = '/assets/images/icons/paymentMethods/mastercard-light.svg';
        paypalIcon.src = '/assets/images/icons/paymentMethods/paypal-light.svg';
        bitocinIcon.src = '/assets/images/icons/paymentMethods/bitcoin-light.svg';
    }
});