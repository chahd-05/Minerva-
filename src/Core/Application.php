<?php
namespace App\Core;

class Application {
    public Router $router;
    public Request $request;
    
    public function __construct() {
        $this->request = new Request();
        $this->router = new Router($this->request);
    }
    
    public function run() {
        try {
            $this->router->resolve();
        } catch (Exception $e) {
            http_response_code(500);
            echo "Erreur serveur : " . $e->getMessage();
        }
    }
}