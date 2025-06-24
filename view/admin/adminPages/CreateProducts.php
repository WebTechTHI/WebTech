<!DOCTYPE html>
<html lang="de">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Produkt anlegen</title>
  <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

  <!-- Script -->
  <script src="/assets/javascript/admin/loadComponentsSubcategories.js"></script>

  <!-- Styling -->
  <link rel="stylesheet" href="/assets/css/colors.css">
  <link rel="stylesheet" href="/assets/css/admin.css">
</head>

<body>
  <?php $category = $_POST["category"] ?? ""; ?>

  <div class="page-wrapper">

    <a class="back-last-page" href="/index.php?page=admin">Zurück</a>

    <h1 style="text-align: center; color: var(--headline-color);">Produkt erstellen</h1>


    <!-- Forumular zum anlegen neuer Produkte -->
    <form class="admin-form" method="post" action="/index.php?page=admin&action=uploadSubmit"
      enctype="multipart/form-data">

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
      <input type="file" name="images[]" id="image" multiple>

      <!-- Produktname eingeben-->
      <label for="name">Name:</label>
      <input type="text" name="name" id="name">

      <!-- Produktbeschreibung eingeben-->
      <label for="short-description">Kurzbeschreibung:</label>
      <input type="text" name="short-description" id="short-description">

      <!-- Produktbeschreibung eingeben-->
      <label for="description">Beschreibung:</label>
      <input type="text" name="description" id="description">


      <!-- container für dynamisches befüllen der Komponentenauswahl -->
      <div id="component-container"></div>


      <!-- Preis in Euro-->
      <label for="price">Preis:</label>
      <input type="text" name="price" id="price">


      <!-- Sale an oder aus -->
      <label for="sale">Sale</label>
      <select name="sale" id="sale">
        <option value="0" selected>Standardpreis</option>
        <option value="1">Im Angebot</option>
      </select>



      <input type="submit" value="Speichern">
    </form>

  </div>


</body>

</html>