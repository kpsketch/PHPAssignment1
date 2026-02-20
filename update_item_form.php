<?php
require_once("database.php");

$item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
if (!$item_id) {
    header("Location: index.php");
    exit;
}

$query = "
    SELECT itemID, itemName, quantity, category, status, imageName, price
    FROM shopping_list
    WHERE itemID = :item_id
";
$statement = $db->prepare($query);
$statement->bindValue(':item_id', $item_id);
$statement->execute();
$item = $statement->fetch();
$statement->closeCursor();

if (!$item) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Update Item</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>

<body>
<?php include("header.php"); ?>

<main>
    <h2>Update Item</h2>

    <form action="update_item.php" method="post" id="update_item_form">
        <input type="hidden" name="item_id" value="<?php echo (int)$item['itemID']; ?>" />

        <div id="data">
            <label>Item Name:</label>
            <input type="text" name="item_name" value="<?php echo htmlspecialchars($item['itemName']); ?>" required /><br />

            <label>Quantity:</label>
            <input type="number" name="quantity" min="1" value="<?php echo htmlspecialchars($item['quantity']); ?>" required /><br />

            <label>Category:</label>
            <input type="text" name="category" value="<?php echo htmlspecialchars($item['category']); ?>" required /><br />

            <label>Price ($):</label>
            <input type="number" name="price" step="0.01" min="0"
                   value="<?php echo htmlspecialchars($item['price']); ?>" required /><br />

            <label>Status:</label><br />
            <input type="radio" name="status" value="To Buy" <?php if ($item['status'] === 'To Buy') echo 'checked'; ?> /> To Buy<br />
            <input type="radio" name="status" value="Bought" <?php if ($item['status'] === 'Bought') echo 'checked'; ?> /> Bought<br /><br />

            <p style="margin-top:10px; font-size:14px; color:#333;">
                ✅ Image will update automatically if you change Item Name.
            </p>
        </div>

        <div id="buttons">
            <label>&nbsp;</label>
            <input type="submit" value="Update Item" />
        </div>
    </form>

    <p><a href="index.php">View Shopping List</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
