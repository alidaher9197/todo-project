<?php
// header("Access-Control-Allow-Origin: *");
// header("Access-Control-Allow-Methods: POST");
// header("Access-Control-Allow-Headers: Content-Type");
// header("Content-Type: application/json");
// require_once("connection.php");
// require_once("hash_password/password.php");
// $array=json_decode(file_get_contents("php://input"),true);
// //create dir if idnt exist
// $images_directory = __DIR__ . "/assets";
// if (!file_exists($images_directory)) {
//     mkdir($images_directory, 0777, true);
// }
// //check if file uploaded
// if (!isset($_FILES["image"])) {
//     http_response_code(400);
//     echo json_encode(["error" => "No image uploaded"]);
//     exit;
// }
// $tmp_name = $_FILES["image"]["tmp_name"];
// $original_name = $_FILES["image"]["name"];

// /* ✅ generate unique name to avoid overwrite */
// $extension = pathinfo($original_name, PATHINFO_EXTENSION);
// $new_name = uniqid("img_", true) . "." . $extension;

// $image_destination = $images_directory . "/" . $new_name;
// $image_path_db = "assets/" . $new_name;

// if (!is_array($array)) {
//     http_response_code(400);
//     echo json_encode([
//         "result" => "error",
//         "error" => "Invalid JSON"
//     ]);
//     exit;
// }
// $array = array_map('trim', $array);
// if (!empty($array["first_name"]) && 
// !empty($array["last_name"]) && 
// !empty($array["username"]) && 
//  !empty($array["password"])){
// if (!move_uploaded_file($tmp_name, $image_destination)){
//     http_response_code(500);
//     echo json_encode(["error" => "Upload failed"]);
// }
// $sql="INSERT INTO users 
//         ( first_name, last_name, username,`password`, profile_url) VALUES 
//         ( :first_name, :last_name, :username,:password, :profile_url);";
// $statment=$pdo->prepare($sql);
// try{
//     $statment->execute([
//         ":first_name"=>$array["first_name"],
//         ":last_name"=>$array["last_name"],
//         ":username"=>$array["username"],
//         ":password"=>Password::hashPassword($array["password"]),
//         ":profile_url"=>$image_path_db
        
// ]);
// http_response_code(201);
//     echo json_encode([
//         "message" => "User created successfully",
//         "user_id"=>$pdo->lastInsertId()       
//     ]);
// }
// catch(PDOException $e){
//     if ($e->getCode() == 23000) {
//     http_response_code(409);
//     echo json_encode([
//         "result" => "error",
//         "error" => "Username already exists"
//     ]);
//     exit;
// }else{
//     http_response_code(500);
//     echo json_encode([
//         "result"=>"error",
//         "error"=>"Database error"       
//     ]);}
    
// }
// }else{
//     http_response_code(400);
//     echo json_encode([
//         "result"=>"error",
//         "error"=>"Missing or invalid fields"       
//     ]);
// }

?>
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once("connection.php");
require_once("hash_password/password.php");

/* create assets directory */
$images_directory = __DIR__ . "/assets";
if (!file_exists($images_directory)) {
    mkdir($images_directory, 0777, true);
}

/* validate image */
if (!isset($_FILES["image"])) {
    http_response_code(400);
    echo json_encode(["error" => "Image is required"]);
    exit;
}

/* validate text fields */
$first_name = trim($_POST["first_name"] ?? "");
$last_name  = trim($_POST["last_name"] ?? "");
$username   = trim($_POST["username"] ?? "");
$password   = trim($_POST["password"] ?? "");

if ($first_name === "" || $last_name === "" || $username === "" || $password === "") {
    http_response_code(400);
    echo json_encode(["error" => "Missing required fields"]);
    exit;
}

/* handle image */
$tmp_name = $_FILES["image"]["tmp_name"];
$original_name = $_FILES["image"]["name"];
$extension = pathinfo($original_name, PATHINFO_EXTENSION);

$new_name = uniqid("img_", true) . "." . $extension;
$image_destination = $images_directory . "/" . $new_name;
$image_path_db = "assets/" . $new_name;

if (!move_uploaded_file($tmp_name, $image_destination)) {
    http_response_code(500);
    echo json_encode(["error" => "Image upload failed"]);
    exit;
}

/* insert into database */
$sql = "INSERT INTO users 
        (first_name, last_name, username, `password`, profile_url)
        VALUES (:first_name, :last_name, :username, :password, :profile_url)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ":first_name" => $first_name,
        ":last_name"  => $last_name,
        ":username"   => $username,
        ":password"   => Password::hashPassword($password),
        ":profile_url"=> $image_path_db
    ]);

    http_response_code(201);
    echo json_encode([
        "message" => "User created successfully",
        "user_id" => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(["error" => "Username already exists"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
    }
}
