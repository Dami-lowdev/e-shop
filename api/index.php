<?php

require_once __DIR__ . '/router.php';
require 'functions.php';
require 'mail.php';
require './controllers/users.php';
require './controllers/articles.php';
require './controllers/orders.php';
require './controllers/shipments.php';


get('/api', function () {
	jsonResponse("hello world");
});

/**
 * USER ROUTES
 */

get('/api/users', function () {
	$conn = connect();
	$users = getUsers($conn);
	jsonResponse($users);
});

post('/api/user/register', function () {
	$name = $_POST['name'];
	$email = $_POST['email'];
	$password = generateRandomString(9)."A";
	$hPassword = password_hash($password, PASSWORD_DEFAULT); // Hash the password
	$conn = connect();
	if (registerUser($conn, $name, $email, $hPassword)) {
		$res=sendEmail($email, "Temporary Password", "Your temporary password for the account ".$email."is ".$password."\n you will have to change it at the first connection");
		jsonResponse("User registered successfully!", 201);
	} else {
		jsonResponse("Failed to register user.", 501);
	}
});

post('/api/user/password', function () {

	// Define the default email
	//  $email = 'damslaning@gmail.com';

	$email = $_POST['email'];
	$newPassword = password_hash($_POST['new_password'], PASSWORD_DEFAULT); // Hash the password
	$conn = connect();
	if (modifyPassword($conn, $email, $newPassword)) {
		jsonResponse("Password modified successfully!", 201);
	} else {
		jsonResponse("Failed to modify password.", 501);
	}
});

post('/api/user/login', function () {
	$email = $_POST['email'];
	$password = $_POST['password'];
	$loginDate = date("Y-m-d H:i:s"); // Current date and time
	$conn = connect();
	if (logUser($conn, $email, $password, $loginDate)) {
		$user = getUserByEmail($conn, $email);
		jsonResponse($user, 201);
	} else {
		jsonResponse("Failed to log user.", 403);
	}
});

post('/api/user/savelogin', function () {
	try {
		$id=$_POST['id'];
		$screen=$_POST['screen'];
		$os=$_POST['os'];
		// $email = $_POST['email'];
		$conn = connect();
		if (insertLogin($conn, $id, $screen, $os)) {
			jsonResponse("Login saved successfully!", 201);
		} else {
			jsonResponse("Failed to log user.", 501);
		}
	} catch (Exception $e) {
        // Handle the exception
        echo "An error occurred: " . $e->getMessage();
		jsonResponse("error on save log user.", 501);
    }
});



post('/api/user/deletelogin', function () {
	$id=$_POST['id'];
	// $email = $_POST['email'];
	$conn = connect();
	if (deleteLoginById($conn, $id)) {
		jsonResponse("Login saved successfully!", 201);
	} else {
		jsonResponse("Failed to logout user.", 501);
	}
});

get('/api/user/login', function () {
	$conn = connect();
	$logins=getAllLogins($conn);
	if ($logins) {
		jsonResponse($logins, 201);
	} else {
		jsonResponse([], 501);
	}
});


/**
 * ARTICLES ROUTES
 */

get('/api/articles', function () {
	$conn = connect();
	$articles = getAllArticles($conn);
	jsonResponse($articles);
});

get('/api/article/$id', function ($id) {
	$conn = connect();
	$article = getArticleById($conn, $id);
	if (!$article) return jsonResponse("No article found", 404);
	else return jsonResponse($article);
});

post('/api/article/add', function () {
	// save images of the articles
	$paths = array();
	for ($i = 0; $i < 4; $i++) {
		if ($_FILES['image' . $i]) {
			$path = saveImage($_FILES['image' . $i]);
			array_push($paths, $path);
		} else array_push($paths, "");
	}
	$name = $_POST['name'];
	$quantity = $_POST['quantity'];
	$price = $_POST['price'];
	$description = $_POST['description'];
	$conn = connect();
	if (saveArticle($conn, $name, $quantity, $price, $paths[0], $paths[1], $paths[2], $paths[3], $description)) {
		jsonResponse("Article saved successfully!", 201);
	} else {
		jsonResponse("Failed to save article.", 501);
	}
});



/**
 * ORDERS ROUTES
 */

get('/api/orders', function () {
	$conn = connect();
	$orders = getAllOrders($conn);
	jsonResponse($orders);
});

get('/api/order/$id', function ($id) {
	$conn = connect();
	$order = getOrderById($conn, $id);
	if (!$order) return jsonResponse("No article found", 404);
	jsonResponse($order);
});

get('/api/orders/$userId', function ($userId) {
	$conn = connect();
	$order = getOrderByUserId($conn, $userId);
	jsonResponse($order);
});

post('/api/order', function () {
	$userId = $_POST['userId']; // ID of the user placing the order
	$articleId = $_POST['articleId']; // ID of the article to order
	$quantity = $_POST['quantity']; // Quantity to order
	$id = generateRandomString(20);
	$conn = connect();
	if (orderArticle($conn, $id, $userId, $articleId, $quantity)) {
		jsonResponse("Order placed successfully!", 201);
	} else {
		jsonResponse("Failed to place order.", 501);
	}
});

post('/api/orders', function () {
	$userId = $_POST['userId']; // ID of the user placing the order
	$number = $_POST['number'];
	$articles = array();
	for ($i = 0; $i < $number; $i++) {
		$articleId = $_POST['article' . $i];
		$quantity = $_POST['quantity' . $i];
		$id = generateRandomString(20);
		$newArticle = array('id' => $id, 'articleId' => $articleId, 'quantity' => $quantity);
		array_push($articles, $newArticle);
	}

	$conn = connect();
	if (orderArticles($conn, $userId, $articles)) {
		// jsonResponse($articles);
		jsonResponse("Order placed successfully!", 201);
	} else {
		jsonResponse("Failed to place order.", 501);
	}
});


/**
 * SHIPMENTS ROUTES
 */

get('/api/shipments', function () {
	$conn = connect();
	$orders = getAllShipments($conn);
	jsonResponse($orders);
});

get('/api/shipment/$id', function ($id) {
	$conn = connect();
	$order = getShipmentById($conn, $id);
	if (!$order) return jsonResponse("No shipment found", 404);
	jsonResponse($order);
});

get('/api/shipments/$userId', function ($userId) {
	$conn = connect();
	$order = getShipmentByUserId($conn, $userId);
	jsonResponse($order);
});

post('/api/shipment', function () {
	$userId = $_POST['userId'];
	$address = $_POST['userId']; // Shipping address
	$trackingNumber = $_POST['userId']; // Tracking number
	$carrier = $_POST['userId']; // Carrier
	$number = $_POST['number'];
	$date = $_POST['date'];
	$shipmentId = generateRandomString(20);
	$orders = array();
	for ($i = 0; $i < $number; $i++) {
		$order = $_POST['order' . $i];
		array_push($orders, $order);
	}
	// return jsonResponse($orders, 201);
	$conn = connect();
	if (createShipment($conn, $shipmentId, $userId, $orders, $address, $trackingNumber, $carrier, date('Y-m-d H:i:s', $date / 1000))) {
		jsonResponse("Shipment created successfully!", 201);
	} else {
		jsonResponse("Failed to create shipment.", 501);
	}
});



post('/api/command', function () {
	$userId = $_POST['userId'];
	$address = $_POST['address']; // Shipping address
	$trackingNumber = generateRandomString(15); // Tracking number
	$carrier = $_POST['carrier']; // Carrier
	$number = $_POST['number'];
	$date = $_POST['date'];
	$email = $_POST['email'];

	$shipmentId = generateRandomString(20);
	// save the articles ordered
	$articles = array();
	for ($i = 0; $i < $number; $i++) {
		$articleId = $_POST['article' . $i];
		$quantity = $_POST['quantity' . $i];
		$id = generateRandomString(20);
		$newArticle = array('id' => $id, 'articleId' => $articleId, 'quantity' => $quantity);
		array_push($articles, $newArticle);
	}
	$conn = connect();
	$user = getUserByEmail($conn, $email);

	// $res=sendCommandEmail($user, $articles, convertTimestampToDate($date), $address, $trackingNumber);
	// return jsonResponse($res);

	if (orderArticles($conn, $userId, $articles)) {
		if (createShipment($conn, $shipmentId, $userId, $articles, $address, $trackingNumber, $carrier, convertTimestampToDate($date))) {
			$user = getUserByEmail($conn, $email);
			sendCommandEmail($user, $articles, convertTimestampToDate($date), $address, $trackingNumber);
			jsonResponse("Command created successfully!", 201);
		} else {
			jsonResponse("Failed to create shipment.", 501);
		}
	} else {
		jsonResponse("Failed to place order.", 501);
	}
});




/**
 * IMAGES ROUTES
 */

get('/api/images/$desktop.png', function ($name) {
	// Path to the image file
	$imagePath = './images/' + $name;

	// Check if the file existsg
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
});


get('/api/email', function () {
	$res = sendEmail('aristote.djounda@gmail.com', "Test mail", "<h1>Hello test</h1>");
	jsonResponse($res);
});

get('/api/test', function () {
	$conn = connect();
	$user = getUserByEmail($conn, 'client@email.com');
	jsonResponse($user);
});
