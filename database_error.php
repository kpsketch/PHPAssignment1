<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Database Error</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>
<body>

<?php include("header.php"); ?>

<main>
    <h2>Database Error</h2>
    <p>There was an error connecting to the database.</p>
    <p>MySQL must be running.</p>
    <p><strong>Error Message:</strong> <?php echo htmlspecialchars($_SESSION["database_error"] ?? ""); ?></p>

    <p><a href="index.php">Back to Home</a></p>
</main>

<?php include("footer.php"); ?>

</body>
</html>
