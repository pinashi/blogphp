<?php


class Router {

    public array $routes = [];

    public function get(string $uri, array $action): void {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, array $action): void {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(): void {       
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri    = trim($uri, '/');
        $method = $_SERVER['REQUEST_METHOD'];

        foreach($this->routes[$method] as $route => $action) {
            $pattern = preg_replace('/\{[a-z]+\}/', '(\d+)', $route);
            $pattern = '#^' . $pattern . '$#';
            
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);

                [$controllerClass, $method] = $action;

                require_once __DIR__ . "/Controllers/{$controllerClass}.php";

                $controller = new $controllerClass();
                $controller->$method(...$matches);
                return;
            }
        
        }

        http_response_code(404);
        require_once __DIR__ . '/Views/404.php';
    } 
}