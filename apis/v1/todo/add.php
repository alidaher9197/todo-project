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

$user_id=require_auth();
required_fields($_POST,"title","description");
isset_image($_FILES);
/* validate text fields */
$title = trim($_POST["title"] ?? "");
$description  = trim($_POST["description"] ?? "");
$image_path_db=add_image($_FILES["image"]);
Todos::add_todo($pdo,$title,$description,$user_id,$image_path_db);
?>