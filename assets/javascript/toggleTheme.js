
function toggleTheme() {

    // liefert true, falls Klasse darkMode noch nicht vorhanden
    const isDark = document.body.classList.toggle('darkMode');

    // aktuelles Thema im localStorage speichern
    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    //  icon ändern
    document.getElementById('themeToggleBtn').src = isDark ? '/assets/images/icons/lightmode-btn.png' : '/assets/images/icons/darkmode-btn.png';
}

    // beim Laden der Seite das gespeicherte Theme anwenden
window.addEventListener('DOMContentLoaded', () => {

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('darkMode');
    } else {
        document.body.classList.remove('darkMode');
    }
});