<?php
require_once "db_verbindung.php";
require_once 'userFunctions.php';

class LoginModel
{

    // Prüft Login-Daten von DB (Funktion wurde aus Controller aufgerufen)
    public function checkLogin($username, $password)
    {

        global $conn;

        // Hole User mit passendem Username
        $sql = "SELECT user_id, username, password FROM user WHERE username = ?";
        
        //Schutz vor SQL-Injection
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        // Wenn User existiert, prüfe Passwort
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {

                // Login korrekt --> Userdaten zurückgeben
                $response = [
                    "user_id" => $user["user_id"],
                    "username" => $user["username"],
                ];
            } else {
                // Passwort falsch
                $response = ["error" => "Falsches Passwort !"];
            }
        } else {
            // User nicht gefunden
            $response = ["error" => "Benutzername existiert leider nicht, versuche einen anderen :("];
        }

        $stmt->close();

        return $response;
    }


    //frägt die benutzerinformationen aus der datenbank durch die benutzer id ab
    // Holt alle User-Infos per ID
    public function getUserData($userId)
    {
        return getUserData($GLOBALS['conn'], $userId);
    }
}