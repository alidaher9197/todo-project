<?php
    require_once ("../../../read_env.php"); 
    $db_host = $_ENV["DB_HOST"];
    $db_name = $_ENV["DB_NAME"];
    $db_user = $_ENV["DB_USER"];
    $db_password = $_ENV["DB_PASSWORD"];
    $charset = $_ENV["CHARSET"];
    
    $connection = "mysql:host=$db_host;dbname=$db_name;charset=$charset";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        $pdo = new PDO($connection, $db_user, $db_password, $options);
    } catch (\Throwable $th) {
        echo json_encode([
            "result"=>"error",
            "error"=>"connection to db failed"
        ]);
    }
?>