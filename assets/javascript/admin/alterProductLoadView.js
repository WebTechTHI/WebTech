
//Abwarten bis dom geladen ist, sonst werden dieelemente nicht gefunden
document.addEventListener("DOMContentLoaded", function () {

  //erstmalig alle komponenten laden wenn produkt editiert wird
  const category = this.getElementById('category').value;

  //Laden von unterkategorien in select-Auswahl
  fetch("/view/admin/alterProduct/SubcategoryLoader.php?category=" + category)
    .then(response => response.text())
    .then(data => {
      document.getElementById("subcategory-container").innerHTML = data;
    });

  //dynamisches Laden von Komponenten zur Auswahl
  fetch("/view/admin/alterProduct/ComponentLoader.php?category=" + category)
    .then(response => response.text())
    .then(data => {
      document.getElementById("component-container").innerHTML = data;
    });


  //gleiche funktionalität wie oben aber wird nur von änderung der hauptkategorie ausgelöst.
  //asynchrones Laden von unterkategorien und entsprechenden Komponenten bei änderung der Hauptkategorie 
  document.getElementById("category").addEventListener("change", function () {
    category = this.value;

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