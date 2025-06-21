<?php
// Lädt das Registrierungs-Model
require_once "model/RegistrationModel.php";

class RegistrationController
{
    public function handleRequest()
    {


        $fehlermeldung = null;
        $erfolgsmeldung = null;

        // Prüft ob Formular abgeschickt wurde
        if ($_SERVER["REQUEST_METHOD"] === "POST") {

            // ==> Validierung in JavaScript
            // Holt Benutzereingaben
            $username = trim(string: $_POST["fromusernameregistration"]);
            $password = $_POST["fromuserpasswordregistration"];


            // Ruft Model auf: registriert User
            $model = new RegistrationModel();
            $result = $model->registerUser($username, $password);

            // Fehler oder Erfolg speichern (Was wir vom Model zurück bekommen und auf Variable result gespeichert wurde also fehlermeldung oder erfolgsmeldung)
            if (isset($result["error"])) {
                // Model meldet Fehler --> speichere ihn für die View
                $fehlermeldung = $result["error"];


            } else {
                // Model meldet Erfolg --> User-Daten laden & Session setzen (Speichert User- Daten in Session)
                $_SESSION['user'] = $model->getUserData($result["success"]); // zum setzen der benutzerinformationen im 'user'-array der session
                $erfolgsmeldung = "Registrierung hat geklappt! Benutzer ID ist:  " . $result['success'];
            }
        }
        // Zeigt die View an wenn fertig
        require "view/RegistrationView.php";
    }
}