<?php

function saveArticle($conn, $name, $quantity, $price, $image1, $image2, $image3, $image4, $description) {
    try {
        // Prepare the SQL statement to insert a new article
        $sql = "INSERT INTO article (name, quantity, price, image1, image2, image3, image4, description) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Bind the parameters
        $stmt->bind_param("siisssss", $name, $quantity, $price, $image1, $image2, $image3, $image4, $description);
        
        // Execute the statement
        if ($stmt->execute()) {
            // Article saved successfully
            return true;
        } else {
            // Article save failed
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}


function getAllArticles($conn) {
    try {
        // Prepare the SQL statement to retrieve all articles
        $sql = "SELECT * FROM article";
        
        // Execute the query
        $result = $conn->query($sql);
        
        // Check if there are any articles
        if ($result->num_rows > 0) {
            // Array to store articles
            $articles = [];
            
            // Loop through each row and add article data to the array
            while ($row = $result->fetch_assoc()) {
                $articles[] = $row;
            }
            
            // Free the result set
            $result->free();
            
            // Return the array of articles
            return $articles;
        } else {
            // No articles found
            return [];
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getArticleById($conn, $articleId) {
    try {
        // Prepare the SQL statement to retrieve the article by ID
        $sql = "SELECT * FROM article WHERE id = ?";
        
        // Prepare the statement
        $stmt = $conn->prepare($sql);
        
        // Bind the parameter
        $stmt->bind_param("i", $articleId);
        
        // Execute the statement
        $stmt->execute();
        
        // Get the result
        $result = $stmt->get_result();
        
        // Check if the article exists
        if ($result->num_rows == 1) {
            // Fetch the article data
            $article = $result->fetch_assoc();
            
            // Free the result set
            $result->free();
            
            // Return the article data
            return $article;
        } else {
            // No article found
            return null;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

