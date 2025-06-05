document.addEventListener("DOMContentLoaded", () => {
    // Array mit den Bildpfaden für den Hero-Bereich
    const heroImages = [
        '/assets/images/background/index-test-banner1.png',
        '/assets/images/background/index-test-banner.png',
    ];

    let currentImageIndex = 0;
    const heroImageElement = document.getElementById('heroImage');


    // Funktion zum Wechseln der Bilder
    function changeHeroImage() {
        // Bewege das aktuelle Bild nach links raus
        heroImageElement.style.transform = 'translateX(-100%)';

        setTimeout(() => {
            // Berechne den nächsten Index
            currentImageIndex = (currentImageIndex + 1) % heroImages.length;

            // Setze das neue Bild und platziere es sofort rechts außerhalb
            heroImageElement.style.transition = 'none';
            heroImageElement.style.backgroundImage = `url('${heroImages[currentImageIndex]}')`;
            heroImageElement.style.transform = 'translateX(100%)';

            setTimeout(() => {
                // Aktiviere Transition wieder und bewege das Bild von rechts rein
                heroImageElement.style.transition = 'transform 1.3s ease-in-out';
                heroImageElement.style.transform = 'translateX(0)';
            }, 20);
        }, 1000);
    }


    // Starte den automatischen Bildwechsel alle 9 Sekunden
    setInterval(changeHeroImage, 9000);
});
