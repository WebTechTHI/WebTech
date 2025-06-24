<?php
$category = $_GET['category'] ?? '';

$options = '';

switch ($category) {
    case 'accesories':
        $options .= '<option value="6">Maus</option>';
        $options .= '<option value="7">Tastatur</option>';
        $options .= '<option value="5">Monitor</option>';
        break;

    case 'desktop':
        $options .= '<option value="2">Gaming-PC</option>';
        $options .= '<option value="1">Office-PC</option>';
        break;

    case 'laptop':
        $options .= '<option value="3">Gaming-Laptop</option>';
        $options .= '<option value="4">Office-Laptop</option>';
        break;

    default:
        $options .= '<option value="">Bitte Kategorie wählen</option>';
        break;
}

echo '<select name="subcategory" id="subcategory">' . $options . '</select>';
