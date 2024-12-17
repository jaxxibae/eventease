<?php

require_once __DIR__ . '/guid.php';

function upload_file($file_content, $folder, $file_name): string|bool {
    if ($file_name === null) {
        $file_name = get_guid() . '.' . pathinfo($file_content["name"], PATHINFO_EXTENSION);
    }
    
    $target_dir = __DIR__ . "/../../assets/$folder/";
    $target_file = $target_dir . basename($file_name);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    $check = getimagesize($file_content["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        $uploadOk = 0;
    }

    // Check file size
    if ($file_content["size"] > 50000000) {
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        return false;
    } else {
        if (move_uploaded_file($file_content["tmp_name"], $target_file)) {
            return $file_name;
        } else {
            return false;
        }
    }
}

?>