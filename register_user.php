<?php
session_start();
require_once('database.php');

/* TEMP DEBUG (remove later) */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$user_name = trim((string)filter_input(INPUT_POST, 'user_name'));
$user_password = (string)filter_input(INPUT_POST, 'password');
$email_address = trim((string)filter_input(INPUT_POST, 'email_address'));

if ($user_name === '' || $user_password === '' || $email_address === '') {
    $_SESSION["add_error"] = "All fields are required.";
    header("Location: error.php");
    exit;
}

if (!filter_var($email_address, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["add_error"] = "Please enter a valid email address.";
    header("Location: error.php");
    exit;
}

try {
    // Check duplicate username/email
    $queryCheck = "
        SELECT userID
        FROM registrations
        WHERE userName = :userName OR emailAddress = :emailAddress
        LIMIT 1
    ";
    $stmt = $db->prepare($queryCheck);
    $stmt->execute([
        ':userName' => $user_name,
        ':emailAddress' => $email_address
    ]);

    if ($stmt->fetch()) {
        $_SESSION["add_error"] = "Duplicate username or email. Try again.";
        header("Location: error.php");
        exit;
    }

    $hash = password_hash($user_password, PASSWORD_DEFAULT);

    // Insert user
    $queryInsert = "
        INSERT INTO registrations (userName, password, emailAddress, failed_attempts, last_failed_login)
        VALUES (:userName, :password, :emailAddress, 0, NULL)
    ";
    $stmt = $db->prepare($queryInsert);
    $stmt->execute([
        ':userName' => $user_name,
        ':password' => $hash,
        ':emailAddress' => $email_address
    ]);

    // Log in after register
    $_SESSION["isLoggedIn"] = true;
    $_SESSION["userName"] = $user_name;

header("Location: register_confirmation.php");
exit;
    exit;

} catch (PDOException $e) {
    $_SESSION["add_error"] = "DB Error: " . $e->getMessage();
    header("Location: error.php");
    exit;
}
