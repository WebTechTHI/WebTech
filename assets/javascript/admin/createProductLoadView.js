
//Abwarten bis dom geladen ist, sonst werden dieelemente nicht gefunden
document.addEventListener("DOMContentLoaded", function () {

//asynchrones Laden von unterkategorien und entsprechenden Komponenten bei änderung der Hauptkategorie 
    document.getElementById("category").addEventListener("change", function () {
      const category = this.value;

      //Laden von unterkategorien in select-Auswahl
      fetch("/view/admin/createProduct/SubcategoryLoader.php?category=" + category)
        .then(response => response.text())
        .then(data => {
          document.getElementById("subcategory-container").innerHTML = data;
        });

      //dynamisches Laden von Komponenten zur Auswahl
      fetch("/view/admin/createProduct/ComponentLoader.php?category=" + category)
        .then(response => response.text())
        .then(data => {
          document.getElementById("component-container").innerHTML = data;
        });
    });
});