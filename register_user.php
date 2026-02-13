<?php
session_start();

$user_name = filter_input(INPUT_POST, 'user_name');
$user_password = filter_input(INPUT_POST, 'password');
$email_address = filter_input(INPUT_POST, 'email_address');

require_once('database.php');

$hash = password_hash($user_password, PASSWORD_DEFAULT);

$queryUsers = 'SELECT userName FROM registrations';
$statement = $db->prepare($queryUsers);
$statement->execute();
$users = $statement->fetchAll();
$statement->closeCursor();

foreach ($users as $user) {
    if ($user_name == $user["userName"]) {
        $_SESSION["add_error"] = "Duplicate username. Try again.";
        header("Location: error.php");
        die();
    }
}

if ($user_name == null || $user_password == null || $email_address == null) {
    $_SESSION["add_error"] = "Invalid registration data. Check all fields.";
    header("Location: error.php");
    die();
}

$query = 'INSERT INTO registrations (userName, password, emailAddress) VALUES (:userName, :password, :emailAddress)';
$statement = $db->prepare($query);
$statement->bindValue(':userName', $user_name);
$statement->bindValue(':password', $hash);
$statement->bindValue(':emailAddress', $email_address);
$statement->execute();
$statement->closeCursor();

$_SESSION["isLoggedIn"] = 1;
$_SESSION["userName"] = $user_name;

header("Location: register_confirmation.php");
die();
?>
