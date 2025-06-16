<html>

<head>

</head>

<body>
    <h1>Produkte hochladen</h1>

    <?php
$category = $_POST['category'] ?? '';
?>

<form method="post" action="uploadProduct.php" enctype="multipart/form-data">
    <div class="form-inner-wrapper">
        <h2>Zubehör hinzufügen --- die eingabefelder sind aktuell auf zubehör ausgelegt. <br>So fehlen z.b. komponenten wie cpu, gpu, .... die felder sollen dynamisch geladen werden, 
        <br>je nach ausgewählter kategorie</h2>

        <label for="category">Bitte wählen Sie eine Produktkategorie:</label>
        <select name="category" id="category" onchange="this.form.submit()">
            <option value="">-- Bitte wählen --</option>
            <option value="desktop" <?= $category == 'desktop' ? 'selected' : '' ?>>Desktop-PC</option>
            <option value="laptop" <?= $category == 'laptop' ? 'selected' : '' ?>>Laptop</option>
            <option value="accesories" <?= $category == 'accesories' ? 'selected' : '' ?>>Zubehör</option>
        </select>

        <label for="subcategory">Bitte wählen Sie die passende Unterkategorie:</label>
        <select name="subcategory" id="subcategory">
            <?php switch ($category): 
                case 'accesories': ?>
                    <option value="mouse">Maus</option>
                    <option value="keyboard">Tastatur</option>
                    <option value="monitor">Monitor</option>
                    <?php break;

                case 'desktop': ?>
                    <option value="desktop-gaming">Gaming-PC</option>
                    <option value="desktop-office">Office-PC</option>
                    <option value="none">Keine</option>
                    <?php break;

                case 'laptop': ?>
                    <option value="laptop-gaming">Gaming-Laptop</option>
                    <option value="laptop-office">Office-Laptop</option>
                    <option value="none">Keine</option>
                    <?php break;

                default: ?>
                    <option value="">Bitte zuerst eine Kategorie wählen</option>
            <?php endswitch; ?>
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
        <select name="display" id="display">

        <?php
        $displays = ['1','2'];
            foreach ($displays as $display){
                ?>

                <option value="$display"><?php echo $display; ?></option>

        <?php } ?>

        </select>

        <label for="connector">Anschlusstyp:</label>
        <input type="text" name="connector" id="connector">

        <label for="price">Preis:</label>
        <input type="text" name="price" id="price">

        <button type="submit">Absenden</button>
    </div>
</form>


    <style>
        body {
            background-image: linear-gradient(rgb(207, 207, 207), rgb(40, 40, 40));
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content:center;
            margin:0;
        }

        .form-inner-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
    </style>
</body>

</html>