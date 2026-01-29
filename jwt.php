<?php
require_once (__DIR__."/read_env.php");
require_once (__DIR__."/vendor/autoload.php");
    use Firebase\JWT\JWT;
    use Firebase\JWT\Key;
    class MyJWT{
        public static function generateToken($id){
            $secret_key=$_ENV["JWT_SECRET_KEY"];
            $payload=[
                "iat"=>time(),
                "exp"=>time()+7200,
                "id"=>$id,
            ];
            $jwt=JWT::encode($payload,$secret_key, "HS256");
            return $jwt;
        }
        public static function verifyToken($token){
            $secret_key=$_ENV["JWT_SECRET_KEY"];
            
            try{
                $payload=JWT::decode($token,new Key($secret_key, "HS256"));
                return $payload->id;
            }
            catch(\Throwable $th){
                return null;
            }
        }
    }
?>