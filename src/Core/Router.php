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
        
        $callback=$this->routes[$method][$path] ?? false;
        
        if (!$callback) {
            foreach ($this->routes[$method] as $route => $routeCallback) {
                $pattern = preg_replace('/\{[^}]+\}/', '([^/]+)', $route);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $path, $matches)) {
                    $callback = $routeCallback;
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
        call_user_func($callback);
        return;
    }

    if (is_array($callback)) {
        $controllerName = $callback[0];
        $methodName = $callback[1];

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