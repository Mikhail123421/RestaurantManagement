<?php
// Path to the JSON file
$jsonFilePath = 'foodList.json';

// Ensure the JSON file exists
if (!file_exists($jsonFilePath)) {
    file_put_contents($jsonFilePath, json_encode([]));
}

// Read and decode the JSON file
$jsonContent = file_get_contents($jsonFilePath);
$menuItems = json_decode($jsonContent, true);

// Check if the POST request has an action
if (isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'add') {
        // Add a new food item
        if (isset($_POST['name'], $_POST['price'])) {
            $name = $_POST['name'];
            $price = (int)$_POST['price'];

            // Generate a new ID
            $newId = !empty($menuItems) ? end($menuItems)['id'] + 1 : 1;

            // Create the new food item
            $newFood = [
                "id" => $newId,
                "name" => $name,
                "price" => $price
            ];

            // Append and save the new food item
            $menuItems[] = $newFood;
            file_put_contents($jsonFilePath, json_encode($menuItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            echo json_encode(["message" => "افزوده شد"]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input."]);
            exit;
        }
    } elseif ($action === 'delete') {
        // Delete a food item
        if (isset($_POST['id'])) {
            $id = (int)$_POST['id'];

            // Filter out the item to delete
            $menuItems = array_filter($menuItems, function ($item) use ($id) {
                return $item['id'] !== $id;
            });

            // Save the updated menu
            file_put_contents($jsonFilePath, json_encode(array_values($menuItems), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            echo json_encode(["message" => "غذا حذف شد"]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input."]);
            exit;
        }
    } elseif ($action === 'edit') {
        // Edit a food item
        if (isset($_POST['id'], $_POST['name'], $_POST['price'])) {
            $id = (int)$_POST['id'];
            $name = $_POST['name'];
            $price = (int)$_POST['price'];

            // Find and update the food item
            foreach ($menuItems as &$item) {
                if ($item['id'] === $id) {
                    $item['name'] = $name;
                    $item['price'] = $price;
                    break;
                }
            }

            // Save the updated menu
            file_put_contents($jsonFilePath, json_encode($menuItems, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

            echo json_encode(["message" => "غذا ویرایش شد"]);
            exit;
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Invalid input."]);
            exit;
        }
    }
}

// Return error response if no valid action is provided
http_response_code(400);
echo json_encode(["message" => "Invalid action."]);
