<?php
$target_dir = "img/"; // Make sure this folder exists and has appropriate permissions
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if the file is a real file
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size (optional)
if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

// Allow certain file formats (optional)
if (!in_array($fileType, array('jpg', 'jpeg', 'png', 'gif', 'pdf'))) { 
   echo "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
    $uploadOk = 0;
}

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// If everything is ok, try to upload the file
} else {
   if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . htmlspecialchars(basename($_FILES["fileToUpload"]["name"])) . " has been uploaded.";
        // Now resize the image
        resizeImage($target_file, 480, 480); // Resize to 800x600 pixels
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

function resizeImage($filename, $width, $height) {
    // Get the image size
    list($originalWidth, $originalHeight) = getimagesize($filename);

    // Create a new image from file
    $image = imagecreatefromstring(file_get_contents($filename));

    // Create a new true color image with specified dimensions
    $resizedImage = imagecreatetruecolor($width, $height);

    // Resize the original image into the new image
    imagecopyresampled($resizedImage, $image, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

    // Save the resized image back to the file
    imagejpeg($resizedImage, $filename);

    // Free up memory
    imagedestroy($image);
    imagedestroy($resizedImage);
}
?>
