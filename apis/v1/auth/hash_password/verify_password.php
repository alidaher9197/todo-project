<?php
    require_once("password.php");

    // 2- How to verify password
    $body = json_decode(file_get_contents("php://input"),true);
    $password = $body["password"];
    $hashed_password = $body["hashed_password"];
    echo json_encode(
        [
            "result"=>Password::verifyPassword($password,$hashed_password)
        ]
    );
?>