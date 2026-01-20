<?php

namespace App\Core;
class Router{
    public Request $request;
    protected array $routes=[];

    public function __construct(Request $request){
        $this->request=$request;
    }
    
    public function get($path,$callback){
        $this->routes['GET'][$path]=$callback;
        
    }
    public function post($path,$callback){
        $this->routes['POST'][$path]=$callback;
       
    }

    public function resolve(){
        $path=$this->request->getPath();
        $method=$this->request->getMethod();
        
        // Try exact match first
        $callback=$this->routes[$method][$path] ?? false;
        
        // If no exact match, try pattern matching for routes with parameters
        if (!$callback) {
            foreach ($this->routes[$method] as $route => $routeCallback) {
                // Convert route pattern to regex
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    $callback = $routeCallback;
                    // Remove the full match from matches
                    array_shift($matches);
                    break;
                }
            }
        }
        
       if (!$callback){ 
        http_response_code(404);
        echo '404 Not Found';
        return;
    }

    if (is_callable($callback)) {
        call_user_func($callback, $path);
        return;
    }

    if (is_array($callback)) {
        $controllerName = $callback[0];
        $methodName = $callback[1];

        // Ajout du namespace pour les contrôleurs
        $fullControllerName = 'App\\Controllers\\' . $controllerName;

        $controller = new $fullControllerName();
        
        if (method_exists($controller, $methodName)) {
            $controller->$methodName();
        } else {
            echo "Method {$methodName} not found in {$fullControllerName}";
        }
        return;
    }

    echo 'Invalid route configuration';
       
    }  
}