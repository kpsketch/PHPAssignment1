<?php

    require("database.php");

    $queryItems = '
        SELECT itemName, quantity, category, status 
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
    <link rel="stylesheet" type="text/css" href="css/contact.css" />
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
        </tr>

        <?php foreach ($items as $item): ?>
        <tr>
            <td><?php echo htmlspecialchars($item['itemName']); ?></td>
            <td><?php echo htmlspecialchars($item['quantity']); ?></td>
            <td><?php echo htmlspecialchars($item['category']); ?></td>
            <td><?php echo htmlspecialchars($item['status']); ?></td>
        </tr>
        <?php endforeach; ?>

    </table>
</main>

<?php include("footer.php"); ?>

</body>
</html>
