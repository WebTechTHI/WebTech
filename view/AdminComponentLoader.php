<?php
$category = $_GET['category'] ?? '';

switch ($category) {
    case 'accesories':
        ?>

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

    <?php  break;  


    case 'desktop':

        break;

    case 'laptop':

        break;

    default:

        break;
}