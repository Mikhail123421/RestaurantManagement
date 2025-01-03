<?php

include("dbConection.php");



class OrderHandler {
    private $pdo;

    // Constructor receives PDO instance from the Database class
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Process orders method
    public function processOrders($orders, $userId) {
        if (!is_array($orders) || empty($orders)) {
            throw new Exception('Invalid or empty orders data.');
        }

        $this->pdo->beginTransaction();
        try {
            // Generate a unique order number
            $orderNumber = uniqid('ORD');

            // Prepare the query for inserting orders
            $query = "INSERT INTO orders (ORDER_NUMBER, USER_ID, FOOD_ID, FOOD_NAME, QUANTITY, PRICE) 
                      VALUES (:order_number, :user_id, :food_id, :food_name, :quantity, :price)";
            $stmt = $this->pdo->prepare($query);

            foreach ($orders as $order) {
                if (!isset($order['foodID'], $order['foodName'], $order['quantity'], $order['price'])) {
                    throw new Exception('Missing data in order.');
                }

                // Execute the query for each order
                $stmt->execute([
                    ':order_number' => $orderNumber,
                    ':user_id'      => $userId,
                    ':food_id'      => $order['foodID'],
                    ':food_name'    => $order['foodName'],
                    ':quantity'     => $order['quantity'],
                    ':price'        => $order['price']
                ]);
            }

            // Commit the transaction
            $this->pdo->commit();

            // Return the order number and success message
            return ['message' => 'Orders added successfully.', 'orderNumber' => $orderNumber];
        } catch (Exception $e) {
            // Rollback the transaction in case of an error
            $this->pdo->rollBack();
            throw $e;
        }
    }
}


try {
    // Initialize the Database
    $db = new Database();
    $pdo = $db->getConnection(); // Get PDO instance

    // Initialize the OrderHandler
    $orderHandler = new OrderHandler($pdo);

    // Check for POST data
    if (isset($_POST['orders']) && isset($_POST['userId'])) {
        $orders = json_decode($_POST['orders'], true); // Decode the orders
        $userId = $_POST['userId']; // User ID from POST data

        // Process orders
        $result = $orderHandler->processOrders($orders, $userId);

        // Send a JSON response
        echo json_encode($result);
    } else {
        echo json_encode(['message' => 'Missing orders or userId']);
    }
} catch (Exception $e) {
    echo json_encode(['message' => 'Error: ' . $e->getMessage()]);
}


?>
