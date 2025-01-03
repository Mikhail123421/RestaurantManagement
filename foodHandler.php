<?php

include("dbConection.php");

class FoodManager {
    private $pdo;

    // Constructor to initialize the PDO instance
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Load the food menu
    public function loadFood() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM foodlist ORDER BY ID ASC");
            $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($foods);
        } catch (PDOException $e) {
        
            echo json_encode(['message' => 'Failed to load menu: ' . $e->getMessage()]);
        }
    }

    // Add a new food item
    public function addFood($name, $price) {
        if (empty($name) || empty($price)) {
            http_response_code(400);
            echo json_encode(['message' => 'Name and price are required.']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("INSERT INTO foodlist (name, price) VALUES (:name, :price)");
            $stmt->execute(['name' => $name, 'price' => $price]);
            echo json_encode(['message' => 'Food added successfully.']);
        } catch (PDOException $e) {
        
            echo json_encode(['message' => 'Failed to add food: ' . $e->getMessage()]);
        }
    }

    // Edit a food item
    public function editFood($id, $name, $price) {
        if (empty($id) || empty($name) || empty($price)) {
            http_response_code(400);
            echo json_encode(['message' => 'ID, name, and price are required.']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE foodlist SET name = :name, price = :price WHERE ID = :id");
            $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price]);
            echo json_encode(['message' => 'Food updated successfully.']);
        } catch (PDOException $e) {
        
            echo json_encode(['message' => 'Failed to update food: ' . $e->getMessage()]);
        }
    }

    // Delete a food item
    public function deleteFood($id) {
        if (empty($id)) {
            http_response_code(400);
            echo json_encode(['message' => 'ID is required.']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM foodlist WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            echo json_encode(['message' => 'Food deleted successfully.']);
        } catch (PDOException $e) {
        
            echo json_encode(['message' => 'Failed to delete food: ' . $e->getMessage()]);
        }
    }
}

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // Instantiate the FoodManager
    $foodManager = new FoodManager($pdo);

    switch ($action) {
        case 'loadFood':
            $foodManager->loadFood();
            break;
        case 'add':
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $foodManager->addFood($name, $price);
            break;
        case 'edit':
            $id = $_POST['id'] ?? 0;
            $name = $_POST['name'] ?? '';
            $price = $_POST['price'] ?? 0;
            $foodManager->editFood($id, $name, $price);
            break;
        case 'delete':
            $id = $_POST['id'] ?? 0;
            $foodManager->deleteFood($id);
            break;
        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid action.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Request method not allowed.']);
}

?>
