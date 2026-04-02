<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
session_start();

require __DIR__ . '/../app/core/App.php';
require __DIR__ . '/../app/core/Controller.php';
require __DIR__ . '/../app/core/Router.php';
require __DIR__ . '/../app/core/functions.php';

// Auto-close passed events on every request
checkEventValidity();

// Autoload controllers if needed, or just require them manually for simplicity in this small project
// For a larger project, we'd use Composer's autoloader.
require __DIR__ . '/../app/controllers/HomeController.php';
require __DIR__ . '/../app/controllers/MenuController.php';
require __DIR__ . '/../app/controllers/AuthController.php';
require __DIR__ . '/../app/controllers/AdminController.php';
require __DIR__ . '/../app/controllers/ProfileController.php';
require __DIR__ . '/../app/controllers/CalendarController.php';


$app = new App();

