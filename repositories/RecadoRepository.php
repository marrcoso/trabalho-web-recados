<?php
require_once __DIR__ . '/../lib/Database.php';
require_once __DIR__ . '/../models/Recado.php';

class RecadoRepository {
    private mysqli $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
    }

    public function all(): array {
        $items = [];
        $result = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados ORDER BY id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
            }
        }
        return $items;
    }

    public function create(string $mensagem): ?Recado {
        $stmt = $this->db->prepare("INSERT INTO recados (mensagem) VALUES (?)");
        if (!$stmt) return null;
        $stmt->bind_param('s', $mensagem);
        if (!$stmt->execute()) return null;
        $id = $stmt->insert_id;
        $stmt->close();
        $res = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados WHERE id = " . (int)$id . " LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
        }
        return null;
    }

    public function edit(int $id, string $mensagem): ?Recado {
        $stmt = $this->db->prepare("UPDATE recados SET mensagem = ? WHERE id = ?");
        if (!$stmt) return null;
        $stmt->bind_param('si', $mensagem, $id);
        if (!$stmt->execute()) return null;
        $res = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados WHERE id = " . (int)$id . " LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
        }
        return null;
    }

    public function delete(int $id): ?Recado {
        $stmt = $this->db->prepare("DELETE FROM recados WHERE id = ?");
        if (!$stmt) return null;
        $stmt->bind_param('i', $id);
        if (!$stmt->execute()) return null;
        $res = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados WHERE id = " . (int)$id . " LIMIT 1");
        if ($res && $row = $res->fetch_assoc()) {
            return new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
        }
        return null;
    }

    public function updateStatus(int $id, int $status) {
        $stmt = $this->db->prepare("UPDATE recados SET status = ? WHERE id = ?");
        $stmt->bind_param("ii", $status, $id);
        $stmt->execute();
    }
    
    public function favorites(): array {
        $items = [];
        $result = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados WHERE status = 1 ORDER BY id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
            }
        }
        return $items;
    }

    public function nonFavorites(): array {
        $items = [];
        $result = $this->db->query("SELECT id, mensagem, data_criacao, status FROM recados WHERE status = 0 ORDER BY id DESC");
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $items[] = new Recado((int)$row['id'], $row['mensagem'], $row['data_criacao'], (int)$row['status']);
            }
        }
        return $items;
    }
}
