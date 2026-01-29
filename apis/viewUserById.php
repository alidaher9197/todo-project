<?php
header("Content-Type: application/json");
require_once("connection.php");

$array = json_decode(file_get_contents("php://input"), true);

if (!is_array($array)) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Invalid JSON"
    ]);
    exit;
}



// Validate ID
if (!isset($array["id"]) || !is_numeric($array["id"])) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Invalid user id"
    ]);
    exit;
}

$sql = "SELECT id, first_name, last_name, username, profile_url, created_at
        FROM users
        WHERE id = :id";

$statement = $pdo->prepare($sql);

try {
    $statement->execute([
        ":id" => (int)$array["id"]
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(404);
        echo json_encode([
            "result" => "error",
            "error" => "User not found"
        ]);
        exit;
    }

    http_response_code(200);
    echo json_encode([
        "message" => "User found",
        "user" => $user
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}
