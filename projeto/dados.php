<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location: index.html"); 
    exit();
}

$host   = 'localhost';
$dbname = 'user_tn'; 
$dbUser = 'admin';
$dbPass = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $dbUser, $dbPass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

$email = $_SESSION['email'];

$sql = "SELECT cpf, usuario, telefone, email FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $dadosUsuario = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "Usuário não encontrado!";
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SP Dois Pares - Dados</title>
      <link rel="stylesheet" href="css/dates.css">
</head>
<body>
    <div class="logo">
        <h1 class="header">
            <img src="imgs/Logo Loja.png" alt="Melhores Tênis" class="logo-image">
            SP Dois Pares
        </h1>
    </div>
    
    <div class="container">
        <h1>Seja bem-vindo <?php echo htmlspecialchars($dadosUsuario['usuario']); ?></h1>
        
        <table>
            <tr>
                <th>CPF</th>
                <td><?php echo htmlspecialchars($dadosUsuario['cpf']); ?></td>
            </tr>
            <tr>
                <th>Nome</th>
                <td><?php echo htmlspecialchars($dadosUsuario['usuario']); ?></td>
            </tr>
            <tr>
                <th>Telefone</th>
                <td><?php echo htmlspecialchars($dadosUsuario['telefone']); ?></td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td><?php echo htmlspecialchars($dadosUsuario['email']); ?></td>
            </tr>
        </table>
        
        <div style="margin-top: 20px;">
            <a class="logout" style="text-decoration: none;" href="logout_users.php">Sair</a>
            <a class="logout" style="text-decoration: none;" href="senhas/recup.php">Recuperar Senha</a>
        </div>
    </div>
</body>
</html>