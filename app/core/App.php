<?php

class App {
    public function __construct() {
        // Load routes
        require __DIR__ . '/../../routes/web.php';
        
        // Dispatch
        Router::dispatch();
    }
}

