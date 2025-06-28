<?php
//MICHAEL PIETSCH
require_once __DIR__ . '/../../../model/AdminModel.php';

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


$category = $_GET['category'] ?? '';


switch ($category) {
  case 'accesories':
    ?>


    <!-- Display-Auswahl -->
    <div class="select-wrapper">
      <label class="lablll" for="display">Display:</label>
      <div class="component-wrapper">

        <select name="display" id="display">

          <option value="">- keine -</option>

          <?php foreach ($displays as $display): ?>
            <option value="<?= $display["display_id"] ?>">
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
            <option value="<?= $connector["connectors_id"] ?>"><?= htmlspecialchars($connector["spec"]) ?></option>
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
            <option value="<?= $feature["feature_id"] ?>"><?= htmlspecialchars($feature["spec"]) ?></option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>

    <?php break;



  case 'laptop':    //displays werden zusätzlich von laptops benutzt ?>


    <!-- Display-Auswahl -->
    <div class="select-wrapper">
      <label for="display">Display:</label>
      <div class="component-wrapper">

        <select name="display" id="display">

          <option value="">- keine -</option>

          <?php foreach ($displays as $display): ?>
            <option value="<?= $display["display_id"] ?>">
              <?= htmlspecialchars($display["brand"] . " " . $display["size_inch"] . "\", " . $display["resolution"] . "p, " .
                $display["refresh_rate_hz"] . "Hz") ?>
            </option>

          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>

    <?php



  case 'desktop':   //Alles restliche wird geteilt von laptops und desktop pcs genutzt  ?>



    <!-- Connector-Auswahl -->
    <div class="select-wrapper">
      <label for="connector">Anschlusstyp:</label>
      <div class="component-wrapper">

        <select name="connector" id="connector">

          <option value="">- keine -</option>

          <?php foreach ($connectors as $connector): ?>
            <option value="<?= $connector["connectors_id"] ?>"><?= htmlspecialchars($connector["spec"]) ?></option>
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
            <option value="<?= $cpu["cpu_id"] ?>">
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
            <option value="<?= $storage["storage_id"] ?>">
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
            <option value="<?= $gpu["gpu_id"] ?>">
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
            <option value="<?= $os["os_id"] ?>"><?= htmlspecialchars($os["name"]) ?></option>
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
            <option value="<?= $ram["ram_id"] ?>">
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
            <option value="<?= $network["network_id"] ?>"><?= htmlspecialchars($network["spec"]) ?></option>
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
            <option value="<?= $feature["feature_id"] ?>"><?= htmlspecialchars($feature["spec"]) ?></option>
          <?php endforeach; ?>

        </select>

        <button type="button">+</button>
      </div>
    </div>



    <?php

    break;

}