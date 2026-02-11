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

$user_id=require_auth($pdo);
required_fields($_POST,"todo_id");
$todo=Todos::view_todo_by_id($pdo,$_POST["todo_id"]);
if($todo["user_id"]==$user_id){
    $params=[];
if(isset($_POST["title"]) &&  trim($_POST["title"]) !== ""){
$params["title"]=$_POST["title"];
}
if(isset($_POST["description"]) &&  trim($_POST["description"]) !== ""){
$params["description"]=$_POST["description"];
}
if(isset($_FILES["image"]) ){
    
$image_path_db=add_image($_FILES["image"]);
delete_image($todo["image_url"]);
$params["image_url"]=$image_path_db;
}
if (empty($params)) {
    http_response_code(400);
    echo json_encode(["error" => "Nothing to update"]);
    exit;
}
$set = [];
foreach ($params as $key => $value) {
    $set[] = "$key = :$key"; // creates strings like "title = :title"
}
$sql = "UPDATE todos SET " . implode(", ", $set) . " WHERE id = :id ";
$params[":id"] = $_POST["todo_id"];
$stmt = $pdo->prepare($sql);
try{$stmt->execute($params);
if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode(["error" => "Todo not found or no changes"]);
    exit;
}
$response = ["result" => "success", "todo" => $_POST["todo_id"],"token" => MyJWT::generateToken($user_id)];

if (isset($params["title"])) $response["new_title"] = $params["title"];
if (isset($params["description"])) $response["new_description"] = $params["description"];
if (isset($params["image_url"])) $response["new_image_url"] = $params["image_url"];

echo json_encode($response);}
catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}
}else{
    http_response_code(403);
        echo json_encode([
        "result"=>"error",
        "message"=>"access denied"
    ]);
}

?>