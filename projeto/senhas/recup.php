<?php
session_start();
include '../conexao.php'; 
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../css/recup.css">
</head>
<body>
    <header class="header">
        <div class="container_2">
            <h1 class="logo">
                <img src="../imgs/Logo Loja.png" alt="Melhores Tênis" class="logo-image">
                Santa Gula
            </h1>
            <div class="button-container">
     <a href="../logout_users.php" class="back-button">voltar</a>
</div>
    </header>
<div class="container">
    <h1 class="recup">Recuperar Senha</h1>

<?php

$msg = "";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

  
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        $token = bin2hex(random_bytes(16));
        $expiracao = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt2 = $pdo->prepare("INSERT INTO redefinir_senha (usuario_id, token, expiracao, usado) VALUES (?, ?, ?, 0)");
        $stmt2->execute([$usuario['id'], $token, $expiracao]);

        $link = "http://localhost/proyecto/senhas/recup.php?token=" . $token;
        $msg = "<div class='success'>Link de redefinição de senha:<br><a href='$link'>$link</a></div>";
    } else {
        $msg = "<div class='error'>Email não encontrado.</div>";
    }
}


if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = $pdo->prepare("SELECT usuario_id, expiracao, usado FROM redefinir_senha WHERE token = ?");
    $stmt->execute([$token]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        $msg = "<div class='error'>Token inválido.</div>";
    } elseif ($dados['usado']) {
        $msg = "<div class='error'>Token já foi usado.</div>";
    } elseif (strtotime($dados['expiracao']) < time()) {
        $msg = "<div class='error'>Token expirado.</div>";
    } else {
   
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nova_senha'])) {
            $nova_senha = $_POST['nova_senha'];
            $confirmar_senha = $_POST['confirmar_senha'];

            if ($nova_senha !== $confirmar_senha) {
                $msg = "<div class='error'>As senhas não conferem.</div>";
            } else {
                $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
                $stmt3 = $pdo->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
                $stmt3->execute([$senha_hash, $dados['usuario_id']]);

                $stmt4 = $pdo->prepare("UPDATE redefinir_senha SET usado = 1 WHERE token = ?");
                $stmt4->execute([$token]);

                $msg = "<div class='success'>Senha redefinida com sucesso!</div>";
            }
        }
    }
}


if (!empty($msg)) {
    echo $msg;
}


if (isset($_GET['token']) && isset($dados) && !$dados['usado'] && strtotime($dados['expiracao']) > time()) {
    ?>
    <form method="POST" class="form">
        <label>Nova Senha:</label>
        <input type="password" name="nova_senha" required>
        <label>Confirmar Senha:</label>
        <input type="password" name="confirmar_senha" required>
        <button type="submit">Redefinir Senha</button>
    </form>
    <?php
} elseif (!isset($_GET['token'])) {
    ?>
    <form method="POST" class="form">
        <label>Email:</label>
        <input type="email" name="email" required>
        <button type="submit">Enviar Link de Redefinição</button>
    </form>
    <?php
}
?>

</div>
</body>
</html>
