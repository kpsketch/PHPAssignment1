<?php
session_start();
require_once("database.php");

$item_name = trim((string)filter_input(INPUT_POST, 'item_name'));
$quantity  = filter_input(INPUT_POST, 'quantity', FILTER_VALIDATE_INT);
$category  = trim((string)filter_input(INPUT_POST, 'category'));
$status    = trim((string)filter_input(INPUT_POST, 'status'));
$price     = filter_input(INPUT_POST, 'price', FILTER_VALIDATE_FLOAT);

// Validation
if ($item_name === '' || $quantity === false || $category === '' || $status === '' || $price === false) {
    $_SESSION["add_error"] = "Invalid item data. Please fill all fields correctly.";
    header("Location: error.php");
    exit;
}

// Duplicate check
$queryCheck = "
    SELECT itemID
    FROM shopping_list
    WHERE itemName = :itemName
    LIMIT 1
";
$stmt = $db->prepare($queryCheck);
$stmt->bindValue(':itemName', $item_name);
$stmt->execute();
if ($stmt->fetch()) {
    $_SESSION["add_error"] = "Duplicate item name. Try again.";
    header("Location: error.php");
    exit;
}
$stmt->closeCursor();

// Auto image pick based on item name
function pick_image_by_item_name(string $item_name): string {
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
    return 'placeholder_100.jpg';
}

$image_name = pick_image_by_item_name($item_name);
if (!file_exists("images/" . $image_name)) {
    $image_name = 'placeholder_100.jpg';
}

// Insert item
$queryInsert = "
    INSERT INTO shopping_list (itemName, quantity, category, status, imageName, price)
    VALUES (:itemName, :quantity, :category, :status, :imageName, :price)
";
$stmt = $db->prepare($queryInsert);
$stmt->bindValue(':itemName', $item_name);
$stmt->bindValue(':quantity', $quantity);
$stmt->bindValue(':category', $category);
$stmt->bindValue(':status', $status);
$stmt->bindValue(':imageName', $image_name);
$stmt->bindValue(':price', $price);
$stmt->execute();
$stmt->closeCursor();

$_SESSION["itemName"] = $item_name;
header("Location: add_confirmation.php");
exit;
?>
