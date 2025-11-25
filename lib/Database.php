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
        $sql = "CREATE TABLE IF NOT EXISTS recados (\nid INT AUTO_INCREMENT PRIMARY KEY,\nmensagem TEXT NOT NULL,\ndata_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,\nstatus TINYINT NOT NULL DEFAULT 0\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
        $this->conn->query($sql);

        $dbName = $this->db;
        
        $hasDataCriacao = false;
        $hasStatus = false;
        $hasCreatedAt = false;

        $res = $this->conn->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '" . $this->conn->real_escape_string($dbName) . "' AND TABLE_NAME = 'recados'");
        if ($res) {
            while ($row = $res->fetch_assoc()) {
                if ($row['COLUMN_NAME'] === 'data_criacao') $hasDataCriacao = true;
                if ($row['COLUMN_NAME'] === 'status') $hasStatus = true;
                if ($row['COLUMN_NAME'] === 'created_at') $hasCreatedAt = true;
            }
            $res->free();
        }

        if (!$hasDataCriacao && $hasCreatedAt) {
            $this->conn->query("ALTER TABLE recados CHANGE COLUMN created_at data_criacao TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP");
            $hasDataCriacao = true;
        }

        if (!$hasDataCriacao) {
            $this->conn->query("ALTER TABLE recados ADD COLUMN data_criacao TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP AFTER mensagem");
        }

        if (!$hasStatus) {
            $this->conn->query("ALTER TABLE recados ADD COLUMN status TINYINT NOT NULL DEFAULT 0 AFTER data_criacao");
        }
    }
}
