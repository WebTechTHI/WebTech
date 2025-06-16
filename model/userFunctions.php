<?php
function getUserData($conn, $userId)
{
    $sql = "SELECT user_id, username, richtiger_name, land, stadt, email FROM user WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId); // "i" steht für Integer
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $stmt->close();

    return $data;
}
