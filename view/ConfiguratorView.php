<!-- MICHAEL PIETSCH -->
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Produkt anlegen</title>
    <link rel="icon" href="/assets/images/logo/favicon.png" type="image/x-icon">

    <!-- Script -->
    <script src="/assets/javascript/configurator.js"></script>

    <!-- Styling -->
    <link rel="stylesheet" href="/assets/css/colors.css">
    <link rel="stylesheet" href="/assets/css/configurator.css">
</head>

<body>

    <?php include './components/header.php' ?>


    <div class="page-wrapper">

    <h1 style="text-align: center; color: var(--headline-color);">PC KONFIGURIEREN</h1>


    <!-- Forumular zum Konfigurieren eines Pcs aus nutzersicht -->
    <form class="configure-form" method="post" action="/index.php?page=configurator&action=configurationSubmit"
        enctype="multipart/form-data">


        <!-- Connector-Auswahl -->
        <div class="select-wrapper">
            <label for="connector">Anschlusstyp:</label>
            <div class="component-wrapper">

                <select name="connector" id="connector">

                    <option value="">- keine -</option>

                    <?php foreach ($connectors as $connector): ?>
                        <option value="<?= $connector["connectors_id"] ?>"><?= htmlspecialchars($connector["spec"]) ?>
                        </option>
                    <?php endforeach; ?>

                    
                </select>
                <div class="price-container" id="connector-price-container">+359.99€</div>
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
            </div>
        </div>



        <!-- storage-Auswahl -->
        <div class="select-wrapper">
            <label for="storage">Speicher:</label>
            <div class="component-wrapper">

                <select name="storage" id="storage">

                    <option value="">- keine -</option>

                    <?php foreach ($storages as $storage): ?>
                        <option value="<?= $storage["storage_id"] ?>">
                            <?= htmlspecialchars($storage["brand"] . " " . $storage["capacity_gb"] . "Gb, " . $storage["storage_type"]) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
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
            </div>
        </div>



        <!-- Netzwerkmodul-Auswahl -->
        <div class="select-wrapper">
            <label for="network">Netzwerkmodule:</label>
            <div class="component-wrapper">

                <select name="network" id="network">

                    <option value="">- keine -</option>

                    <?php foreach ($networks as $network): ?>
                        <option value="<?= $network["network_id"] ?>">
                            <?= htmlspecialchars($network["spec"]) . "  |  " . $network["price"] . '€' ?></option>
                    <?php endforeach; ?>

                </select>
            </div>
        </div>


        <div>Preis:</div>



        <input type="submit" value="Anfrage senden">
    </form>




</div>

    <?php include './components/footer.php' ?>

</body>

</html>