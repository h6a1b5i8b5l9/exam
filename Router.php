<?php


abstract class Router
{



    public static function redirect($routes = [], $page404) {
        $route = $_SERVER['REQUEST_URI'];

        if(array_key_exists($route, $routes)) {
            if(file_exists($routes[$route])) {
                include $routes[$route];
                exit;
            } else {
                return false;
            }

        } else {
            if(file_exists($page404)) {
            include $page404;
            exit;
        } else {
            return false;
        }
        }
    }

}