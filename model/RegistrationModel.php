<?php
require_once 'db_verbindung.php';

class RegistrationModel
{
    public function registerUser($username, $password)
    {
        global $conn;


        //========= Hier noch prüfung ob es Benutzername schon in Datenbank gibt !! ===========
        $check_sql = "SELECT username FROM user WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {

            $result = ['error' => "Achtung! Dieser Benutzername ist leider schon vergeben :("];
        } else {

            //Passwort hashen (sicherheit!!!)PASSWORD_DEFAULT bedeutet: Nimm den sichersten Algorithmus, den PHP aktuell kennt
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            //SQL statement (prepared statement) 1. sql statement vorbereiten dann die strings einbinden
            $stmt = $conn->prepare("INSERT INTO user (username, password, richtiger_name, land, stadt, email) VALUES (?, ?, '', '', '', '')");
            $stmt->bind_param("ss", $username, $hashedPassword);

            if ($stmt->execute()) {
                $user_id = $stmt->insert_id;
                $result = ['success' => $user_id];

            } else {
                $result = ['error' => "Fehler: " . $stmt->error];

            }

            $stmt->close();

        }

        $check_stmt->close();
        $conn->close();

        return $result;
    }

}