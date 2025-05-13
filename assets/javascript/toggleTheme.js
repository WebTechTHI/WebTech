
function toggleTheme() {

    const isDark = document.body.classList.toggle('darkMode');

    localStorage.setItem('theme', isDark ? 'dark' : 'light');

    document.getElementById('themeToggleBtn').src = isDark ? '/assets/images/icons/lightmode-btn.png' : '/assets/images/icons/darkmode-btn.png';
}

    // beim Laden der Seite das gespeicherte Theme anwenden
window.addEventListener('DOMContentLoaded', () => {

    const icon = document.getElementById('themeToggleBtn');

    if (localStorage.getItem('theme') === 'dark') {
        document.body.classList.add('darkMode');
        icon.src = '/assets/images/icons/lightmode-btn.png';
    } else {
        document.body.classList.remove('darkMode');
        icon.src = '/assets/images/icons/darkmode-btn.png';
    }
});