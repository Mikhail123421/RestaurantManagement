<?php
include("dbConection.php");

// Handle the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'loadUsers':
            loadUsers($pdo);
            break;
        case 'edit':
            editUser($pdo);
            break;
        case 'delete':
            deleteUser($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['message' => 'Invalid action.']);
    }
} else {
    http_response_code(405);
    echo json_encode(['message' => 'Request method not allowed.']);
}

// Function to load the user list
function loadUsers($pdo)
{
    try {
        $stmt = $pdo->query("SELECT * FROM users ORDER BY ID ASC");
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
    } 
    catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to load users: ' . $e->getMessage()]);
    }
}

// Function to edit a user
function editUser($pdo)
{
    // گرفتن مقادیر از درخواست POST
    $id = $_POST['id'] ?? 0;
    $f_name = $_POST['f_name'] ?? '';
    $l_name = $_POST['l_name'] ?? ''; 
    $email = $_POST['email'] ?? '';

    // بررسی خالی نبودن فیلدها
    if (empty($id) || empty($f_name) || empty($l_name) || empty($email)) {
        echo json_encode(['message' => 'ID, first name, last name, and email are required.']);
        return;
    }

    try {
        // اجرای کوئری UPDATE با استفاده از مقادیر فیلدهای جدید
        $stmt = $pdo->prepare("UPDATE users SET F_NAME = :f_name, L_NAME = :l_name, EMAIL = :email WHERE ID = :id");
        $stmt->execute(['id' => $id, 'f_name' => $f_name, 'l_name' => $l_name, 'email' => $email]);
        
        echo json_encode(['message' => 'User updated successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to update user: ' . $e->getMessage()]);
    }
}


// Function to delete a user
function deleteUser($pdo)
{
    $id = $_POST['id'] ?? 0;

    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['message' => 'ID is required.']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM users WHERE ID = :id");
        $stmt->execute(['id' => $id]);
        echo json_encode(['message' => 'User deleted successfully.']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['message' => 'Failed to delete user: ' . $e->getMessage()]);
    }
}
?>
