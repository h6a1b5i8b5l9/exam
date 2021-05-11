<?php


abstract class Flash
{
    public static function setMessage($name, $message) {
        session_start();
        $_SESSION[$name] = $message;
    }

    public static function getMessage($name) {
        session_start();
        $message = '';
        if(isset($_SESSION[$name])) {
            $message = $_SESSION[$name];
            unset($_SESSION[$name]);
            return $message;
        }

        return $message;
    }

}