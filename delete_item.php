<?php
require_once('database.php');

// get data from the form
$item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

// validate input
if ($item_id != false) {

    // delete item from shopping_list table
    $query = 'DELETE FROM shopping_list WHERE itemID = :item_id';

    $statement = $db->prepare($query);
    $statement->bindValue(':item_id', $item_id);

    $statement->execute();
    $statement->closeCursor();
}

// reload home page
header("Location: index.php");
exit();
?>
