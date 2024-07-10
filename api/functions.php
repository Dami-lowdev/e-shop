<?php


function connect()
{
    $servername = "localhost";
    $username = "rooter";
    $password = "password";
    $database = "webshop";
    try {
        // Create connection
        $conn = new mysqli($servername, $username, $password, $database);

        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // echo "Connected successfully <br>";
        // Example usage:
        return $conn;
    } catch (Exception $e) {
        // Handle the exception
        return false;
    }
}

function jsonResponse($data, $status = 200)
{
    echo json_encode($data);


    // Set the content type to JSON
    header('Content-Type: application/json');

    // Set the cross-origin policy to allow all origins
    header('Access-Control-Allow-Origin: *');

    header('HTTP/1.0 ' . strval($status));
}

function generateRandomString($length = 15)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

function saveImage($files)
{
    // Get file details
    $fileName = $files['name'];
    $fileTmpPath = $files['tmp_name'];
    $fileSize = $files['size'];
    $fileType = $files['type'];
    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
    // Define the upload directory
    $uploadDir = 'images/';

    // Create the upload directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    // Define the path where the file will be saved
    $uploadFilePath = $uploadDir . basename(generateRandomString() . '.' . $fileExtension);
    // Move the file from the temporary directory to the target directory
    if (move_uploaded_file($fileTmpPath, $uploadFilePath)) {
        return $uploadFilePath;
    } else {
        return false;
    }
}

function convertTimestampToDate($timestamp)
{
    // Check if the provided timestamp is valid
    if (!is_numeric($timestamp)) {
        throw new InvalidArgumentException("Invalid timestamp provided.");
    }

    // Convert the timestamp to a formatted date string
    $date = date('Y-m-d H:i:s', $timestamp);

    return $date;
}

function hello()
{
    echo "Hello word <br>";
}
