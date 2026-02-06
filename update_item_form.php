<?php
    require_once("database.php");
    
    // get data from the form
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

    $queryItems = '
        SELECT itemID, itemName, quantity, category, status, imageName FROM shopping_list WHERE itemID = :item_id';

    $statement = $db->prepare($queryItems);
    $statement->bindValue(':item_id', $item_id);
    $statement->execute();
    $item = $statement->fetch();
    $statement->closeCursor();

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

            <form action="update_item.php" method="post" id="update_item_form" enctype="multipart/form-data">
                <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                <div id="data">

                    <label>Item Name:</label>
                    <input type="text" name="item_name" value="<?php echo $item['itemName']; ?>" /><br />

                    <label>Quantity:</label>
                    <input type="text" name="quantity" value="<?php echo $item['quantity']; ?>" /><br />

                    <label>Category:</label>
                    <input type="text" name="category" value="<?php echo $item['category']; ?>" /><br />

                    <label>Status:</label><br />
                    <input type="radio" name="status" value="To Buy" <?php if ($item['status'] == 'To Buy') echo 'checked'; ?>/>To Buy<br />
                    <input type="radio" name="status" value="Bought" <?php if ($item['status'] == 'Bought') echo 'checked'; ?> />Bought<br /><br />

                    <?php if (!empty($item['imageName'])): ?>
                        <label>Current Image:</label>
                        <img src="images/<?php echo htmlspecialchars($item['imageName']); ?>" height="100"><br />                        
                    <?php endif; ?>

                    <label>Update Image:</label>
                    <input type="file" name="file1" /><br />

                </div>

                <div id="buttons">
                   <label>&nbsp;</label>
                   <input type="submit" value="Update Item" /><br /> 
                </div>

            </form>            

            <p><a href="index.php">View Shopping List</a></p>

        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
