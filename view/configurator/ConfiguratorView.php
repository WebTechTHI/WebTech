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

        <h1 class="headline">PC-KONFIGURATOR</h1>
        <div class="subtitle-card">
            <p class="subtitle">Ob leistungsintensives Gaming oder effizientes Arbeiten:<br>
                Mit unserem Konfigurator bauen Sie Ihr System nach Ihren Anforderungen.<br>
                Die PCs werden individuell montiert, getestet und startklar geliefert.</p>
        </div>

        <!-- Forumular zum Konfigurieren eines Pcs aus nutzersicht -->
        <form id="configure-form" class="configure-form" method="post"
            action="/index.php?page=configurator&action=configurationSubmit" enctype="multipart/form-data">


            <!-- Connector-Auswahl -->
            <div class="select-wrapper">
                <label for="connector">Anschlusstyp:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="connector" id="connector"
                        onchange="updatePriceTag('connector', 'connector-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($connectors as $connector): ?>
                            <option value="<?= $connector["connectors_id"] ?>" data-price="<?= $connector['price'] ?>">
                                <?= htmlspecialchars($connector["spec"]) ?>
                            </option>
                        <?php endforeach; ?>


                    </select>
                    <div class="price-container" id="connector-price-container" style="background-color:transparent;">
                    </div>
                </div>
            </div>



            <!-- Cpu-Auswahl -->
            <div class="select-wrapper">
                <label for="cpu">Cpu:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="cpu" id="cpu"
                        onchange="updatePriceTag('cpu', 'cpu-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($cpus as $cpu): ?>
                            <option value="<?= $cpu["cpu_id"] ?>" data-price="<?= $cpu['price'] ?>">
                                <?= htmlspecialchars($cpu["model"] . ", " . $cpu["cores"] . " cores, " . $cpu["base_clock_ghz"] . " GHz") ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="cpu-price-container" style="background-color:transparent;"></div>
                </div>
            </div>



            <!-- storage-Auswahl -->
            <div class="select-wrapper">
                <label for="storage">Speicher:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="storage" id="storage"
                        onchange="updatePriceTag('storage', 'storage-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($storages as $storage): ?>
                            <option value="<?= $storage["storage_id"] ?>" data-price="<?= $storage['price'] ?>">
                                <?= htmlspecialchars($storage["brand"] . " " . $storage["capacity_gb"] . "Gb, " . $storage["storage_type"]) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="storage-price-container" style="background-color:transparent;">
                    </div>
                </div>
            </div>



            <!-- gpu-Auswahl -->
            <div class="select-wrapper">
                <label for="gpu">Gpu:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="gpu" id="gpu"
                        onchange="updatePriceTag('gpu', 'gpu-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($gpus as $gpu): ?>
                            <option value="<?= $gpu["gpu_id"] ?>" data-price="<?= $gpu['price'] ?>">
                                <?= htmlspecialchars($gpu["brand"] . " " . $gpu["model"] . ", " . $gpu["vram_gb"] . "Gb") ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="gpu-price-container" style="background-color:transparent;"></div>
                </div>
            </div>



            <!-- Operating-System-Auswahl -->
            <div class="select-wrapper">
                <label for="os">Betriebssystem:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="os" id="os"
                        onchange="updatePriceTag('os', 'os-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($operatingSystems as $os): ?>
                            <option value="<?= $os["os_id"] ?>" data-price="<?= $os['price'] ?>">
                                <?= htmlspecialchars($os["name"]) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="os-price-container" style="background-color:transparent;"></div>
                </div>
            </div>



            <!-- Ram-Auswahl -->
            <div class="select-wrapper">
                <label for="ram">Ram:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="ram" id="ram"
                        onchange="updatePriceTag('ram', 'ram-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($rams as $ram): ?>
                            <option value="<?= $ram["ram_id"] ?>" data-price="<?= $ram['price'] ?>">
                                <?= htmlspecialchars($ram["brand"] . " " . $ram["model"] . ", " . $ram["capacity_gb"] . "Gb " . $ram["ram_type"]) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="ram-price-container" style="background-color:transparent;"></div>
                </div>
            </div>



            <!-- Netzwerkmodul-Auswahl -->
            <div class="select-wrapper">
                <label for="network">Netzwerkmodule:</label>
                <div class="component-wrapper">

                    <select class="component-select" name="network" id="network"
                        onchange="updatePriceTag('network', 'network-price-container')">

                        <option value="" data-price="">- keine -</option>

                        <?php foreach ($networks as $network): ?>
                            <option value="<?= $network["network_id"] ?>" data-price="<?= $network['price'] ?>">
                                <?= htmlspecialchars($network["spec"]) . "  |  " . $network["price"] . '€' ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                    <div class="price-container" id="network-price-container" style="background-color:transparent;">
                    </div>
                </div>
            </div>

            <div class="total-container">
                <div class="price-tag">Gesamtpreis:</div>
                <div class="total-amount" id="total-amount">0€</div>
            </div>


            <input id="submit-button" type="submit" value="Anfrage senden">
        </form>




    </div>

    <?php include './components/footer.php' ?>

</body>

</html>