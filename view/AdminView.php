<html>

<body>

<?php
$category = $_POST['category'] ?? '';
?>

    <h1>Produkte hochladen</h1>

    <form method="post" action="" enctype="multipart/form-data">

        <h2>eingabefelder aktuell auf zubehör ausgelegt</h2>

        <label for="category">Bitte wählen Sie eine Produktkategorie:</label>
        <select name="category" id="category">
    <option value="" disabled <?= $category == '' ? 'selected' : '' ?>>Bitte Kategorie wählen</option>
    <option value="desktop" <?= $category == 'desktop' ? 'selected' : '' ?>>Desktop-PC</option>
    <option value="laptop" <?= $category == 'laptop' ? 'selected' : '' ?>>Laptop</option>
    <option value="accesories" <?= $category == 'accesories' ? 'selected' : '' ?>>Zubehör</option>
</select>


        <label for="subcategory">Bitte wählen Sie die passende Unterkategorie:</label>
        <div id="subcategory-container">
            <select name="subcategory" id="subcategory" disabled>
                <option>Bitte zuerst eine Kategorie wählen</option>
            </select>
        </div>

        
        </select>

        <label for="image">Bild hochladen:</label>
        <input type="file" name="image" id="image">

        <label for="name">Name:</label>
        <input type="text" name="name" id="name">

        <label for="short-description">Kurzbeschreibung:</label>
        <input type="text" name="short-description" id="short-description">

        <label for="desctiption">Beschreibung:</label>
        <input type="text" name="desctiption" id="desctiption">

        <label for="display">Display:</label>
        <div class="component-wrapper">
            <!-- wrapper klasse um das auswahlfeld und den "komponente-neu-anlegen"-button -->
            <select name="display" id="display">

                <?php
                foreach ($displays as $display) {
                    ?>
                    <option value="value="<?php echo $display['display_id']; ?>""><?php echo $display['Name']; ?></option>
                <?php } ?>

            </select>

            <button>+</button>
        </div>

        <label for="connector">Anschlusstyp:</label>
        <div class="component-wrapper">
            <!-- wrapper klasse um das auswahlfeld und den "komponente-neu-anlegen"-button -->
            <select name="connector" id="connector">

                <?php
                foreach ($connectors as $connector) {
                    ?>
                    <option value="<?php echo $connector['connectors_id']; ?>"><?php echo $connector['spec']; ?></option>
                <?php } ?>

            </select>

            <button>+</button>
        </div>

        <label for="price">Preis:</label>
        <input type="text" name="price" id="price">

        <button type="submit">Absenden</button>
    </form>


    <style>
        body {
            background-image: linear-gradient(rgb(207, 207, 207), rgb(40, 40, 40));
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .component-wrapper {
            display: flex;
            flex-direction: row;

        }

        input {
            width: 50vw;
        }
    </style>


    <script>
        document.getElementById("category").addEventListener("change", function () {
            const category = this.value;

            fetch("/view/AdminComponentLoader.php?category=" + category)
                .then(response => response.text())
                .then(data => {
                    document.getElementById("subcategory-container").innerHTML = data;
                });
        });
    </script>

</body>

</html>