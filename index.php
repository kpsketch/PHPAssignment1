<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: login_form.php");
    die();
}

require("database.php");

$queryItems = '
    SELECT itemID, itemName, quantity, category, status, imageName
    FROM shopping_list
';

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
    <h2>Shopping List (<?php echo "Logged In User: " . htmlspecialchars($_SESSION['userName']); ?>)</h2>

    <table>
        <tr>
            <th>Photo</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Status</th>
            <th>&nbsp;</th>
            <th>&nbsp;</th>
        </tr>

        <?php foreach ($items as $item): ?>
        <tr>
            <td>
                <img src="<?php echo htmlspecialchars('./images/' . $item['imageName']); ?>"
                     alt="<?php echo htmlspecialchars($item['itemName']); ?>" />
            </td>
            <td><?php echo htmlspecialchars($item['itemName']); ?></td>
            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td><?php echo htmlspecialchars($item['status']); ?></td>

            <td>
                <form action="update_item_form.php" method="post">
                    <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                    <input type="submit" value="Edit" />
                </form>
            </td>

            <td>
                <form action="delete_item.php" method="post">
                    <input type="hidden" name="item_id" value="<?php echo $item['itemID']; ?>" />
                    <input type="submit" value="Delete" />
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <p><a href="add_item_form.php">Add Item</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
