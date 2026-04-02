<?php

class Controller {
    public function view($view, $data = []) {
        // Extract data to variables
        extract($data);

        // Check if view exists
        if (file_exists('../app/views/' . $view . '.php')) {
            require '../app/views/' . $view . '.php';
        } else {
            die("View does not exist.");
        }
    }
}
