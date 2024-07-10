<?php


function getUsers($conn)
{
    try {
        // SQL query to retrieve all users
        $sql = "SELECT * FROM users";
        $result = $conn->query($sql);

        // Array to store users
        $users = [];

        // Check if there are any users
        if ($result->num_rows > 0) {
            // Loop through each row and add user data to the array
            while ($row = $result->fetch_assoc()) {
                $users[] = $row;
            }
        }

        // Close connection
        $conn->close();

        // Return the array of users as JSON
        return $users;
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function registerUser($conn, $name, $email, $password)
{
    try {
        // Prepare the SQL statement to insert a new user
        $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("sss", $name, $email, $password);

        // Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Registration failed
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function modifyPassword($conn, $email, $newPassword)
{
    try {
        // Prepare the SQL statement to update the password for a user with the provided email
        $sql = "UPDATE users SET password = ? WHERE email = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        // Hash the new password
        // $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Bind the parameters
        $stmt->bind_param("ss", $newPassword, $email);

        // Execute the statement
        if ($stmt->execute()) {
            // Check the number of rows modified
            if ($stmt->affected_rows === 0) {
                // No rows were modified
                // echo "No password changed.";
                return false;
            } else {
                // Password modified successfully
                // echo "Password modified successfully.";
                markUserAsLogged($conn, $email);
                return true;
            }
        } else {
            // Password modification failed
            // echo "failed to modify password";
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function checkForLogin($conn, $email, $password)
{
    // Prepare an SQL statement to fetch the user with the provided email
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);

    // Execute the statement
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if a user was found
    if ($result->num_rows === 1) {
        // Fetch the user data
        $user = $result->fetch_assoc();

        // Verify the provided password with the hashed password in the database
        if (password_verify($password, $user['password'])) {
            // Password is correct, return user data or true for success
            return $user;
        } else {
            // Password is incorrect
            return false;
        }
    } else {
        // No user found with the provided email
        return false;
    }
}

function logUser($conn, $email, $password, $loginDate)
{
    try {
        if (!checkForLogin($conn, $email, $password)) return false;
        // Prepare the SQL statement to insert a login record for the user
        $sql = "INSERT INTO login (user_id, login_date) VALUES ((SELECT id FROM users WHERE email = ?), ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("ss", $email, $loginDate);
        return true;

        // // Execute the statement
        // if ($stmt->execute()) {
        //     // User logged successfully
        //     return true;
        // } else {
        //     // User log failed
        //     return false;
        // }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function markUserAsLogged($conn, $email)
{
    try {
        // Prepare the SQL statement to update the already_logged field
        $sql = "UPDATE users SET already_logged = 1 WHERE email = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("s", $email);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any row was affected
            if ($stmt->affected_rows > 0) {
                // echo "User marked as logged.";
                return true;
            } else {
                // echo "No user found with the given email.";
                return false;
            }
        } else {
            // Query execution failed
            // echo "Failed to update user.";
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getUserByEmail($conn, $email)
{
    try {
        // Prepare the SQL statement to fetch the user by email
        $sql = "SELECT * FROM users WHERE email = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bind_param("s", $email);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the user data
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user;
        } else {
            // No user found with the given email
            return null;
        }
    } catch (Exception $e) {
        // Handle the exception
        echo "An error occurred: " . $e->getMessage();
        return null;
    }
}

function insertLogin($conn, $user_id, $user_screen, $user_os, $online = true)
{
    try {
        // Prepare an SQL statement for safe insertion
        // echo "toto ".$user_screen;
        $stmt = $conn->prepare("INSERT INTO login (user_id, user_screen, user_os, online) VALUES (?, ?, ?, ?)");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            // die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        // Bind the parameters to the SQL query
        $stmt->bind_param('issi', $user_id, $user_screen, $user_os, $online);
        
        /// Execute the statement
        if ($stmt->execute()) {
            // Registration successful
            return true;
        } else {
            // Registration failed
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function deleteLoginById($conn, $id)
{
    try {
        // Prepare an SQL statement for safe deletion
        $stmt = $conn->prepare("UPDATE  login SET online=false WHERE user_id = ?");

        // Check if the statement was prepared successfully
        if ($stmt === false) {
            // die('prepare() failed: ' . htmlspecialchars($conn->error));
        }

        // Bind the parameter to the SQL query
        $stmt->bind_param('i', $id);

        // Execute the statement
        if ($stmt->execute()) {
            // Check if any row was affected
            if ($stmt->affected_rows > 0) {
                // echo "User Log out";
                return true;
            } else {
                // echo "No line modified";
                return false;
            }
        } else {
            // Query execution failed
            // echo "Log out failed";
            return false;
        }
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getAllLogins($conn)
{

    try {
        // SQL query to retrieve all logins
        $sql = "SELECT * FROM login";
        $result = $conn->query($sql);

        // Array to store logins
        $logins = [];

        // Check if there are any logins
        if ($result->num_rows > 0) {
            // Loop through each row and add login data to the array
            while ($row = $result->fetch_assoc()) {
                $logins[] = $row;
            }
        }

        // Close connection
        $conn->close();

        // Return the array of logins as JSON
        return $logins;
    } catch (Exception $e) {
        // Handle the exception
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}
