<?php
require_once "functions.php";
try {

    echo "hello";
    hello();
} catch (Exception $e) {
    // Handle the exception
    echo "An error occurred: " . $e->getMessage();
}

// Example usage:
// Assuming $conn is your database connection object
$name = "John Doe";
$email = "john@example.com";
$password = password_hash("password123", PASSWORD_DEFAULT); // Hash the password
if (registerUser($conn, $name, $email, $password)) {
    echo "User registered successfully!";
} else {
    echo "Failed to register user.";
}


// Example usage:
// Assuming $conn is your database connection object
$email = "john@example.com";
$newPassword = "new_password123";
if (modifyPassword($conn, $email, $newPassword)) {
    echo "Password modified successfully!";
} else {
    echo "Failed to modify password.";
}


// Example usage:
// Assuming $conn is your database connection object
$email = "john@example.com";
$loginDate = date("Y-m-d H:i:s"); // Current date and time
if (logUser($conn, $email, $password, $loginDate)) {
    echo "User logged successfully!";
} else {
    echo "Failed to log user.";
}



// Example usage:
// Assuming $conn is your database connection object
$name = "Product Name";
$quantity = 10;
$price = 99.99;
$image1 = "image1.jpg";
$image2 = "image2.jpg";
$image3 = "image3.jpg";
$image4 = "image4.jpg";
$description = "Product description";
if (saveArticle($conn, $name, $quantity, $price, $image1, $image2, $image3, $image4, $description)) {
    echo "Article saved successfully!";
} else {
    echo "Failed to save article.";
}

// Example usage:
// Assuming $conn is your database connection object
$articles = getAllArticles($conn);
foreach ($articles as $article) {
    echo "Name: " . $article['name'] . ", Quantity: " . $article['quantity'] . ", Price: " . $article['price'] . "<br>";
}


// Example usage:
// Assuming $conn is your database connection object
$articleId = 1; // ID of the article to retrieve
$article = getArticleById($conn, $articleId);
if ($article) {
    echo "Name: " . $article['name'] . ", Quantity: " . $article['quantity'] . ", Price: " . $article['price'];
} else {
    echo "Article not found.";
}





// Example usage:
// Assuming $conn is your database connection object
$userId = 1; // ID of the user placing the order
$articleId = 1; // ID of the article to order
$quantity = 3; // Quantity to order
if (orderArticle($conn, $userId, $articleId, $quantity)) {
    echo "Order placed successfully!";
} else {
    echo "Failed to place order.";
}

// Example usage:
// Assuming $conn is your database connection object
$orderId = 1; // ID of the order to delete
if (deleteOrder($conn, $orderId)) {
    echo "Order deleted successfully!";
} else {
    echo "Failed to delete order.";
}

// Example usage:
// Assuming $conn is your database connection object
$userId = 1; // ID of the user placing the order
$articles = array(
    array('id' => 1, 'quantity' => 3), // Article ID and quantity
    array('id' => 2, 'quantity' => 2)
);

$results = orderArticles($conn, $userId, $articles);

// Output the results
foreach ($results as $articleId => $result) {
    if ($result) {
        echo "Order placed successfully for article ID $articleId!<br>";
    } else {
        echo "Failed to place order for article ID $articleId.<br>";
    }
}





// Example usage:
// Assuming $conn is your database connection object
$userId = 1; // ID of the user associated with the shipment
$orders = array(1, 2, 3); // Array of order IDs associated with the shipment
$address = "123 Main St, Anytown, USA"; // Shipping address
$trackingNumber = "1234567890"; // Tracking number
$carrier = "UPS"; // Carrier
if (createShipment($conn, $userId, $orders, $address, $trackingNumber, $carrier)) {
    echo "Shipment created successfully!";
} else {
    echo "Failed to create shipment.";
}

// Example usage:
// Assuming $conn is your database connection object
$shipmentId = 1; // ID of the shipment to cancel
if (cancelShipment($conn, $shipmentId)) {
    echo "Shipment canceled successfully!";
} else {
    echo "Failed to cancel shipment.";
}