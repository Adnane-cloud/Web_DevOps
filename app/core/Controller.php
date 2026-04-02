<?php

class Controller {
    public function view($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Check if view exists
        if (file_exists(__DIR__ . '/../views/' . $view . '.php')) {
            require __DIR__ . '/../views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }
}

