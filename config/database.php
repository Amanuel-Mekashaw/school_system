<?php
class Database {
    private $host = "localhost";
    private $db_name = "e_communication";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
            $this->conn->exec("set names utf8mb4");
        } catch(PDOException $exception) {
            echo "Database connection error: " . $exception->getMessage();
            exit;
        }
        return $this->conn;
    }
}
