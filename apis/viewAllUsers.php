<?php
header("Content-Type: application/json");
require_once("connection.php");
$sql="SELECT id,first_name,last_name,username,profile_url,created_at FROM users";
$statement = $pdo->query($sql);

    $users = $statement->fetchAll();

    http_response_code(200);
    echo json_encode([
        "result"=>"done",
        "users"=>$users
    ]);
?>