<?php
// -----------------------------------------------------------------------
// Credentials are injected by the CI/CD pipeline from GitHub Secrets.
// Never commit real values here. See .github/workflows/deploy.yml.
// For local development, copy this file, fill in your values, and keep
// it out of git (it is listed in .gitignore).
// -----------------------------------------------------------------------
$host     = '%%DB_HOST%%';
$db_name  = '%%DB_NAME%%';
$username = '%%DB_USERNAME%%';
$password = '%%DB_PASSWORD%%';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$db_name;charset=utf8",
        $username,
        $password
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log('DB connection failed: ' . $e->getMessage());
    die('A database error occurred. Please try again later.');
}
