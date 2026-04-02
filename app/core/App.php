<?php

class App {
    public function __construct() {
        // Load routes
        require '../routes/web.php';
        
        // Dispatch
        Router::dispatch();
    }
}
