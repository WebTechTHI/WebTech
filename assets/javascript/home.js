//Skript zum verschieben horizontalen scrollen des Bestseller Produkt-Karusells auf der Startseite
document.addEventListener('DOMContentLoaded', function () {              //-----------------------
    const container = document.getElementById('product-container');              //-----------------------
    const scrollAmount = 1; // Anzahl der Items, die gescrollt werden scrollen          
    const itemWidth = document.querySelector('.product').offsetWidth + 15; // Breite + Gap              //-----------------------

    document.getElementById('scroll-left').addEventListener('click', () => {              //-----------------------
        container.scrollBy({ left: -itemWidth * scrollAmount, behavior: 'smooth' });              //-----------------------
    });

    document.getElementById('scroll-right').addEventListener('click', () => {              //-----------------------
        container.scrollBy({ left: itemWidth * scrollAmount, behavior: 'smooth' });              //-----------------------
    });
});