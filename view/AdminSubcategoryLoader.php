<?php
$category = $_GET['category'] ?? '';

$options = '';

switch ($category) {
    case 'accesories':
        $options .= '<option value="mouse">Maus</option>';
        $options .= '<option value="keyboard">Tastatur</option>';
        $options .= '<option value="monitor">Monitor</option>';
        break;

    case 'desktop':
        $options .= '<option value="desktop-gaming">Gaming-PC</option>';
        $options .= '<option value="desktop-office">Office-PC</option>';
        $options .= '<option value="none">Keine</option>';
        break;

    case 'laptop':
        $options .= '<option value="laptop-gaming">Gaming-Laptop</option>';
        $options .= '<option value="laptop-office">Office-Laptop</option>';
        $options .= '<option value="none">Keine</option>';
        break;

    default:
        $options .= '<option value="">Bitte Kategorie wählen</option>';
        break;
}

echo '<select name="subcategory" id="subcategory">' . $options . '</select>';
