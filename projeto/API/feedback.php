<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'user_tn';
$user = 'admin';
$pass = 'admin';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'POST') {
        // Recebe feedback do cliente
        $data = json_decode(file_get_contents('php://input'), true);
        if (!isset($data['nome']) || !isset($data['email']) || !isset($data['mensagem'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Campos nome, email e mensagem são obrigatórios']);
            exit();
        }

        $nome = $data['nome'];
        $email = $data['email'];
        $mensagem = $data['mensagem'];

        $stmt = $pdo->prepare("INSERT INTO feedback (nome, email, mensagem, data_envio) VALUES (:nome, :email, :mensagem, NOW())");
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mensagem', $mensagem);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Feedback enviado com sucesso']);
    } elseif ($method === 'GET') {
        // Lista todos os feedbacks
        $stmt = $pdo->query("SELECT id, nome, email, mensagem, data_envio FROM feedback ORDER BY data_envio DESC");
        $feedbacks = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($feedbacks);
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Método não permitido']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor']);
}
?>
