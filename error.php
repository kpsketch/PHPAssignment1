<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Error</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>
<body>
<?php include("header.php"); ?>

<main>
    <h2>Error</h2>
    <p style="color:red; font-weight:bold; text-align:center;">
        <?php echo htmlspecialchars($_SESSION["add_error"] ?? "Something went wrong."); ?>
    </p>
    <p style="text-align:center;"><a href="index.php">Back</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
