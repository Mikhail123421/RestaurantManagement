<?php
include("dbConection.php");

if (isset($_POST['orders']) && isset($_POST['userId'])) {

    // Decode the JSON-encoded orders from the client side
    $orders = json_decode($_POST['orders'], true);
    $userId = $_POST['userId']; // Ensure the userId is passed correctly

    // Debug: Log received data for validation
    error_log("Received Orders: " . print_r($orders, true));
    error_log("User ID: " . $userId);

    // Check if orders is an array and contains data
    if (is_array($orders) && !empty($orders)) {
        try {
            $pdo->beginTransaction();

            // Prepare the query for inserting orders
            $query = "INSERT INTO orders (USER_ID, FOOD_ID, FOOD_NAME, QUANTITY, PRICE) VALUES (:user_id, :food_id, :food_name, :quantity, :price)";
            $stmt = $pdo->prepare($query);

            foreach ($orders as $order) {
                // Ensure the order contains the necessary data
                if (isset($order['foodID'], $order['foodName'], $order['quantity'], $order['price'], $order['userId'])) {
                    // Insert order into the database
                    $stmt->execute([
                        ':user_id' => $order['userId'],          // User ID
                        ':food_id' => $order['foodID'],          // Food ID
                        ':food_name' => $order['foodName'],      // Food Name
                        ':quantity' => $order['quantity'],       // Quantity
                        ':price' => $order['price']              // Price
                    ]);
                } else {
                    throw new Exception('Missing data in order.');
                }
            }

            $pdo->commit();  // Commit the transaction

            echo json_encode(['message' => 'Orders added successfully.']);
        } catch (Exception $e) {
            // Rollback the transaction on error
            $pdo->rollBack();
            echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
        }
    } else {
        echo json_encode(['message' => 'Invalid or empty orders data.']);
    }
} else {
    echo json_encode(['message' => 'Missing orders or userId']);
}
?>
