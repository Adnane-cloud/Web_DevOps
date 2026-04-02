<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'sql206.infinityfree.com';
$db_name = 'if0_41563635_database'; // Double-check this!
$username = 'if0_41563635';
$password = 'A2005dnane'; 

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h1>Success! The Database is Connected.</h1>";
} catch (PDOException $e) {
    echo "<h1>Connection Failed!</h1>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>