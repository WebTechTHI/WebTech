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

  <form class="admin-form" method="post" action="" enctype="multipart/form-data">

    <h2>Neues Produkt anlegen.</h2>

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

    <label for="image">Bild hochladen:</label>
    <input type="file" name="image" id="image" />

    <label for="name">Name:</label>
    <input type="text" name="name" id="name" />

    <label for="short-description">Kurzbeschreibung:</label>
    <input type="text" name="short-description" id="short-description" />

    <label for="description">Beschreibung:</label>
    <input type="text" name="description" id="description" />


    <div id="component-container"></div>



    <!-- Display-Auswahl -->
    <label for="display">Display:</label>
    <div class="component-wrapper">
      <select name="display" id="display">
        <?php foreach ($displays as $display): ?>
          <option value="<?= $display["display_id"] ?>">
            <?= htmlspecialchars($display["brand"] . " " . $display["size_inch"] . "\", " . $display["resolution"] . "p, " .
              $display["refresh_rate_hz"] . "Hz") ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- Connector-Auswahl -->
    <label for="connector">Anschlusstyp:</label>
    <div class="component-wrapper">
      <select name="connector" id="connector">
        <?php foreach ($connectors as $connector): ?>
          <option value="<?= $connector["connectors_id"] ?>"><?= htmlspecialchars($connector["spec"]) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- Cpu-Auswahl -->
    <label for="cpu">Cpu:</label>
    <div class="component-wrapper">
      <select name="cpu" id="cpu">
        <?php foreach ($cpus as $cpu): ?>
          <option value="<?= $cpu["cpu_id"] ?>">
            <?= htmlspecialchars($cpu["model"] . ", " . $cpu["cores"] . " cores, " . $cpu["base_clock_ghz"] . " GHz") ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- storage-Auswahl -->
    <label for="storage">Storage:</label>
    <div class="component-wrapper">
      <select name="storage" id="storage">
        <?php foreach ($storages as $storage): ?>
          <option value="<?= $storage["storage_id"] ?>">
            <?= htmlspecialchars($storage["brand"] . " " . $storage["capacity_gb"] . "Gb, " . $storage["storage_type"]) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- gpu-Auswahl -->
    <label for="gpu">Gpu:</label>
    <div class="component-wrapper">
      <select name="gpu" id="gpu">
        <?php foreach ($gpus as $gpu): ?>
          <option value="<?= $gpu["gpu_id"] ?>">
            <?= htmlspecialchars($gpu["brand"] . " " . $gpu["model"] . ", " . $gpu["vram_gb"] . "Gb") ?></option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- Operating-System-Auswahl -->
    <label for="os">Betriebssystem:</label>
    <div class="component-wrapper">
      <select name="os" id="os">
        <?php foreach ($operatingSystems as $os): ?>
          <option value="<?= $os["os_id"] ?>"><?= htmlspecialchars($os["name"]) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- Ram-Auswahl -->
    <label for="ram">Ram:</label>
    <div class="component-wrapper">
      <select name="ram" id="ram">
        <?php foreach ($rams as $ram): ?>
          <option value="<?= $ram["ram_id"] ?>">
            <?= htmlspecialchars($ram["brand"] . " " . $ram["model"] . ", " . $ram["capacity_gb"] . "Gb " . $ram["ram_type"]) ?>
          </option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- Netzwerkmodul-Auswahl -->
    <label for="network">Netzwerkmodule:</label>
    <div class="component-wrapper">
      <select name="network" id="network">
        <?php foreach ($networks as $network): ?>
          <option value="<?= $network["network_id"] ?>"><?= htmlspecialchars($network["spec"]) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>


    <!-- zusätzliche-Features-Auswahl -->
    <label for="feature">Features:</label>
    <div class="component-wrapper">
      <select name="feature" id="feature">
        <?php foreach ($features as $feature): ?>
          <option value="<?= $feature["feature_id"] ?>"><?= htmlspecialchars($feature["spec"]) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="button">+</button>
    </div>



    <label for="price">Preis:</label>
    <input type="text" name="price" id="price" />

    <input type="submit" value="Absenden" />
  </form>

  <script>

    //asynchrones Laden von unterkategorien und entsprechenden Komponenten bei änderung der Hauptkategorie 
    document.getElementById("category").addEventListener("change", function () {
      const category = this.value;


      //Laden von unterkategorien
      fetch("/view/AdminSubcategoryLoader.php?category=" + category)
        .then(response => response.text())
        .then(data => {
          document.getElementById("subcategory-container").innerHTML = data;
        });


      // Komponenten dynamisch laden
      fetch("/view/AdminComponentLoader.php?category=" + category)
        .then(response => response.text())
        .then(data => {
          document.getElementById("component-container").innerHTML = data;
        });
    });
  </script>
</body>

</html>