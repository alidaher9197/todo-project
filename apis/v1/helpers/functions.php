<?php
function is_valid_json($data){
    if (!is_array($data)) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "error" => "Invalid JSON"
    ]);
    exit;
}
}
function required_fields(array $data, ...$fields) {
    $required=[];
    foreach ($fields as $field) {
        if (!isset($data[$field]) || trim($data[$field]) === "") {
            array_push($required,$field);
            
        }
        
    }
    if($required!=null){
            http_response_code(400);
            echo json_encode([
                "result" => "error",
                "error" => "Missing fields: ".implode(", ", $required)
            ]);
            exit;
        }
}
function add_image($image){
if (!isset($image["error"]) || $image["error"] !== UPLOAD_ERR_OK) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid image upload"]);
        exit;
    }

    $images_directory = __DIR__ . "/../assets";

    if (!is_dir($images_directory)) {
        if (!mkdir($images_directory, 0755, true)) {
            http_response_code(500);
            echo json_encode(["error" => "Failed to create image directory"]);
            exit;
        }
    }

    $tmp_name = $image["tmp_name"];
    $original_name = $image["name"];
    $extension = strtolower(pathinfo($original_name, PATHINFO_EXTENSION));

    $allowed_extensions = ["jpg", "jpeg", "png", "webp"];

    if (!in_array($extension, $allowed_extensions)) {
        http_response_code(400);
        echo json_encode(["error" => "Invalid image type"]);
        exit;
    }

    $new_name = uniqid("img_", true) . "." . $extension;
    $image_destination = $images_directory . "/" . $new_name;

    if (!move_uploaded_file($tmp_name, $image_destination)) {
        http_response_code(500);
        echo json_encode(["error" => "Image upload failed"]);
        exit;
    }

    return "assets/" . $new_name;
}

function require_auth($pdo): int {
    // 1. Get headers safely
    $headers = getallheaders();
    $authHeader =
        $headers["Authorization"]
        ?? $_SERVER["HTTP_AUTHORIZATION"]
        ?? $_SERVER["REDIRECT_HTTP_AUTHORIZATION"]
        ?? null;

    if (!$authHeader) {
        http_response_code(401);
        echo json_encode(["error" => "No token provided"]);
        exit;
    }

    // 2. Split Bearer and token
    $parts = explode(" ", trim($authHeader));
    if (count($parts) !== 2 || strtolower($parts[0]) !== "bearer") {
        http_response_code(401);
        echo json_encode(["error" => "Invalid authorization format"]);
        exit;
    }

    $token = $parts[1];

    // 3. Verify JWT
    $userId = MyJWT::verifyToken($token);
    if ($userId === null) {
        http_response_code(401);
        echo json_encode(["error" => "Token expired or invalid"]);
        exit;
    }
    $sql = "SELECT id
        FROM users
        WHERE id = :userId";

$statement = $pdo->prepare($sql);

try {
    $statement->execute([
        ":userId" => $userId
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user ) {
        http_response_code(401);
        echo json_encode([
            "result" => "error",
            "error" => "Invalid user"
        ]);
        exit;
    }}catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}

    // 4. Return user ID for use in endpoint
    return $userId;


}
function isset_image($file){
    if (!isset($file["image"])) {
    http_response_code(400);
    echo json_encode(["error" => "Image is required"]);
    exit;
}
}

?>