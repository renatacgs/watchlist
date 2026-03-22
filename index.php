<?php
session_start();
require_once 'app/DAO/UsuarioDAO.php';
require_once 'app/DAO/TarefaDAO.php';

$uDAO = new UsuarioDAO();
$tDAO = new TarefaDAO();
$msg = "";

if (isset($_GET['action']) && $_GET['action'] == 'logout') { 
    session_destroy(); 
    header("Location: index.php"); 
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['btn_cadastrar'])) {
        $uDAO->cadastrar($_POST['nome'], $_POST['email'], $_POST['senha']);
        $msg = "Usuário criado com sucesso!";
    }
    if (isset($_POST['btn_login'])) {
        $user = $uDAO->login($_POST['email'], $_POST['senha']);
        if ($user) { 
            $_SESSION['user'] = $user; 
        } else { 
            $msg = "Email ou senha incorretos!"; 
        }
    }
    if (isset($_POST['btn_add'])) {
        $tDAO->salvar($_POST['titulo'], $_POST['desc'], $_SESSION['user']['id']);
    }
    if (isset($_POST['excluir'])) {
        $tDAO->excluir($_POST['id']);
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style.css">
    <title>Meus filmes e séries </title>
</head>
<body>
    <div class="container">
        <?php if($msg): ?> <p style="color: #ff57abff; text-align:center;"><?= $msg ?></p> <?php endif; ?>

        <?php if(!isset($_SESSION['user'])): ?>
            <h2>Minha watchlist</h2>
            <form method="POST">
                <input type="text" name="nome" placeholder="Nome para Cadastro">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button name="btn_login" class="btn-primary">ENTRAR</button>
                <button name="btn_cadastrar" class="btn-secondary">CADASTRAR</button>
            </form>

        <?php else: ?>
            <a href="?action=logout" class="logout">Sair</a>
            <h2>Dashboard</h2>
            <p>Olá, <strong><?= htmlspecialchars($_SESSION['user']['nome']) ?></strong></p>
            <hr style="border: 0.5px solid #30363d;">
            
            <h3>Novo filme/série</h3>
            <form method="POST">
                <input type="text" name="titulo" placeholder="Nome" required>
                <textarea name="desc" placeholder="Especificações técnicas(filme, série, anime...)"></textarea>
                <button name="btn_add" class="btn-primary">CADASTRAR</button>
            </form>

            <table>
                <thead><tr><th>Item</th><th>Ação</th></tr></thead>
                <tbody>
                    <?php foreach($tDAO->listar($_SESSION['user']['id']) as $t): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($t['titulo']) ?></strong><br><small><?= htmlspecialchars($t['descricao']) ?></small></td>
                        <td>
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                <button name="excluir" class="btn-delete">Remover</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>