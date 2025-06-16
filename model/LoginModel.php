<?php
require_once "db_verbindung.php";
require_once 'userFunctions.php';

class LoginModel
{
    public function checkLogin($username, $password)
    {

        global $conn;

        $sql = "SELECT user_id, username, password FROM user WHERE username = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user["password"])) {
                $response = [
                    "user_id" => $user["user_id"],
                    "username" => $user["username"],
                ];
            } else {
                $response = ["error" => "Falsches Passwort !"];
            }
        } else {
            $response = ["error" => "Benutzername existiert leider nicht, versuche einen anderen :("];
        }

        $stmt->close();

        return $response;
    }


    //frägt die benutzerinformationen aus der datenbank durch die benutzer id ab
    public function getUserData($userId){
        return getUserData($GLOBALS['conn'],$userId);
    }
}