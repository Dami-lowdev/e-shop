<?php

function orderArticle($conn,$id, $userId, $articleId, $quantity)
{
    try {
        // Check if the article exists and has sufficient quantity
        $article = getArticleById($conn, $articleId);
        if (!$article || $article['quantity'] < $quantity) {
            // echo "the article exist";
            return false; // Article not found or insufficient quantity
        }

        // Deduct the ordered quantity from the available quantity
        $newQuantity = $article['quantity'] - $quantity;

        // Prepare the SQL statement to update the quantity of the ordered article
        $sqlUpdate = "UPDATE article SET quantity = ? WHERE id = ?";

        // Prepare the statement
        $stmtUpdate = $conn->prepare($sqlUpdate);

        // Bind the parameters
        $stmtUpdate->bind_param("ii", $newQuantity, $articleId);

        // Execute the statement to update the quantity
        if (!$stmtUpdate->execute()) {
            return false; // Failed to update quantity
        }

        // Prepare the SQL statement to insert the order into the orders table
        $sqlInsert = "INSERT INTO orders (id, user_id, article_id, quantity) VALUES (?, ?, ?, ?)";

        // Prepare the statement
        $stmtInsert = $conn->prepare($sqlInsert);

        // Bind the parameters
        $stmtInsert->bind_param("siii",$id, $userId, $articleId, $quantity);

        // Execute the statement to insert the order
        if ($stmtInsert->execute()) {
            // Order placed successfully
            return true;
        } else {
            // Failed to place order
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function orderArticles($conn, $userId, $articles)
{
    try {
        // Array to store the results of each order attempt
        $results = array();

        // Iterate over each article in the collection
        foreach ($articles as $article) {
            // Extract article details
            $articleId = $article['articleId'];
            $quantity = $article['quantity'];
            $id=$article['id'];

            // Attempt to order the article
            $result = orderArticle($conn, $id, $userId, $articleId, $quantity);

            // Store the result
            $results[$articleId] = $result;
        }

        // Return the results array
        return $results;
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function deleteOrder($conn, $orderId)
{
    try {
        // Prepare the SQL statement to delete the order
        $sql = "DELETE FROM orders WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameter
        $stmt->bind_param("i", $orderId);

        // Execute the statement
        if ($stmt->execute()) {
            // Order deleted successfully
            return true;
        } else {
            // Failed to delete order
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getAllOrders($conn)
{
    try {
        // Prepare the SQL statement to select all orders
        $sql = "SELECT * FROM orders";

        // Execute the query
        $result = $conn->query($sql);

        // Check if there are results
        if ($result->num_rows > 0) {
            // Initialize an array to store the orders
            $orders = [];

            // Fetch all orders
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }

            // Return the orders as an array
            return $orders;
        } else {
            // No orders found
            return [];
        }
    } catch (Exception $e) {
        // Handle any exceptions
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getOrderById($conn, $orderId)
{
    try {
        // Prepare the SQL statement to select an order by its ID
        $sql = "SELECT * FROM orders WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }

        // Bind the order ID parameter
        $stmt->bind_param("s", $orderId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if an order was found
        if ($result->num_rows > 0) {
            // Fetch the order details
            $order = $result->fetch_assoc();

            // Return the order details
            return $order;
        } else {
            // No order found with the given ID
            return null;
        }
    } catch (Exception $e) {
        // Handle any exceptions
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getOrderByUserId($conn, $UserId)
{
    try {
        // Prepare the SQL statement to select all orders
        $sql = "SELECT * FROM orders where user_id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }
        // Bind the order ID parameter
        $stmt->bind_param("s", $UserId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            // Initialize an array to store the orders
            $orders = [];

            // Fetch all orders
            while ($row = $result->fetch_assoc()) {
                $orders[] = $row;
            }

            // Return the orders as an array
            return $orders;
        } else {
            // No orders found
            return [];
        }
    } catch (Exception $e) {
        // Handle any exceptions
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}
