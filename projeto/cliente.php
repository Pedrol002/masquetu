<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include('conexao.php');

$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
// Use backticks for table name to avoid reserved word conflicts
$sql = "SELECT * FROM `produto` WHERE nome LIKE '%$busca%'";
$result = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>SP Dois Pares</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<header class="header">
    <div class="container">
        <h1 class="logo">
            <img src="imgs/Logo Loja.png" alt="Melhores Tênis" class="logo-image">
            Santa Gula
        </h1>
        <nav class="nav">
            <a href="#ofertas" class="nav-link">Ofertas</a>
            <a href="#contacto" class="nav-link">Contato</a>
            <a href="dados.php" class="nav-link">Meus Dados</a>
            <a href="logout.php" class="nav-link active">Sair</a>
        </nav>
    </div>
</header>

<div class="search-bar">
    <div class="container">
        <form method="GET" action="cliente.php">
            <div class="search-container">
                <i class="fas fa-search"></i>
                <input type="text" name="busca" placeholder="Pesquisar Tênis" value="<?= htmlspecialchars($busca) ?>">
            </div>
        </form>
    </div>
</div>


<main class="container">
    <div class="menu-grid" id="menuContainer">
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <div class="menu-item">
                <img src="imgs/tenis<?= ($row['id'] % 3) + 1 ?>.jpg" alt="<?= $row['nome'] ?>">
                    <h3 class="item-title"><?= $row['nome'] ?></h3>
                    <p class="item-description"><?= $row['descricao'] ?></p>
                    <p class="price">R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
                    <button class="add-to-cart" onclick="addToCart(<?= $row['id'] ?>)">Adicionar <i class="fas fa-cart-plus"></i></button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>Nenhum produto encontrado.</p>
        <?php endif; ?>
    </div>
    <div class="cart-floating">
        <button class="cart-button" onclick="toggleCart()">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count">0</span>
        </button>
    </div>

    <div class="cart-sidebar">
        <div class="cart-header">
            <h2>Seu Pedido</h2>
            <button class="close-cart" onclick="toggleCart()">&times;</button>
        </div>
        <ul class="cart-items" id="cartItems"></ul>

        <!-- Bloco para cálculo de frete -->
        <div class="cart-cep-container">
            <h3 class="cart-cep-title">Calcular Frete</h3>
            <div class="cart-cep-input-group">
                <input type="text" id="cepInput" placeholder="Digite seu CEP" class="cart-cep-input">
                <button id="calcFreteBtn" class="cart-cep-button">Calcular</button>
            </div>
            <p id="cepError" class="cep-error-message"></p>
        </div>

        <div id="freteInfo" class="cart-frete-info-container">
            <p class="cart-frete-text">Frete para: <span id="enderecoFrete"></span></p>
            <p class="cart-frete-text">Valor do Frete: <span id="valorFrete"></span></p>
        </div>

        <div class="cart-total">
            Total: R$<span id="cartTotal">0.00</span>
        </div>
        <button class="checkout-button" onclick="realizarPedido()">Finalizar Compra</button>
    </div>

    <!-- Modal de carregamento -->
    <div id="loadingOverlay">
        <div class="spinner"></div>
        <p>Calculando frete...</p>
    </div>

    <script src="js/index.js"></script>
</body>
</html>
