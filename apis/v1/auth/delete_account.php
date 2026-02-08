<?php
header("Content-Type: application/json");
require_once("../helpers/connection.php");
require_once("../helpers/hash_password/password.php");
require_once ("../../../jwt.php");
require_once("../helpers/functions.php");
require_once("../helpers/Users.php");
$headers = getallheaders();
$result=require_auth($pdo);
Users::delete_user($pdo,$result);
?>