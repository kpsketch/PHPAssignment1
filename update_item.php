<?php
    session_start();

    require_once('database.php');
    require_once('image_util.php');

    $item_id = filter_input(INPUT_POST, 'item_id', FILTER_VALIDATE_INT);

    $item_name = filter_input(INPUT_POST, 'item_name');
    $quantity  = filter_input(INPUT_POST, 'quantity');
    $category  = filter_input(INPUT_POST, 'category');
    $status    = filter_input(INPUT_POST, 'status');

    // NEW: checkbox to switch back to placeholder
    $use_placeholder = filter_input(INPUT_POST, 'use_placeholder');

    // Get the uploaded image (if any)
    $image = $_FILES['file1'];

    // Get current item record to check current image name
    $queryItems = '
        SELECT itemID, itemName, quantity, category, status, imageName 
        FROM shopping_list 
        WHERE itemID = :item_id';

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

    // helper to delete old images
    function delete_old_images($old_image_name, $base_dir) {
        if ($old_image_name != 'placeholder_100.jpg') {
            $old_base = substr($old_image_name, 0, strrpos($old_image_name, '_100'));
            $old_ext = substr($old_image_name, strrpos($old_image_name, '.'));
            $original = $old_base . $old_ext;
            $img100 = $old_base . '_100' . $old_ext;
            $img400 = $old_base . '_400' . $old_ext;

            foreach ([$original, $img100, $img400] as $file) {
                $path = $base_dir . $file;
                if (file_exists($path)) {
                    unlink($path);
                }
            }
        }
    }

    // ✅ If user selected placeholder, set image to placeholder and delete old images
    if ($use_placeholder == "1") {
        delete_old_images($old_image_name, $base_dir);
        $image_name = 'placeholder_100.jpg';
    }
    // ✅ Else if new image is uploaded, process it and delete old images
    else if ($image && $image['error'] == UPLOAD_ERR_OK) {

        $original_filename = basename($image['name']);
        $upload_path = $base_dir . $original_filename;
        move_uploaded_file($image['tmp_name'], $upload_path);

        process_image($base_dir, $original_filename);

        $dot_pos = strrpos($original_filename, '.');
        $new_image_name = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
        $image_name = $new_image_name;

        delete_old_images($old_image_name, $base_dir);
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
