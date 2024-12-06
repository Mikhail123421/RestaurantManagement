

<?php
//#1 work with json
// // Path to the JSON file
// $jsonFilePath = 'foodList.json';

// // Ensure the JSON file exists
// if (!file_exists($jsonFilePath)) {
//     file_put_contents($jsonFilePath, json_encode([]));
// }

// // Read and decode the JSON file
// $jsonContent = file_get_contents($jsonFilePath);
// $menuItems = json_decode($jsonContent, true);

// // Check if the POST request has an action
// if (isset($_POST['action'])) {
//     $action = $_POST['action'];

//     if ($action === 'add') {
//         // Add a new food item
//         if (isset($_POST['name'], $_POST['price'])) {
//             $name = $_POST['name'];
//             $price = (int)$_POST['price'];

//             // Generate a new ID
//             $newId = !empty($menuItems) ? end($menuItems)['id'] + 1 : 1;

//             // Create the new food item
//             $newFood = [
//                 "id" => $newId,
//                 "name" => $name,
//                 "price" => $price
//             ];

//             // Append and save the new food item
//             $menuItems[] = $newFood;
//             file_put_contents($jsonFilePath, json_encode($menuItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//             echo json_encode(["message" => "افزوده شد"]);
//             exit;
//         } else {
//             http_response_code(400);
//             echo json_encode(["message" => "Invalid input."]);
//             exit;
//         }
//     } elseif ($action === 'delete') {
//         // Delete a food item
//         if (isset($_POST['id'])) {
//             $id = (int)$_POST['id'];

//             // Filter out the item to delete
//             $menuItems = array_filter($menuItems, function ($item) use ($id) {
//                 return $item['id'] !== $id;
//             });

//             // Save the updated menu
//             file_put_contents($jsonFilePath, json_encode(array_values($menuItems), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//             echo json_encode(["message" => "غذا حذف شد"]);
//             exit;
//         } else {
//             http_response_code(400);
//             echo json_encode(["message" => "Invalid input."]);
//             exit;
//         }
//     } elseif ($action === 'edit') {
//         // Edit a food item
//         if (isset($_POST['id'], $_POST['name'], $_POST['price'])) {
//             $id = (int)$_POST['id'];
//             $name = $_POST['name'];
//             $price = (int)$_POST['price'];

//             // Find and update the food item
//             foreach ($menuItems as &$item) {
//                 if ($item['id'] === $id) {
//                     $item['name'] = $name;
//                     $item['price'] = $price;
//                     break;
//                 }
//             }

//             // Save the updated menu
//             file_put_contents($jsonFilePath, json_encode($menuItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

//             echo json_encode(["message" => "غذا ویرایش شد"]);
//             exit;
//         } else {
//             http_response_code(400);
//             echo json_encode(["message" => "Invalid input."]);
//             exit;
//         }
//     }
// }

// // Return error response if no valid action is provided
// http_response_code(400);
// echo json_encode(["message" => "Invalid action."]);

#2 work with mysql 

include("dbConection.php");


// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'loadFood':
            loadFood($pdo);
            break;
        case 'add':
            addFood($pdo);
            break;
        case 'edit':
            editFood($pdo);
            break;
        case 'delete':
            deleteFood($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid action.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Request method not allowed.']);
}

// Function to load the food menu
function loadFood($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM foodlist ORDER BY ID ASC");
        $foods = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($foods);
    } 
    catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to load menu: ' . $e->getMessage()]);
    }
}

// Function to add a new food item
function addFood($pdo)
{
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;

    if (empty($name) || empty($price)) {
        http_response_code(400);
        echo json_encode(['message' => 'Name and price are required.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO foodlist (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => $name, 'price' => $price]);
        echo json_encode(['message' => 'Food added successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to add food: ' . $e->getMessage()]);
    }
}

// Function to edit a food item
function editFood($pdo)
{
    $id = $_POST['id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;

    if (empty($id) || empty($name) || empty($price)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID, name, and price are required.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("UPDATE foodlist SET name = :name, price = :price WHERE ID = :id");
        $stmt->execute(['id' => $id, 'name' => $name, 'price' => $price]);
        echo json_encode(['message' => 'Food updated successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update food: ' . $e->getMessage()]);
    }
}

// Function to delete a food item
function deleteFood($pdo)
{
    $id = $_POST['id'] ?? 0;

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID is required.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM foodlist WHERE ID = :id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['message' => 'Food deleted successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete food: ' . $e->getMessage()]);
    }
}
?>
