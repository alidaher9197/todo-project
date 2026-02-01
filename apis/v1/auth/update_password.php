<?php
header("Content-Type: application/json");
require_once("../helpers/connection.php");
require_once("../helpers/hash_password/password.php");
require_once ("../../../jwt.php");
require_once("../helpers/functions.php");
require_once("../helpers/Users.php");
$headers = getallheaders();
$data = json_decode(file_get_contents("php://input"), true);

is_valid_json($data);
required_fields($data,"old_password","new_password");
$old_password=$data["old_password"];
$new_password=$data["new_password"];
$result=require_auth();
Users::select_user($pdo,$result,$old_password);
Users::edit_pass($pdo,$result,$new_password);
?>