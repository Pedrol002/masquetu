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
            SP Dois Pares
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

    <section class="feedback-section">
        <h2>Envie seu Feedback</h2>
        <form id="feedbackForm">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required />
            <label for="email">E-mail:</label>
            <input type="email" id="email" name="email" required />
            <label for="mensagem">Mensagem:</label>
            <textarea id="mensagem" name="mensagem" rows="4" required></textarea>
            <button type="submit">Enviar Feedback</button>
        </form>
        <p id="message"></p>

        <h3>Feedbacks Recebidos</h3>
        <div id="feedbackList"></div>
    </section>

    <script src="js/index.js"></script>
    <script>
        async function fetchFeedbacks() {
            const response = await fetch('API/feedback.php');
            const feedbacks = await response.json();
            const feedbackList = document.getElementById('feedbackList');
            if (!feedbackList) return;
            feedbackList.innerHTML = '';
            feedbacks.forEach(fb => {
                const div = document.createElement('div');
                div.className = 'feedback-item';
                div.innerHTML = `
                    <strong>${fb.nome}</strong> (${fb.email}) - <em>${new Date(fb.data_envio).toLocaleString()}</em>
                    <p>${fb.mensagem}</p>
                `;
                feedbackList.appendChild(div);
            });
        }

        document.getElementById('feedbackForm')?.addEventListener('submit', async (e) => {
            e.preventDefault();
            const nome = document.getElementById('nome').value;
            const email = document.getElementById('email').value;
            const mensagem = document.getElementById('mensagem').value;
            const messageEl = document.getElementById('message');
            messageEl.textContent = '';

            try {
                const response = await fetch('API/feedback.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ nome, email, mensagem })
                });
                const data = await response.json();
                if (data.success) {
                    messageEl.style.color = 'green';
                    messageEl.textContent = data.message;
                    document.getElementById('feedbackForm').reset();
                    fetchFeedbacks();
                } else {
                    messageEl.style.color = 'red';
                    messageEl.textContent = data.error || 'Erro ao enviar feedback.';
                }
            } catch (error) {
                messageEl.style.color = 'red';
                messageEl.textContent = 'Erro na comunicação com o servidor.';
            }
        });

        fetchFeedbacks();
    </script>
</body>
</html>
