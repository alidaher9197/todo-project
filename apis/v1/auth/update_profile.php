<?php
header("Content-Type: application/json");
require_once("../helpers/connection.php");
require_once("../helpers/hash_password/password.php");
require_once ("../../../jwt.php");
require_once("../helpers/functions.php");
require_once("../helpers/Users.php");
$headers = getallheaders();
$user_id=require_auth($pdo);
$params=[];
if (isset($_POST["username"]) && trim($_POST["username"]) !== "") {
            $params["username"]=$_POST["username"];
            
        }
if(isset($_FILES["image"]) ){
$image_path_db=add_image($_FILES["image"]);
$params["profile_url"]=$image_path_db;
$user=Users::select_user_by_id($pdo,$user_id);
delete_image($user["profile_url"]);
    
    
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
$sql = "UPDATE users SET " . implode(", ", $set) . " WHERE id = :id ";
$params[":id"] = $user_id;
$stmt = $pdo->prepare($sql);
try{$stmt->execute($params);
if ($stmt->rowCount() === 0) {
    http_response_code(404);
    echo json_encode(["error" => "user not found or no changes"]);
    exit;
}
$response = ["result" => "success", "user" => $user_id,"token" => MyJWT::generateToken($user_id)];

if (isset($params["username"])) $response["new_username"] = $params["username"];
if (isset($params["profile_url"])) $response["new_profile_url"] = $params["profile_url"];

echo json_encode($response);}
catch(PDOException $e){
     http_response_code(500);
       
    // Check if the error is a duplicate entry
    if ($e->getCode() == 23000) {
        // 23000 = integrity constraint violation (unique key)
        echo json_encode([
            "status" => "error",
            "message" => "Username already exists"
        ]);
    } else {
        // Other database errors
        echo json_encode([
            "status" => "error",
            "message" => $e->getMessage()
        ]);
    }
}
?>