<?php
    session_start();

    $item_name = filter_input(INPUT_POST, 'item_name');
    $quantity  = filter_input(INPUT_POST, 'quantity');
    $category  = filter_input(INPUT_POST, 'category');
    $status    = filter_input(INPUT_POST, 'status');
    $image = $_FILES['file1'];   

    require_once('database.php');
    require_once('image_util.php');

    $base_dir = 'images/';

    // Check for duplicate item name (same style as teacher duplicate email)
    $queryItems = '
        SELECT itemName FROM shopping_list';

    $statement = $db->prepare($queryItems);
    $statement->execute();
    $items = $statement->fetchAll();
    $statement->closeCursor();

    foreach ($items as $item) {
        if ($item_name == $item["itemName"]) {
            $_SESSION["add_error"] = "Invalid data, Duplicate Item Name. Try again.";
            $url = "error.php";
            header("Location: " . $url);
            die();  
        }
    }

    if ($item_name == null || $quantity == null || $category == null || $status == null) {
        $_SESSION["add_error"] = "Invalid item data, Check all fields and try again.";
        $url = "error.php";
        header("Location: " . $url);
        die();  
    }

    $image_name = ''; // default empty

    // ******* Image Upload *******

    if ($image && $image['error'] == UPLOAD_ERR_OK) {
        // process new image
        $original_filename = basename($image['name']);
        $upload_path = $base_dir . $original_filename;
        move_uploaded_file($image['tmp_name'], $upload_path);

        process_image($base_dir, $original_filename);

        // save _100 version in DB
        $dot_pos = strpos($original_filename, '.');
        $name_100 = substr($original_filename, 0, $dot_pos) . '_100' . substr($original_filename, $dot_pos);
        $image_name = $name_100;
    }
    else {
        // Use placeholder
        $placeholder = 'placeholder.jpg';
        $placeholder_100 = 'placeholder_100.jpg';
        $placeholder_400 = 'placeholder_400.jpg';

        if (!file_exists($base_dir . $placeholder_100) || !file_exists($base_dir . $placeholder_400)) {
            process_image($base_dir, $placeholder);
        }

        $image_name = $placeholder_100;
    }

    // Add Item
    $query = 'INSERT INTO shopping_list (itemName, quantity, category, status, imageName) 
        VALUES (:itemName, :quantity, :category, :status, :imageName)';

    $statement = $db->prepare($query);
    $statement->bindValue(':itemName', $item_name);
    $statement->bindValue(':quantity', $quantity);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':status', $status);
    $statement->bindValue(':imageName', $image_name);
    $statement->execute();
    $statement->closeCursor();

    $_SESSION["itemName"] = $item_name;
    $url = "add_confirmation.php";
    header("Location: " . $url);
    die();

?>
