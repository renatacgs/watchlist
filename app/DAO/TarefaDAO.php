<?php
require_once __DIR__ . '/../Core/Database.php';
use App\Core\Database;

class TarefaDAO {
    public function listar($userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM tarefas WHERE usuario_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function salvar($titulo, $desc, $userId) {
        $db = Database::getConnection();
        $stmt = $db->prepare("INSERT INTO tarefas (titulo, descricao, usuario_id) VALUES (?, ?, ?)");
        return $stmt->execute([$titulo, $desc, $userId]);
    }
    
    public function excluir($id) {
        $db = Database::getConnection();
        $stmt = $db->prepare("DELETE FROM tarefas WHERE id = ?");
        return $stmt->execute([$id]);
    }
}