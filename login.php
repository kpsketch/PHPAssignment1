<?php
session_start();
date_default_timezone_set("America/Toronto");

require_once('database.php');

$user_name = filter_input(INPUT_POST, 'user_name');
$user_password = filter_input(INPUT_POST, 'password');

$queryUsers = '
    SELECT userID, userName, password, emailAddress, failed_attempts, last_failed_login
    FROM registrations
    WHERE userName = :userName
';

$statement = $db->prepare($queryUsers);
$statement->bindValue(':userName', $user_name);
$statement->execute();
$user = $statement->fetch();
$statement->closeCursor();

if ($user) {
    $now = new DateTime();
    $last_failed = $user['last_failed_login'] ? new DateTime($user['last_failed_login']) : null;

    if ($last_failed) {
        $interval = $now->getTimestamp() - $last_failed->getTimestamp();
        if ($user['failed_attempts'] >= 3 && $interval < 300) {
            $remaining = 300 - $interval;
            $_SESSION['login_error'] = "Account locked. Try again in " . ceil($remaining) . " seconds.";
            header("Location: login_form.php");
            exit;
        }
    }

    if (password_verify($user_password, $user['password'])) {
        $_SESSION['isLoggedIn'] = TRUE;
        $_SESSION['userName'] = $user['userName'];
        $_SESSION['user_id'] = $user['userID'];

        $query = "UPDATE registrations SET failed_attempts = 0, last_failed_login = NULL WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        header("Location: login_confirmation.php");
        exit;
    } else {
        $query = "UPDATE registrations SET failed_attempts = failed_attempts + 1, last_failed_login = NOW() WHERE userName = :userName";
        $statement = $db->prepare($query);
        $statement->bindValue(':userName', $user_name);
        $statement->execute();
        $statement->closeCursor();

        $_SESSION['login_error'] = "Incorrect password.";
        header("Location: login_form.php");
        exit;
    }
} else {
    $_SESSION['login_error'] = "User not found.";
    header("Location: login_form.php");
    exit;
}
?>
