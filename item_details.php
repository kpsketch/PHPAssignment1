<?php
require_once("database.php");

// Get item ID
$item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

if (!$item_id) {
    header("Location: index.php");
    exit;
}

// Fetch item (use correct column name itemId)
$query = "
    SELECT itemId, itemName, quantity, category, status, imageName
    FROM shopping_list
    WHERE itemId = :item_id
";

$statement = $db->prepare($query);
$statement->bindValue(':item_id', $item_id);
$statement->execute();
$item = $statement->fetch();
$statement->closeCursor();

if (!$item) {
    echo "Item not found.";
    exit;
}

// Convert _100 to _400 image
$imageName = $item['imageName'];  // example: milk_100.jpg
$dotPosition = strrpos($imageName, '.');
$baseName = substr($imageName, 0, $dotPosition);
$extension = substr($imageName, $dotPosition);

if (substr($baseName, -4) === '_100') {
    $baseName = substr($baseName, 0, -4);
}

$imageName_400 = $baseName . '_400' . $extension; // milk_400.jpg
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shopping List Manager - Item Details</title>
    <link rel="stylesheet" type="text/css" href="css/shopping.css" />
</head>

<body>

<?php include("header.php"); ?>

<main>
    <h2>Item Details</h2>

    <!-- BIG IMAGE -->
    <img class="detail-image"
     src="images/<?php echo htmlspecialchars($imageName_400); ?>"
     alt="<?php echo htmlspecialchars($item['itemName']); ?>" />

    <!-- DETAILS -->
    <div class="details">
        <p><strong>Item Name:</strong> <?php echo htmlspecialchars($item['itemName']); ?></p>
        <p><strong>Quantity:</strong> <?php echo htmlspecialchars($item['quantity']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($item['category']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($item['status']); ?></p>
    </div>

    <p class="back-link">
        <a href="index.php">Back to Shopping List</a>
    </p>

</main>

<?php include("footer.php"); ?>

</body>
</html>
