<?php

function createShipment($conn, $id, $userId, $orders, $address, $trackingNumber, $carrier, $date) {
    // Prepare the SQL statement to insert a new shipment
    $sqlShipment = "INSERT INTO shipment (id, user_id, address, tracking_number, carrier, delivery_date) VALUES (?, ?, ?, ?, ?, ?)";
    
    // Prepare the statement
    $stmtShipment = $conn->prepare($sqlShipment);
    
    // Bind the parameters
    $stmtShipment->bind_param("sissss", $id, $userId, $address, $trackingNumber, $carrier, $date);
    
    // Execute the statement to insert the shipment
    if (!$stmtShipment->execute()) {
        // Failed to create shipment
        return false;
    }
    
    // Get the ID of the newly inserted shipment
    $shipmentId = $stmtShipment->insert_id;
    
    // Close the statement
    $stmtShipment->close();
    
    // Prepare the SQL statement to insert the links between shipment and orders
    $sqlShipmentOrders = "INSERT INTO shipment_orders (shipment_id, order_id) VALUES (?, ?)";
    
    // Prepare the statement
    $stmtShipmentOrders = $conn->prepare($sqlShipmentOrders);
    
    // Initialize a variable to store the result of each execution
    $success = true;
    
    // Bind the shipment ID and order ID for each order
    foreach ($orders as $order) {
        // Bind the parameters
        $stmtShipmentOrders->bind_param("ss", $id, $order['id']);
        
        // Execute the statement
        if (!$stmtShipmentOrders->execute()) {
            // If execution fails for any order, set $success to false
            $success = false;
        }
    }
    
    // Close the statement
    $stmtShipmentOrders->close();
    
    // Return the overall success status
    return $success;
}

function cancelShipment($conn, $shipmentId) {
    // Start a transaction
    $conn->begin_transaction();

    // Update the status of the shipment to "Canceled"
    $sqlUpdateShipmentStatus = "UPDATE shipment SET status = 'Canceled' WHERE id = ?";
    $stmtUpdateShipmentStatus = $conn->prepare($sqlUpdateShipmentStatus);
    $stmtUpdateShipmentStatus->bind_param("i", $shipmentId);

    // Execute the statement to update the shipment status
    if (!$stmtUpdateShipmentStatus->execute()) {
        // If execution fails, rollback the transaction and return false
        $conn->rollback();
        return false;
    }

    // Delete the associated records from the shipment_orders table
    $sqlDeleteShipmentOrders = "DELETE FROM shipment_orders WHERE shipment_id = ?";
    $stmtDeleteShipmentOrders = $conn->prepare($sqlDeleteShipmentOrders);
    $stmtDeleteShipmentOrders->bind_param("i", $shipmentId);

    // Execute the statement to delete the associated records
    if (!$stmtDeleteShipmentOrders->execute()) {
        // If execution fails, rollback the transaction and return false
        $conn->rollback();
        return false;
    }

    // Commit the transaction
    $conn->commit();

    // Shipment canceled successfully
    return true;
}

function getAllShipments($conn)
{
    try {
        // Prepare the SQL statement to select all orders
        $sql = "SELECT * FROM shipment";

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

function getShipmentById($conn, $shipmentId)
{
    try {
        // Prepare the SQL statement to select an shipment by its ID
        $sql = "SELECT * FROM shipment WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }

        // Bind the shipment ID parameter
        $stmt->bind_param("i", $shipmentId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if an shipment was found
        if ($result->num_rows > 0) {
            // Fetch the shipment details
            $shipment = $result->fetch_assoc();

            // Return the shipment details
            return $shipment;
        } else {
            // No shipment found with the given ID
            return null;
        }
    } catch (Exception $e) {
        // Handle any exceptions
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

function getShipmentByUserId($conn, $userId)
{
    try {
        // Prepare the SQL statement to select all shipments
        $sql = "SELECT * FROM shipment where user_id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Failed to prepare the SQL statement: " . $conn->error);
        }
        // Bind the shipment ID parameter
        $stmt->bind_param("i", $userId);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            // Initialize an array to store the shipments
            $shipments = [];

            // Fetch all shipments
            while ($row = $result->fetch_assoc()) {
                $shipments[] = $row;
            }

            // Return the shipments as an array
            return $shipments;
        } else {
            // No shipments found
            return [];
        }
    } catch (Exception $e) {
        // Handle any exceptions
        // echo "An error occurred: " . $e->getMessage();
        return false;
    }
}

?>
