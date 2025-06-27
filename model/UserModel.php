<?php
//LAURIN SCHNITZER 

require_once "db_verbindung.php";

class UserModel
{

    //Ich habe getUserData einmal als allgemeine Helper-Funktion, um in jedem Model schnell auf Userdaten zugreifen zu können — z. B. beim Login und bei der Registrierung.
    //Zusätzlich gibt’s getUserData nochmal im UserModel — dort sauber als OOP-Methode, speziell für den Profilbereich.
    //So trenne ich allgemeine Helfer (userFunctions) von Models, die speziell nur für ein Feature sind


    public function getUserData($user_id)
    {

        global $conn;

        $sql = "SELECT username, password, richtiger_name, land, stadt, email FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($username, $password, $richtiger_name, $land, $stadt, $email);

        $defaultData = [];
        if ($stmt->fetch()) {
            $defaultData = [
                "username" => $username,
                "password" => $password,
                "richtiger_name" => $richtiger_name,
                "land" => $land,
                "stadt" => $stadt,
                "email" => $email
            ];
        }
        $stmt->close();
        return $defaultData;
    }

    // Speichert geänderte Userdaten in DB hier wenn Function aufgerufen wird
    public function updateUserData($user_id, $username, $hashedPassword, $richtiger_name, $land, $stadt, $email)
    {
        global $conn;

        $sql = "UPDATE user SET username = ?, password = ?, richtiger_name = ?, land = ?, stadt = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $username, $hashedPassword, $richtiger_name, $land, $stadt, $email, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }



    // Prüft, ob Username bei anderem User schon existiert
    public function usernameExists($username, $user_id)
    {

        global $conn;

        $sql = "SELECT user_id FROM user WHERE username = ? AND user_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $username, $user_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        return $exists;
    }


    // Holt aktuelles Passwort, falls User kein neues eingibt
    public function getCurrentPassword($user_id)
    {

        global $conn;

        $sql = "SELECT  password FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($password);
        $stmt->fetch();
        $stmt->close();
        return $password;
    }
}
//LAURIN SCHNITZER ENDE

