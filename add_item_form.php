<?php
    session_start();
    require("database.php");
?>

<!DOCTYPE html>
<html>

    <head>
        <title>Shopping List Manager - Add Item</title>
        <link rel="stylesheet" type="text/css" href="css/shopping.css" />
    </head>

    <body>

        <?php include("header.php"); ?>

        <main>
            <h2>Add Item</h2>

            <form action="add_item.php" method="post" id="add_item_form" enctype="multipart/form-data">

                <div id="data">

                    <label>Item Name:</label>
                    <input type="text" name="item_name" /><br />

                    <label>Quantity:</label>
                    <input type="text" name="quantity" /><br />

                    <label>Category:</label>
                    <input type="text" name="category" /><br />

                    <label>Status:</label><br />
                    <input type="radio" name="status" value="To Buy" checked />To Buy<br />
                    <input type="radio" name="status" value="Bought" />Bought<br /><br />

                    <label>Upload Image:</label>
                    <input type="file" name="file1" /><br />

                </div>

                <div id="buttons">
                    <label>&nbsp;</label>
                    <input type="submit" value="Save Item" /><br />
                </div>

            </form>

            <p><a href="index.php">View Shopping List</a></p>
        </main>

        <?php include("footer.php"); ?>

    </body>
</html>
