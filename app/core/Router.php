<?php

class Router {
    protected static $routes = [];

    public static function get($uri, $controller) {
        self::$routes['GET'][$uri] = $controller;
    }

    public static function post($uri, $controller) {
        self::$routes['POST'][$uri] = $controller;
    }

    public static function dispatch() {
        $uri = isset($_GET['url']) ? '/' . rtrim($_GET['url'], '/') : '/';
        $method = $_SERVER['REQUEST_METHOD'];

        if (isset(self::$routes[$method])) {
            foreach (self::$routes[$method] as $routeUri => $controllerAction) {
                // Convert {id} to (\d+) for regex matching
                $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_]+)', $routeUri);
                $pattern = "#^" . $pattern . "$#";

                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches); // Remove full match

                    $parts = explode('@', $controllerAction);
                    $controllerName = $parts[0];
                    $action = $parts[1];

                    if (class_exists($controllerName)) {
                        $controller = new $controllerName();
                        if (method_exists($controller, $action)) {
                            // Call action with extracted params
                            call_user_func_array([$controller, $action], $matches);
                            return;
                        }
                    }
                }
            }
        }

        // fallback to exact match check (unlikely needed if loop handles it, but safe)
        // or just 404
        echo "404 Not Found";
    }
}
