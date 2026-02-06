<?php
    session_start();   
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Shopping List Manager - Update Confirmation</title>
        <link rel="stylesheet" type="text/css" href="css/shopping.css" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Update Item Confirmation</h2>
            <p>
                Item <strong><?php echo htmlspecialchars($_SESSION["itemName"]); ?></strong>
                was updated successfully.
            </p>                        

            <p><a href="index.php">Back to Home</a></p>

        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
