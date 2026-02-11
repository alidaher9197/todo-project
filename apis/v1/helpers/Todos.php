<?php
require_once("functions.php");
abstract Class Todos{
public static function add_todo($pdo,$title,$description,$user_id,$image_path_db){
    /* insert into database */
$sql = "INSERT INTO todos 
        (title, `description`, user_id, `image_url`)
        VALUES (:title, :description, :user_id, :image_path_db)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        ":title" => $title,
        ":description"  => $description,
        ":user_id"   => $user_id,
        ":image_path_db"   => $image_path_db
    ]);

    http_response_code(201);
    echo json_encode([
        "message" => "todo created successfully",
        "todo_id" => $pdo->lastInsertId()
    ]);

} catch (PDOException $e) {

    if ($e->getCode() == 23000) {
        http_response_code(409);
        echo json_encode(["error" => "title already exists"]);
    } else {
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
    }
}
}
public static function view_todos($pdo,$id){
$sql="SELECT * FROM todos WHERE user_id=:id";
$stmt = $pdo->prepare($sql);
try{
$stmt->execute([
        ":id" => $id
    ]);

    $todos = $stmt->fetchAll();

    http_response_code(200);
    echo json_encode([
        "result"=>"done",
        "todos"=>$todos
    ]);
}catch(PDOException $e){
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
}
}

public static function view_todo_by_id($pdo,$id){
$sql="SELECT * FROM todos WHERE id=:id LIMIT 1";
$statement=$pdo->prepare($sql);
try{
$statement->execute([
        ":id" => $id
    ]);
    $todo = $statement->fetch(PDO::FETCH_ASSOC);
    if(!$todo){
       http_response_code(400);
        echo json_encode([
        "result"=>"error",
        "message"=>"no todo"
    ]); 
    exit;
    }
    http_response_code(200);
    return $todo;
}catch(PDOException $e){
        http_response_code(500);
        echo json_encode(["error" => "Database error"]);
}
}
public static function delete_todo_by_id($pdo,$id,$user_id)
{
    $todo=self::view_todo_by_id($pdo,$id);
    delete_image($todo["image_url"]);
    $sql = "DELETE FROM todos WHERE id = :id";
    $statement = $pdo->prepare($sql);

    try {
        $statement->execute([
            ":id" => $id
        ]);

        if ($statement->rowCount() === 0) {
            http_response_code(404);
            echo json_encode([
                "result"  => "error",
                "message" => "Todo not found"
            ]);
            exit;
        }

        http_response_code(200);
        echo json_encode([
            "result" => "deleted",
        "token" => MyJWT::generateToken($user_id)
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
public static function test_title($value){
    if(strlen($value)>100 || strlen($value)<2){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "title must be between 2 and 100 char"
    ]);
    exit;
    }   
}

public static function test_description($value){
    if(strlen($value)>255 || strlen($value)<2){
        http_response_code(400);
        echo json_encode([
        "result" => "error",
        "error" => "description must be between 2 and 255 char"
    ]);
    exit;
    }   
}

}

?>