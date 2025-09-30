<?php
session_start();

$host = 'localhost';
$dbname = 'user_tn'; 
$user = 'admin';
$pass = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro na conexão: " . $e->getMessage();
    header("Location: register_index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf     = trim($_POST['cpf']);
    $usuario = trim($_POST['usuario']);
    $telefone= trim($_POST['telefone']);
    $email   = trim($_POST['email']);
    $senha   = trim($_POST['senha']);
    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    // filtragem de verificação
    $verificaSQL = "SELECT COUNT(*) FROM usuarios WHERE usuario = :usuario OR email = :email";
    $stmtVerifica = $pdo->prepare($verificaSQL);
    $stmtVerifica->bindParam(':usuario', $usuario);
    $stmtVerifica->bindParam(':email', $email);
    $stmtVerifica->execute();
    $existe = $stmtVerifica->fetchColumn();

    if ($existe > 0) {
        $_SESSION['erro'] = "Este login já existe!";
        header("Location: register_index.php");
        exit;
    }

    //inserção de cadastros
    $sql = "INSERT INTO usuarios (cpf, usuario, telefone, email, senha) 
            VALUES (:cpf, :usuario, :telefone, :email, :senha)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpf', $cpf);
    $stmt->bindParam(':usuario', $usuario);
    $stmt->bindParam(':telefone', $telefone);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':senha', $senhaHash);

    if ($stmt->execute()) {
        $_SESSION['erro'] = "Registro realizado com sucesso!!!";
    } else {
        $_SESSION['erro'] = "Erro ao registrar os dados.";
    }

    header("Location: register_index.php");
    exit;
} else {
    $_SESSION['erro'] = "Requisição inválida.";
    header("Location: register_index.php");
    exit;
}
