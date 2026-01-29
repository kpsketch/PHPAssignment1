<?php
session_start();

$item_name = filter_input(INPUT_POST, 'item_name');
$quantity  = filter_input(INPUT_POST, 'quantity');
$category  = filter_input(INPUT_POST, 'category');
$status    = filter_input(INPUT_POST, 'status');

require("database.php");

if ($item_name == null || $quantity == null || $category == null || $status == null) {
    $_SESSION["add_error"] = "Invalid item data. Check all fields and try again.";
    header("Location: error.php");
    exit();
}

$query = "
    INSERT INTO shopping_list (itemName, quantity, category, status)
    VALUES (:itemName, :quantity, :category, :status)
";

$statement = $db->prepare($query);
$statement->bindValue(':itemName', $item_name);
$statement->bindValue(':quantity', $quantity);
$statement->bindValue(':category', $category);
$statement->bindValue(':status', $status);
$statement->execute();
$statement->closeCursor();

$_SESSION["itemName"] = $item_name;
header("Location: add_confirmation.php");
exit();
?>
