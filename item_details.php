<?php
session_start();

if (!isset($_SESSION['isLoggedIn'])) {
    header("Location: login_form.php");
    exit;
}

require_once("database.php");

$queryItems = "
    SELECT itemID, itemName, quantity, category, status, imageName, price
    FROM shopping_list
    ORDER BY itemID DESC
";

$statement = $db->prepare($queryItems);
$statement->execute();
$items = $statement->fetchAll();
$statement->closeCursor();

$grand_total = 0.0;
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
    <h2>
        Shopping List (Logged In User: <?php echo htmlspecialchars($_SESSION['userName']); ?>)
    </h2>

    <table>
        <tr>
            <th>Photo</th>
            <th>Item Name</th>
            <th>Quantity</th>
            <th>Category</th>
            <th>Status</th>
            <th>Price</th>
            <th>Total</th>
            <th></th>
            <th></th>
            <th></th>
        </tr>

        <?php foreach ($items as $item): ?>
            <?php
                $price = (float)$item['price'];
                $qty = (int)$item['quantity'];
                $total = $qty * $price;
                $grand_total += $total;
                $img = $item['imageName'] ?? 'placeholder_100.jpg';
            ?>
            <tr>
                <td>
                    <img src="images/<?php echo htmlspecialchars($img); ?>"
                         alt="<?php echo htmlspecialchars($item['itemName']); ?>"
                         style="width:80px;height:80px;object-fit:cover;">
                </td>
                <td><?php echo htmlspecialchars($item['itemName']); ?></td>
                <td><?php echo htmlspecialchars($qty); ?></td>
                <td><?php echo htmlspecialchars($item['category']); ?></td>
                <td><?php echo htmlspecialchars($item['status']); ?></td>
                <td>$<?php echo number_format($price, 2); ?></td>
                <td>$<?php echo number_format($total, 2); ?></td>

                <td>
                    <form action="item_details.php" method="post">
                        <input type="hidden" name="item_id" value="<?php echo (int)$item['itemID']; ?>">
                        <input type="submit" value="View">
                    </form>
                </td>

                <td>
                    <form action="update_item_form.php" method="post">
                        <input type="hidden" name="item_id" value="<?php echo (int)$item['itemID']; ?>">
                        <input type="submit" value="Edit">
                    </form>
                </td>

                <td>
                    <form action="delete_item.php" method="post"
                          onsubmit="return confirm('Are you sure you want to delete this item?');">
                        <input type="hidden" name="item_id" value="<?php echo (int)$item['itemID']; ?>">
                        <input type="submit" value="Delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

        <tr style="font-weight:bold; background:#f2f2f2;">
            <td colspan="6" style="text-align:right;">Grand Total:</td>
            <td colspan="4">$<?php echo number_format($grand_total, 2); ?></td>
        </tr>
    </table>

    <p><a href="add_item_form.php">Add Item</a></p>
    <p><a href="logout.php">Logout</a></p>
</main>

<?php include("footer.php"); ?>
</body>
</html>
