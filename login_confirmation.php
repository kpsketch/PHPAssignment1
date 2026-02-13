<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Login Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>
<body>

<?php include("header.php"); ?>

<main>
    <h2>Login Confirmation</h2>
    <p style="text-align:center;">
        Thank you, <?php echo htmlspecialchars($_SESSION["userName"] ?? ""); ?> for logging in.
    </p>
    <p style="text-align:center;">
        <a href="index.php">Go to Shopping List</a>
    </p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
