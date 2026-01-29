<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Add Item Confirmation</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>

<body>

<?php include("header.php"); ?>

<main>
    <h2>Add Item Confirmation</h2>

    <p>
        Thank you. Your item <strong><?php echo htmlspecialchars($_SESSION["itemName"]); ?></strong>
        was saved successfully.
    </p>

    <p><a href="index.php">Back to Home</a></p>
</main>

<?php include("footer.php"); ?>

</body>
</html>
