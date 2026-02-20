<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=shopping_list_db;charset=utf8mb4';
$username = 'root';
$password = '';

try {
    $db = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    $_SESSION['database_error'] = $e->getMessage();
    header("Location: database_error.php");
    exit();
}
?>
