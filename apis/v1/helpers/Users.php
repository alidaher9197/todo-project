<?php
abstract Class Users{
public static function sign_in($pdo,$username,$password){
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
}

public static function sign_up($pdo,$first_name,$last_name,$password,$username,$image_path_db){
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
}

public static function select_user($pdo,$id,$password){
    $sql = "SELECT id, username, `password`,profile_url
        FROM users
        WHERE id = :id";

$statement = $pdo->prepare($sql);

try {
    $statement->execute([
        ":id" => $id
    ]);

    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if (!$user ) {
        http_response_code(401);
        echo json_encode([
            "result" => "error",
            "error" => "Invalid username"
        ]);
        exit;
    }
    if (!Password::verifyPassword($password,$user["password"])) {
        http_response_code(401);
        echo json_encode([
            "result" => "error",
            "error" => "Invalid  password"
        ]);
        exit;
    }
    
    

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}
}
public static function edit_pass($pdo,$id,$new_pass){
    $sql="UPDATE users set password=:password WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    
try{
    $stmt->execute([
    ":password" => Password::hashPassword($new_pass),
    ":id"       => $id
]);

$rowsAffected = $stmt->rowCount();

if ($rowsAffected > 0) {
    echo json_encode([
        "result" => "success",
        "message" => "pass updated",
        "id" => $id,
        "token" => MyJWT::generateToken($id)
    ]);
} else {
    echo json_encode([
        "result" => "error",
        "message" => "pass is not updated",
        "id" => $id,
        "token" => MyJWT::generateToken($id)
    ]);
}
}
catch(PDOException $e){
 http_response_code(500);
    echo json_encode([
        "result" => "error",
        "error" => "Database error"
    ]);
}
}

}




?>