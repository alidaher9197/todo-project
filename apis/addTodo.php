<?php
header("Content-Type: application/json");
require_once("connection.php");

$array=json_decode(file_get_contents("php://input"),true);
if (!is_array($array)) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Invalid JSON"
    ]);
    exit;
}
foreach ($array as $key => $value) {
    if (is_string($value)) {
        $array[$key] = trim($value);
    }
}
if (!empty($array["title"]) && 
!empty($array["description"]) && 
isset($array["is_done"]) && 
!empty($array["image_url"]) &&
 !empty($array["user_id"])){

$sql="INSERT INTO todos 
        ( title, `description`, is_done,`image_url`, `user_id`) VALUES 
        ( :title, :description, :is_done,:image_url, :user_id);";
$statment=$pdo->prepare($sql);
try{
    $statment->execute([
        ":title"=>$array["title"],
        ":description"=>$array["description"],
        ":is_done"=>$array["is_done"],
        ":image_url"=>$array["image_url"],
        ":user_id"=>$array["user_id"]
        
]);
http_response_code(201);
    echo json_encode([
        "message" => "Todo created successfully",
        "todo_id"=>$pdo->lastInsertId()       
    ]);
}
catch(PDOException $e){
    
    http_response_code(500);
    echo json_encode([
        "result"=>"error",
        "error"=>"Database error"       
    ]);
    
}
}else{
    http_response_code(400);
    echo json_encode([
        "result"=>"error",
        "error"=>"Missing or invalid fields"       
    ]);
}

?>