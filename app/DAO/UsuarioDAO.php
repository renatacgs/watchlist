<?php
require_once __DIR__ . '/../Core/Database.php';
use App\Core\Database;

class UsuarioDAO {
    public function cadastrar($nome, $email, $senha) {
        $db = Database::getConnection();
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (?, ?, ?)";
        return $db->prepare($sql)->execute([$nome, $email, $hash]);
    }

    public function login($email, $senha) {
        $db = Database::getConnection();
        $stmt = $db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($senha, $user['senha'])) return $user;
        return false;
    }
}