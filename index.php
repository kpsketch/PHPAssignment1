<?php

    require("database.php");

    $queryItems = '
        SELECT itemID, itemName, quantity, category, status, imageName
        FROM shopping_list';

    $statement = $db->prepare($queryItems);
    $statement->execute();
    $items = $statement->fetchAll();
    $statement->closeCursor();

?>

<!DOCTYPE html>
<html>

    <head>
        <title>Shopping List Manager - Home</title>
        <link rel="stylesheet" type="text/css" href="css/shopping.css" />
    </head>

    <body>
        <?php include("header.php"); ?>

        <main>
            <h2>Shopping List</h2>

            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th>Photo</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                </tr>

                <?php foreach ($items as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['itemName']); ?></td>
                        <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($item['category']); ?></td>
                        <td><?php echo htmlspecialchars($item['status']); ?></td>

                        <!-- IMAGE COLUMN -->
                        <td>
    <img src="images/<?php echo htmlspecialchars($item['imageName']); ?>" 
         alt="<?php echo htmlspecialchars($item['itemName']); ?>" 
         width="80">
</td>


                        <td>
                            <form action="update_item_form.php" method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                                <input type="submit" value="Update" />
                            </form>
                        </td>

                        <td>
                            <form action="delete_item.php" method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                                <input type="submit" value="Delete" />
                            </form>
                        </td>

                        <td>
                            <form action="item_details.php" method="post">
                                <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                                <input type="submit" value="View Details" />
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>

            </table>

            <p><a href="add_item_form.php">Add Item</a></p>

        </main>

        <?php include("footer.php"); ?> 

    </body>
</html>
