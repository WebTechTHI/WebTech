//MICHAEL PIETSCH
//Abwarten bis dom geladen ist, sonst werden dieelemente nicht gefunden
document.addEventListener("DOMContentLoaded", function () {

  const category = document.getElementById('category').value;
  


  //Laden von unterkategorien in select-Auswahl
  fetch("/view/admin/alterProduct/SubcategoryLoaderAlter.php?category=" + category, {
      
        //schickt $subcategory als json objekt um es in subcategory auswahl verfügbar zu machen
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          subcategory
        })
          })
    .then(response => response.text())
    .then(data => {
      document.getElementById("subcategory-container").innerHTML = data;
    });



  //dynamisches Laden von Komponenten zur Auswahl
    fetch("/view/admin/alterProduct/ComponentLoaderAlter.php?category=" + category, {
      
        //schickt $product als json objekt um es in components verfügbar zu machen
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          category,
          product
        })
          })
          .then(response => response.text())
          .then(data => {
            document.getElementById("component-container").innerHTML = data;
      });




  //asynchrones Laden von unterkategorien und entsprechenden Komponenten bei änderung der Hauptkategorie 
  document.getElementById("category").addEventListener("change", function () {
    const category = this.value;

    //Laden von unterkategorien in select-Auswahl
    fetch("/view/admin/alterProduct/SubcategoryLoaderAlter.php?category=" + category, {
      
        //schickt $subcategory als json objekt um es in subcategory auswahl verfügbar zu machen
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          subcategory
        })
          })
    .then(response => response.text())
    .then(data => {
      document.getElementById("subcategory-container").innerHTML = data;
    });



    //dynamisches Laden von Komponenten zur Auswahl
    fetch("/view/admin/alterProduct/ComponentLoaderAlter.php?category=" + category, {

        //schickt $product als json objekt um es in components verfügbar zu machen
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          category,
          product
        })
          })
          .then(response => response.text())
          .then(data => {
            document.getElementById("component-container").innerHTML = data;
      });


  });
});

//MICHAEL PIETSCH ENDE

