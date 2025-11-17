<?php
class Database {
    private string $host = "localhost";
    private string $user = "root";
    private string $pass = "";
    private string $db = "trabalho_web";
    private ?mysqli $conn = null;

    public function getConnection(): mysqli {
        if ($this->conn === null) {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->db);
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            $this->initializeSchema();
        }
        return $this->conn;
    }

    private function initializeSchema(): void {
        $sql = "CREATE TABLE IF NOT EXISTS recados (\n            id INT AUTO_INCREMENT PRIMARY KEY,\n            mensagem TEXT NOT NULL,\n            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conn->query($sql);
    }
}
