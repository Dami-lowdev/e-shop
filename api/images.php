<?php
// Path to the image file
$imagePath = './images/desktop.png';

// Check if the file exists
if (file_exists($imagePath)) {
    // Set the Content-Type header to image/jpeg
    header('Content-Type: image/jpeg');
    
    // Read the image file and output its contents
    readfile($imagePath);
} else {
    // Handle the error - image file not found
    header('HTTP/1.0 404 Not Found');
    echo 'Image not found';
}
?>