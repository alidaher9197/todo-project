<?php
header("Content-Type: application/json");
require_once("connection.php");
$sql="SELECT todos.id,title,`description`,is_done,image_url,todos.created_at,todos.updated_at,`user_id`,username FROM todos LEFT JOIN users ON todos.user_id=users.id";
$statement = $pdo->query($sql);

    $users = $statement->fetchAll();

    http_response_code(200);
    echo json_encode([
        "result"=>"done",
        "users"=>$users
    ]);
?>