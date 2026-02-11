<?php
require_once("functions.php");
abstract class Users{
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

    
        http_response_code(500);
       
    // Check if the error is a duplicate entry
    if ($e->getCode() == 23000) {
        // 23000 = integrity constraint violation (unique key)
        http_response_code(409);
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
public static function test_first_name($value){
    if(strlen($value)>20 ||strlen($value)<3 ){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "first_name must be between 3 and 20 char"
    ]);
    exit;
    }
    
}
public static function test_last_name($value){
    if(strlen($value)>20 ||strlen($value)<3 ){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "last_name must be between 3 and 20 char"
    ]);
    exit;
    }
    
}
public static function test_username($value){
    if(strlen($value)>20 ||strlen($value)<3 ){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "username must be between 3 and 20 char"
    ]);
    exit;
    }
    
}
public static function test_password($value){
    if(strlen($value)>30 ||strlen($value)<6 ){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "password must be between 6 and 30 char"
    ]);
    exit;
    }
    
}
public static function delete_user($pdo,$id){
    $user=self::select_user_by_id($pdo,$id);
    delete_image($user["profile_url"]);
    $sql = "DELETE FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);

    try {
        $statement->execute([
            ":id" => $id
        ]);

        if ($statement->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                "result"  => "error",
                "message" => "user not found"
            ]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            "result" => "deleted"
        ]);
        exit;

    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode([
            "error" => "Database error"
        ]);
        exit;
    }
}
public static function select_user_by_id($pdo,$id){
    if (!is_numeric($id)) {
    http_response_code(400);
    echo json_encode([
        "result" => "error",
        "message" => "Invalid user ID"
    ]);
    exit;
}
     $sql="SELECT id, username, first_name,last_name,profile_url FROM users WHERE id=:id";
    $stmt = $pdo->prepare($sql);
    try{
    $stmt->execute([
    ":id"       => $id
]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$user) {
            http_response_code(404);
            echo json_encode([
                "result"  => "error",
                "message" => "user not found"
            ]);
            exit;
        }
        return $user;
}catch(PDOException $e){
    http_response_code(500);
        echo json_encode([
            "error" => "Database error"
        ]);
        exit;
}}
}




?>