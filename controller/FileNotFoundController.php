<?php
//MICHAEL PIETSCH

class FileNotFoundController {

    public function handleRequest() {
        require "view/FileNotFoundView.php";
        exit;
    }
}