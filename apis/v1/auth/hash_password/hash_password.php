<?php
    require_once("password.php");

    // 1- How to hash_password
    $body = json_decode(file_get_contents("php://input"),true);
    $password = $body["password"];
    
    echo json_encode(
        [
            "result"=>"done",
            "hashed_password"=>Password::hashPassword($password)
        ]
    );
?>