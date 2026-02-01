<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");
require_once ("../../../jwt.php");
require_once("../helpers/connection.php");
require_once("../helpers/hash_password/password.php");
require_once("../helpers/functions.php");
require_once("../helpers/Todos.php");

$user_id=require_auth();
required_fields($_GET,"todo_id");
$todo=Todos::view_todo_by_id($pdo,$_GET["todo_id"]);
if($todo["user_id"]==$user_id){
Todos::delete_todo_by_id($pdo,$_GET["todo_id"]);    
}else{
    http_response_code(403);
        echo json_encode([
        "result"=>"error",
        "message"=>"access denied"
    ]);
}

?>