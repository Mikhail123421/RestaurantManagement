<?php
include("dbConection.php");

class UserHandler
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->sendResponse(405, ['message' => 'Request method not allowed.']);
            return;
        }

        $action = $_POST['action'] ?? '';
        switch ($action) {
            case 'loadUsers':
                $this->loadUsers();
                break;
            case 'edit':
                $this->editUser();
                break;
            case 'delete':
                $this->deleteUser();
                break;
            default:
                $this->sendResponse(400, ['message' => 'Invalid action.']);
        }
    }

    private function loadUsers()
    {
        try {
            $stmt = $this->pdo->query("SELECT * FROM users ORDER BY ID ASC");
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->sendResponse(200, $users);
        } catch (PDOException $e) {
            $this->sendResponse(500, ['message' => 'Failed to load users: ' . $e->getMessage()]);
        }
    }

    private function editUser()
    {
        $id = $_POST['id'] ?? 0;
        $f_name = $_POST['f_name'] ?? '';
        $l_name = $_POST['l_name'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($id) || empty($f_name) || empty($l_name) || empty($email)) {
            $this->sendResponse(400, ['message' => 'ID, first name, last name, and email are required.']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("UPDATE users SET F_NAME = :f_name, L_NAME = :l_name, EMAIL = :email WHERE ID = :id");
            $stmt->execute(['id' => $id, 'f_name' => $f_name, 'l_name' => $l_name, 'email' => $email]);
            $this->sendResponse(200, ['message' => 'User updated successfully.']);
        } catch (PDOException $e) {
            $this->sendResponse(500, ['message' => 'Failed to update user: ' . $e->getMessage()]);
        }
    }

    private function deleteUser()
    {
        $id = $_POST['id'] ?? 0;

        if (empty($id)) {
            $this->sendResponse(400, ['message' => 'ID is required.']);
            return;
        }

        try {
            $stmt = $this->pdo->prepare("DELETE FROM users WHERE ID = :id");
            $stmt->execute(['id' => $id]);
            $this->sendResponse(200, ['message' => 'User deleted successfully.']);
        } catch (PDOException $e) {
            $this->sendResponse(500, ['message' => 'Failed to delete user: ' . $e->getMessage()]);
        }
    }

    private function sendResponse($statusCode, $data)
    {
        http_response_code($statusCode);
        echo json_encode($data);
    }
}

$userHandler = new UserHandler($pdo);
$userHandler->handleRequest();
?>
