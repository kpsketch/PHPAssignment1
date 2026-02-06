<?php
    session_start();

    require_once('database.php');
    require_once('image_util.php');

    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

    $item_name = filter_input(INPUT_POST, 'item_name');
    $quantity  = filter_input(INPUT_POST, 'quantity');
    $category  = filter_input(INPUT_POST, 'category');
    $status    = filter_input(INPUT_POST, 'status');

    // Get the uploaded image (if any)
    $image = $_FILES['file1'];

    // Get current item record to check current image name
    $queryItems = '
        SELECT itemID, itemName, quantity, category, status, imageName FROM shopping_list WHERE itemID = :item_id';

    $statement = $db->prepare($queryItems);
    $statement->bindValue(':item_id', $item_id);
    $statement->execute();
    $item = $statement->fetch();
    $statement->closeCursor();

    $old_image_name = $item['imageName'];
    $base_dir = 'images/';
    $image_name = $old_image_name;

    // Check for duplicate item name
    $queryItems = '
        SELECT itemID, itemName FROM shopping_list';

    $statement = $db->prepare($queryItems);
    $statement->execute();
    $items = $statement->fetchAll();
    $statement->closeCursor();

    foreach ($items as $row) {
        if ($item_name == $row["itemName"] && $item_id != $row["itemID"]) {
            $_SESSION["add_error"] = "Invalid data, Duplicate Item Name. Try again.";
            $url = "error.php";
            header("Location: " . $url);
            die();  
        }
    }

    // Validate input
    if ($item_name == null || $quantity == null || $category == null || $status == null) {
        $_SESSION["add_error"] = "Invalid item data, Check all fields and try again.";
        $url = "error.php";
        header("Location: " . $url);
        die();  
    }

    // If new image is uploaded
    if ($image && $image['error'] == UPLOAD_ERR_OK) {

        // process new image
        $original_filename = basename($image['name']);
        $upload_path = $base_dir . $original_filename;
        move_uploaded_file($image['tmp_name'], $upload_path);        

        process_image($base_dir, $original_filename);        

        // save _100 version in DB
        $dot_pos = strrpos($original_filename, '.');
        $new_image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
        $image_name = $new_image_name;

        // delete old images if not placeholder
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
    }

    // Update Item
    $query = '
        UPDATE shopping_list
        SET itemName = :itemName,
            quantity = :quantity,
            category = :category,
            status = :status,
            imageName = :imageName
        WHERE itemID = :itemID
    ';

    $statement = $db->prepare($query);
    $statement->bindValue(':itemName', $item_name);
    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':imageName', $image_name);
    $statement->bindValue(':itemID', $item_id);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["itemName"] = $item_name;
    $url = "update_confirmation.php";
    header("Location: " . $url);
    die();

?>
