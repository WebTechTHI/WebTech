
<?php

require_once "db_verbindung.php";

class UserModel {
    public function getUserData($user_id) {

        global $conn;

        $sql = "SELECT username, password, richtiger_name, land, stadt, email FROM user WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->bind_result($username, $password, $richtiger_name, $land, $stadt, $email);

        $defaultData = [];
        if ( $stmt->fetch() ) {
            $defaultData = [
                "username"=> $username,
                "password"=> $password,
                "richtiger_name"=> $richtiger_name,
                "land"=> $land,
                "stadt"=> $stadt,
                "email" => $email
            ];
    }
    $stmt->close();
    return $defaultData;
}

    public function updateUserData($user_id, $username, $hashedPassword, $richtiger_name, $land, $stadt, $email) {
        global $conn;

        $sql = "UPDATE user SET username = ?, password = ?, richtiger_name = ?, land = ?, stadt = ?, email = ? WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $user_id, $username, $hashedPassword, $richtiger_name, $land, $stadt, $email, $user_id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
        }

    

    public function usernameExists($username, $user_id) {

        global $conn;

        $sql = "SELECT user_id FROM user WHERE username = ? AND user_id != ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si",$username, $user_id);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows() > 0;
        $stmt->close();
        return $exists;
    }


    public function getCurrentPassword($user_id) {

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
