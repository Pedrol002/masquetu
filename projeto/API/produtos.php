q<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$conexao = new mysqli("localhost", "admin", "admin", "user_tn");

if ($conexao->connect_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na conexão com o banco de dados']);
    exit();
}

$q = isset($_GET['q']) ? $conexao->real_escape_string($_GET['q']) : '';
$category = isset($_GET['category']) ? $conexao->real_escape_string($_GET['category']) : '';
$min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : null;
$max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : null;
$sort = isset($_GET['sort']) ? $conexao->real_escape_string($_GET['sort']) : 'nome';

$sql = "SELECT id, nome, descricao, preco, imagem FROM produto WHERE 1=1";

if ($q !== '') {
    $sql .= " AND nome LIKE '%$q%'";
}

if ($category !== '') {
    // Assumindo que há uma coluna categoria na tabela produto
    $sql .= " AND categoria = '$category'";
}

if ($min_price !== null) {
    $sql .= " AND preco >= $min_price";
}

if ($max_price !== null) {
    $sql .= " AND preco <= $max_price";
}

$sql .= " ORDER BY $sort";

$result = $conexao->query($sql);

$produtos = [];

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $produtos[] = [
            'id' => intval($row['id']),
            'name' => $row['nome'],
            'description' => $row['descricao'],
            'price' => floatval($row['preco']),
            'image' => 'Tênis/' . $row['imagem']
        ];
    }
    echo json_encode($produtos);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Erro na consulta ao banco de dados']);
}

$conexao->close();
?>
