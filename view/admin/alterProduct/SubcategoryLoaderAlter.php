<?php
$category = $_GET['category'] ?? '';

//lädt die per ajax gesendeten daten aus javascript
$jsInput = file_get_contents("php://input");
$objects = json_decode($jsInput, true);

$subcategory = $objects["subcategory"];


$options = '';

switch ($category) {
    case 'accesories':
        $options .= '<option value="6"' . ($subcategory == 6 ? 'selected' : '') . '>Maus</option>';
        $options .= '<option value="7"' . ($subcategory == 7 ? 'selected' : '') . '>Tastatur</option>';
        $options .= '<option value="5"' . ($subcategory == 5 ? 'selected' : '') . '>Monitor</option>';
        break;

    case 'desktop':
        $options .= '<option value="2"' . ($subcategory == 2 ? 'selected' : '') . '>Gaming-PC</option>';
        $options .= '<option value="1"' . ($subcategory == 1 ? 'selected' : '') . '>Office-PC</option>';
        break;

    case 'laptop':
        $options .= '<option value="3"' . ($subcategory == 3 ? 'selected' : '') . '>Gaming-Laptop</option>';
        $options .= '<option value="4"' . ($subcategory == 4 ? 'selected' : '') . '>Office-Laptop</option>';
        break;

    default:
        $options .= '<option value="">Bitte Kategorie wählen</option>';
        break;
}

echo '<select name="subcategory" id="subcategory">' . $options . '</select>';
