<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Bereich - Produktverwaltung</title>
  <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon" />

  <!-- Styling -->
  <link rel="stylesheet" href="/assets/css/colors.css" />
  <link rel="stylesheet" href="/assets/css/admin.css" />
</head>

<body>
  <?php $category = $_POST["category"] ?? ""; ?>

  <h1 style="text-align: center; color: var(--headline-color);">Produkte hochladen</h1>


  <!-- Forumular zum anlegen neuer Produkte -->
  <form class="admin-form" method="post" action="/controller/AdminController.php" enctype="multipart/form-data">

    <h2>Neues Produkt anlegen.</h2>


  <!-- Als erstes muss eine Kategorie ausgewählt werden, um anschließend die richtigen Untekategorien und Komponenten zu laden.-->
    <label for="category">Bitte wählen Sie eine Produktkategorie:</label>
    <select name="category" id="category">
      <option value="" disabled <?= $category == "" ? "selected" : "" ?>>Bitte Kategorie wählen</option>
      <option value="desktop" <?= $category == "desktop" ? "selected" : "" ?>>Desktop-PC</option>
      <option value="laptop" <?= $category == "laptop" ? "selected" : "" ?>>Laptop</option>
      <option value="accesories" <?= $category == "accesories" ? "selected" : "" ?>>Zubehör</option>
    </select>

    <!-- Unterkategorie wählen-->
    <label for="subcategory">Bitte wählen Sie die passende Unterkategorie:</label>
    <div id="subcategory-container">
      <select name="subcategory" id="subcategory" disabled>
        <option>Bitte zuerst eine Kategorie wählen</option>
      </select>
    </div>

    <!-- Produktbild hochladen-->
    <label for="image">Bild hochladen:</label>
    <input type="file" name="image" id="image" />

    <!-- Produktname eingeben-->
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" />

    <!-- Produktbeschreibung eingeben-->
    <label for="short-description">Kurzbeschreibung:</label>
    <input type="text" name="short-description" id="short-description" />

    <!-- Produktbeschreibung eingeben-->
    <label for="description">Beschreibung:</label>
    <input type="text" name="description" id="description" />


    <!-- container für dynamisches befüllen der Komponentenauswahl -->
    <div id="component-container"></div>



    <label for="price">Preis:</label>
    <input type="text" name="price" id="price" />

    <input type="submit" value="Absenden" />
  </form>

  <script>

    //asynchrones Laden von unterkategorien und entsprechenden Komponenten bei änderung der Hauptkategorie 
    document.getElementById("category").addEventListener("change", function () {
      const category = this.value;


      //Laden von unterkategorien
      fetch("/view/AdminComponents/AdminLoader/AdminSubcategoryLoader.php?category=" + category)
        .then(response => response.text())
        .then(data => {
          document.getElementById("subcategory-container").innerHTML = data;
        });

      //dynamisches Laden von Komponenten zur Auswahl
        fetch("/view/AdminComponents/AdminLoader/AdminComponentLoader.php?category=" + category)
    .then(response => response.text())
    .then(data => {
      document.getElementById("component-container").innerHTML = data;
    });
      });
  </script>
</body>

</html>