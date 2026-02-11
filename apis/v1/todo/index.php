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

$user_id=require_auth($pdo);
Todos::view_todos($pdo,$user_id);
?>