<?php
header("Content-Type: application/json");
require_once("connection.php");
require_once("hash_password/password.php");
require_once ("../../../jwt.php");
$data = json_decode(file_get_contents("php://input"), true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Invalid JSON"
    ]);
    exit;
}
if(!isset($data["username"], $data["password"]) || trim($data["password"]) === "" || trim($data["username"]) === ""){
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Missing required fields"
    ]);
    exit;
}
$username=trim($data["username"]);
$password=trim($data["password"]);

$sql = "SELECT id, username, `password`,profile_url
        FROM users
        WHERE username = :username";

$statement = $pdo->prepare($sql);

try {
    $statement->execute([
        ":username" => $username
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user || !Password::verifyPassword($password,$user["password"])) {
        http_response_code(401);
        echo json_encode([
            "result" => "error",
            "error" => "Invalid username or password"
        ]);
        exit;
    }

    
    
    http_response_code(200);
    echo json_encode([
        "result" => "success",
        "message" => "Login successful",
        "id" => $user["id"],
        "username" => $user["username"],
        "profile_url" => $user["profile_url"],
        "token" => MyJWT::generateToken($user["id"])
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}




?>