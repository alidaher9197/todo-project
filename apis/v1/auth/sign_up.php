
<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

require_once("../helpers/connection.php");
require_once("../helpers/hash_password/password.php");
require_once("../helpers/functions.php");
require_once("../helpers/Users.php");



required_fields($_POST,"first_name","last_name","username","password");
isset_image($_FILES);
/* validate text fields */
$first_name = trim($_POST["first_name"] ?? "");
$last_name  = trim($_POST["last_name"] ?? "");
$username   = trim($_POST["username"] ?? "");
$password   = trim($_POST["password"] ?? "");
$image_path_db=add_image($_FILES["image"]);
Users::test_first_name($first_name);
Users::test_last_name($last_name);
Users::test_username($username);
Users::test_password($password);

Users::sign_up($pdo,$first_name,$last_name,$password,$username,$image_path_db);
?>
