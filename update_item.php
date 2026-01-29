<?php
session_start();
require_once("database.php");

$item_id   = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
$item_name = filter_input(INPUT_POST, 'item_name');
$quantity  = filter_input(INPUT_POST, 'quantity');
$category  = filter_input(INPUT_POST, 'category');
$status    = filter_input(INPUT_POST, 'status');

if ($item_id == null || $item_name == null ||
    $quantity == null || $category == null || $status == null) {

    $_SESSION["update_error"] = "Invalid item data. Try again.";
    header("Location: index.php");
    exit();
}

$query = "
    UPDATE shopping_list
    SET itemName = :itemName,
        quantity = :quantity,
        category = :category,
        status = :status
    WHERE itemID = :itemID
";

$statement = $db->prepare($query);
$statement->bindValue(':itemName', $item_name);
$statement->bindValue(':quantity', $quantity);
$statement->bindValue(':category', $category);
$statement->bindValue(':status', $status);
$statement->bindValue(':itemID', $item_id);
$statement->execute();
$statement->closeCursor();

$_SESSION["itemName"] = $item_name;

header("Location: update_confirmation.php");
exit();
?>
