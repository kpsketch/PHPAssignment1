<?php
session_start();
require_once("database.php");
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

    <form action="add_item.php" method="post" id="add_item_form">

        <div id="data">
            <label>Item Name:</label>
            <input type="text" name="item_name" required /><br />

            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" required /><br />

            <label>Category:</label>
            <input type="text" name="category" required /><br />

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" min="0" required /><br />

            <label>Status:</label><br />
            <input type="radio" name="status" value="To Buy" checked /> To Buy<br />
            <input type="radio" name="status" value="Bought" /> Bought<br /><br />

            <p style="margin-top:10px; font-size:14px; color:#333;">
                ✅ Image will be selected automatically based on Item Name.
            </p>
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Save Item" />
        </div>

    </form>

    <p><a href="index.php">View Shopping List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
