<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require '../app/core/App.php';
require '../app/core/Controller.php';
require '../app/core/Router.php';
require '../app/core/functions.php';

// Auto-close passed events on every request
checkEventValidity();

// Autoload controllers if needed, or just require them manually for simplicity in this small project
// For a larger project, we'd use Composer's autoloader.
require '../app/controllers/HomeController.php';
require '../app/controllers/MenuController.php';
require '../app/controllers/AuthController.php';
require '../app/controllers/AdminController.php';
require '../app/controllers/ProfileController.php';
require '../app/controllers/CalendarController.php';


$app = new App();
