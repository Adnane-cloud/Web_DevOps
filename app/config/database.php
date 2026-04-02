<?php
$host = 'sql206.infinityfree.com';
$db_name = 'if0_41563635_database';
$username = 'if0_41563635';
$password = 'A2005dnane';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db_name;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}
