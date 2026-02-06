<?php
    require_once('database.php');

    // get data from the form
    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);
    
    // Get current item record to check current image name
    $queryItems = '
        SELECT itemID, itemName, imageName FROM shopping_list WHERE itemID = :item_id';

    $statement = $db->prepare($queryItems);
    $statement->bindValue(':item_id', $item_id);
    $statement->execute();
    $item = $statement->fetch();
    $statement->closeCursor();

    $old_image_name = $item['imageName'];
    $base_dir = 'images/';

    if($old_image_name != 'placeholder_100.jpg') {
        $old_base = substr($old_image_name, 0, strrpos($old_image_name, '_100'));
        $old_ext = substr($old_image_name, strrpos($old_image_name, '.'));
        $original = $old_base . $old_ext;
        $img100 = $old_base . '_100' . $old_ext;
        $img400 = $old_base . '_400' . $old_ext;

        foreach([$original, $img100, $img400] as $file) {
            $path = $base_dir . $file;
            if(file_exists($path)) {
                unlink($path);
            }
        }
    }

    if ($item_id != false) {
        // delete item from the database
        $query = 'DELETE FROM shopping_list WHERE itemID = :item_id';

        $statement = $db->prepare($query);
        $statement->bindValue(':item_id', $item_id);

        $statement->execute();
        $statement->closeCursor();
    }

    // reload the index page
    $url = "index.php";
    header("Location: " . $url);
    die();

?>
