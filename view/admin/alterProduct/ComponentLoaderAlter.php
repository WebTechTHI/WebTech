<?php

require_once __DIR__ . '/../../../model/AdminModel.php';

  //dekodiert produkt jason objekt aus javascript alterProductLooadView.js, 
  // also das $produkt aus alterproductview
  $jsInput = file_get_contents("php://input");
$objects = json_decode($jsInput, true);


$product = $objects["product"];
$category = $objects["category"];

$model = new AdminModel();

// alle Komponenten Laden (-> beim Neuanlegen von Produkten später auswählbar machen)
$displays = $model->getComponents('display');
$gpus = $model->getComponents('gpu');
$cpus = $model->getComponents('processor');
$rams = $model->getComponents('ram');
$networks = $model->getComponents('network');
$connectors = $model->getComponents('connectors');
$features = $model->getComponents('feature');
$operatingSystems = $model->getComponents('operating_system');
$storages = $model->getComponents('storage');


switch ($category) {
  case 'accesories': //3 ist category id von zubehör
    ?>



    <!-- Display-Auswahl -->
    <div class="select-wrapper">
      <label class="lablll" for="display">Display:</label>
      <div class="component-wrapper">

        <select name="display" id="display">

          <option value="">- keine -</option>

          <?php foreach ($displays as $display): ?>
            <option value="<?= $display["display_id"] ?>" <?= $display["display_id"] == $product['display_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($display["brand"] . " " . $display["size_inch"] . "\", " . $display["resolution"] . "p, " .
                $display["refresh_rate_hz"] . "Hz") ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- Connector-Auswahl -->
    <div class="select-wrapper">
      <label for="connector">Anschlusstyp:</label>
      <div class="component-wrapper">

        <select name="connector" id="connector">

          <option value="">- keine -</option>

          <?php foreach ($connectors as $connector): ?>
            <option value="<?= $connector["connectors_id"] ?>" <?= $connector["connectors_id"] == $product['connectors_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($connector["spec"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- zusätzliche-Features-Auswahl -->
    <div class="select-wrapper">
      <label for="feature">Features:</label>
      <div class="component-wrapper">

        <select name="feature" id="feature">

          <option value="">- keine -</option>

          <?php foreach ($features as $feature): ?>
            <option value="<?= $feature["feature_id"] ?>" <?= $feature["feature_id"] == $product['feature_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($feature["spec"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>

    <?php break;



case 'laptop':   //2 ist category id von laptop
   //displays werden zusätzlich von laptops benutzt ?>


    <!-- Display-Auswahl -->
    <div class="select-wrapper">
      <label for="display">Display:</label>
      <div class="component-wrapper">

        <select name="display" id="display">

          <option value="">- keine -</option>

          <?php foreach ($displays as $display): ?>
            <option value="<?= $display["display_id"] ?>" <?= $display["display_id"] == $product['display_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($display["brand"] . " " . $display["size_inch"] . "\", " . $display["resolution"] . "p, " .
                $display["refresh_rate_hz"] . "Hz") ?>
            </option>

          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>

    <?php



  case 'desktop':   //1 ist category id von pcs
  //Alles restliche wird geteilt von laptops und desktop pcs genutzt  ?>



    <!-- Connector-Auswahl -->
    <div class="select-wrapper">
      <label for="connector">Anschlusstyp:</label>
      <div class="component-wrapper">

        <select name="connector" id="connector">

          <option value="">- keine -</option>

          <?php foreach ($connectors as $connector): ?>
            <option value="<?= $connector["connectors_id"] ?>" <?= $connector["connectors_id"] == $product['connectors_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($connector["spec"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- Cpu-Auswahl -->
    <div class="select-wrapper">
      <label for="cpu">Cpu:</label>
      <div class="component-wrapper">

        <select name="cpu" id="cpu">

          <option value="">- keine -</option>

          <?php foreach ($cpus as $cpu): ?>
            <option value="<?= $cpu["cpu_id"] ?>" <?= $cpu["cpu_id"] == $product['cpu_id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($cpu["model"] . ", " . $cpu["cores"] . " cores, " . $cpu["base_clock_ghz"] . " GHz") ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- storage-Auswahl -->
    <div class="select-wrapper">
      <label for="storage">Storage:</label>
      <div class="component-wrapper">

        <select name="storage" id="storage">

          <option value="">- keine -</option>

          <?php foreach ($storages as $storage): ?>
            <option value="<?= $storage["storage_id"] ?>" <?= $storage["storage_id"] == $product['storage_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($storage["brand"] . " " . $storage["capacity_gb"] . "Gb, " . $storage["storage_type"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- gpu-Auswahl -->
    <div class="select-wrapper">
      <label for="gpu">Gpu:</label>
      <div class="component-wrapper">

        <select name="gpu" id="gpu">

          <option value="">- keine -</option>

          <?php foreach ($gpus as $gpu): ?>
            <option value="<?= $gpu["gpu_id"] ?>" <?= $gpu["gpu_id"] == $product['gpu_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($gpu["brand"] . " " . $gpu["model"] . ", " . $gpu["vram_gb"] . "Gb") ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- Operating-System-Auswahl -->
    <div class="select-wrapper">
      <label for="os">Betriebssystem:</label>
      <div class="component-wrapper">

        <select name="os" id="os">

          <option value="">- keine -</option>

          <?php foreach ($operatingSystems as $os): ?>
            <option value="<?= $os["os_id"] ?>" <?= $os["os_id"] == $product['os_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($os["name"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- Ram-Auswahl -->
    <div class="select-wrapper">
      <label for="ram">Ram:</label>
      <div class="component-wrapper">

        <select name="ram" id="ram">

          <option value="">- keine -</option>

          <?php foreach ($rams as $ram): ?>
            <option value="<?= $ram["ram_id"] ?>" <?= $ram["ram_id"] == $product['ram_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($ram["brand"] . " " . $ram["model"] . ", " . $ram["capacity_gb"] . "Gb " . $ram["ram_type"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- Netzwerkmodul-Auswahl -->
    <div class="select-wrapper">
      <label for="network">Netzwerkmodule:</label>
      <div class="component-wrapper">

        <select name="network" id="network">

          <option value="">- keine -</option>

          <?php foreach ($networks as $network): ?>
            <option value="<?= $network["network_id"] ?>" <?= $network["network_id"] == $product['network_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($network["spec"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <!-- zusätzliche-Features-Auswahl -->
    <div class="select-wrapper">
      <label for="feature">Features:</label>
      <div class="component-wrapper">

        <select name="feature" id="feature">

          <option value="">- keine -</option>

          <?php foreach ($features as $feature): ?>
            <option value="<?= $feature["feature_id"] ?>" <?= $feature["feature_id"] == $product['feature_id'] ? 'selected' : ''?>>
              <?= htmlspecialchars($feature["spec"]) ?>
            </option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <?php

    break;

}