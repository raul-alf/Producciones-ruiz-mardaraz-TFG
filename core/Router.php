<?php

class Router
{
    private $routes = [];
    

    public function get($url, $controllerAction)
    {
        $this->routes['GET'][$url] = $controllerAction;
    }

    public function post($url, $controllerAction)
    {
        $this->routes['POST'][$url] = $controllerAction;
    }

    public function dispatch($requestUri, $requestMethod)
    {
        $url = parse_url($requestUri, PHP_URL_PATH);
        if (!isset($this->routes[$requestMethod][$url])) {
            http_response_code(404);
            echo "Página no encontrada: " . $url;
            return;
        }

        $controllerAction = $this->routes[$requestMethod][$url];

        [$controllerName, $methodName] = explode('@', $controllerAction);

        $controllerFile = '../controllers/' . $controllerName . '.php';

        if (!file_exists($controllerFile)) {
            die("No existe el controlador: " . $controllerName);
        }

        require_once $controllerFile;

        $controller = new $controllerName();

        if (!method_exists($controller, $methodName)) {
            die("No existe el método: " . $methodName);
        }

        $controller->$methodName();
    }
}