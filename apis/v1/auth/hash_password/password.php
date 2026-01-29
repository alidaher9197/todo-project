<?php
    class Password{
        public static function hashPassword($password):string{
            return password_hash($password,PASSWORD_DEFAULT);
        }

        public static function verifyPassword($password, $hashed_password):bool{
            return password_verify($password,$hashed_password);
        }
    }
?>