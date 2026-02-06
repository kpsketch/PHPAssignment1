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
            
            <p>Error Message: <?php echo htmlspecialchars($_SESSION["add_error"]); ?></p>

            <p><a href="add_item_form.php">Add Item</a></p>
            <p><a href="index.php">View Shopping List</a></p>
        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
