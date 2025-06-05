//Beim wechseln des modus werden die Quellen entsprechend neu besetzt    
function toggleTheme() {

    const isDark = document.body.classList.toggle('darkMode');

    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    document.getElementById('themeToggleBtn').src = isDark ? '/assets/images/icons/light-mode.svg' : '/assets/images/icons/dark-mode.svg';
    document.getElementById('themeToggleBtn').title = isDark ? 'light mode' : 'dark mode';
    darkmodeBtn.title = 'light mode';
    document.getElementById('visaIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/visa-dark.svg' : '/assets/images/icons/paymentMethods/visa-light.svg';
    document.getElementById('mastercardIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/mastercard-dark.svg' : '/assets/images/icons/paymentMethods/mastercard-light.svg';
    document.getElementById('paypalIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/paypal-dark.svg' : '/assets/images/icons/paymentMethods/paypal-light.svg';
    document.getElementById('bitcoinIconFooter').src = isDark ? '/assets/images/icons/paymentMethods/bitcoin-dark.svg' : '/assets/images/icons/paymentMethods/bitcoin-light.svg';
}

// beim Laden der Seite das gespeicherte Theme anwenden
window.addEventListener('DOMContentLoaded', () => {

    //Speichern von DOM-Elementen in Konstanten
    const darkmodeBtn = document.getElementById('themeToggleBtn');
    const visaIcon = document.getElementById('visaIconFooter');
    const mastercardIcon = document.getElementById('mastercardIconFooter');
    const paypalIcon = document.getElementById('paypalIconFooter');
    const bitocinIcon = document.getElementById('bitcoinIconFooter');


    //Pfade zu den Quelldatein
    const pathPayment = '/assets/images/icons/paymentMethods/';
    const pathThemeBtn = '/assets/images/icons/';

    //dark theme
    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('darkMode');

        darkmodeBtn.title = 'light mode';
        darkmodeBtn.src = pathThemeBtn + 'light-mode.svg';
        visaIcon.src = pathPayment + 'visa-dark.svg';
        mastercardIcon.src = pathPayment + 'mastercard-dark.svg';
        paypalIcon.src = pathPayment + 'paypal-dark.svg';
        bitocinIcon.src = pathPayment + 'bitcoin-dark.svg';
    } else {
        //light theme
        darkmodeBtn.title = 'dark mode';
        darkmodeBtn.src = pathThemeBtn + 'dark-mode.svg';
        visaIcon.src = pathPayment + 'visa-light.svg';
        mastercardIcon.src = pathPayment + 'mastercard-light.svg';
        paypalIcon.src = pathPayment + 'paypal-light.svg';
        bitocinIcon.src = pathPayment + 'bitcoin-light.svg';
    }
});