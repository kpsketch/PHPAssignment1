<?php
session_start();
require_once("database.php");

$item_id   = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
$item_name = trim((string)filter_input(INPUT_POST, 'item_name'));
$quantity  = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$category  = trim((string)filter_input(INPUT_POST, 'category'));
$status    = trim((string)filter_input(INPUT_POST, 'status'));
$price     = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

if (!$item_id || $item_name === '' || $quantity === false || $category === '' || $status === '' || $price === false) {
    $_SESSION["add_error"] = "Invalid item data. Please check all fields.";
    header("Location: error.php");
    exit;
}

// Get current item (so we can keep old image if no match)
$queryCurrent = "
    SELECT imageName
    FROM shopping_list
    WHERE itemID = :item_id
";
$stmt = $db->prepare($queryCurrent);
$stmt->bindValue(':item_id', $item_id);
$stmt->execute();
$current = $stmt->fetch();
$stmt->closeCursor();

$old_image = $current ? $current['imageName'] : 'placeholder_100.jpg';

// Duplicate name check (exclude current)
$queryDup = "
    SELECT itemID
    FROM shopping_list
    WHERE itemName = :itemName AND itemID != :itemID
    LIMIT 1
";
$stmt = $db->prepare($queryDup);
$stmt->bindValue(':itemName', $item_name);
$stmt->bindValue(':itemID', $item_id);
$stmt->execute();
if ($stmt->fetch()) {
    $_SESSION["add_error"] = "Duplicate item name. Try again.";
    header("Location: error.php");
    exit;
}
$stmt->closeCursor();

function pick_image_by_item_name(string $item_name): ?string {
    $name = strtolower($item_name);
    $name_clean = preg_replace('/[^a-z0-9]/', '', $name);

    $map = [
        'milk'       => 'milk_100.png',
        'bread'      => 'bread_100.png',
        'headphones' => 'headphones_100.png',
        'tshirt'     => 'tshirt_100.png',
        'apple'      => 'apple_100.png',
        'juice'      => 'juice_100.png',
    ];

    foreach ($map as $key => $file) {
        if (strpos($name_clean, $key) !== false) {
            return $file;
        }
    }
    return null; // no match
}

$match_image = pick_image_by_item_name($item_name);
$image_name = $match_image ? $match_image : $old_image;

if (!file_exists("images/" . $image_name)) {
    $image_name = 'placeholder_100.jpg';
}

// Update item
$queryUpdate = "
    UPDATE shopping_list
    SET itemName = :itemName,
        quantity = :quantity,
        category = :category,
        status = :status,
        price = :price,
        imageName = :imageName
    WHERE itemID = :itemID
";
$stmt = $db->prepare($queryUpdate);
$stmt->bindValue(':itemName', $item_name);
$stmt->bindValue(':quantity', $quantity);
$stmt->bindValue(':category', $category);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':price', $price);
$stmt->bindValue(':imageName', $image_name);
$stmt->bindValue(':itemID', $item_id);
$stmt->execute();
$stmt->closeCursor();

$_SESSION["itemName"] = $item_name;
header("Location: update_confirmation.php");
exit;
?>
