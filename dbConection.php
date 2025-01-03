<?php

class Database {
    private $host;
    private $dbname;
    private $username;
    private $password;
    private $pdo;

    // Constructor to initialize database credentials
    public function __construct($host = 'localhost', $dbname = 'restaurant', $username = 'root', $password = '') {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
        $this->connect(); // Establish connection upon instantiation
    }

    // Method to establish a PDO connection
    private function connect() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8", 
                $this->username, 
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die(json_encode(['message' => 'Database connection failed: ' . $e->getMessage()]));
        }
    }

    // Getter for the PDO instance
    public function getConnection() {
        return $this->pdo;
    }
}

// Example usage
try {
    $db = new Database();
    $pdo = $db->getConnection(); // Access the PDO instance
    // Use $pdo for database operations
} catch (Exception $e) {
    echo $e->getMessage();
}

?>
