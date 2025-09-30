<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['cep'])) {
    echo json_encode(['erro' => 'CEP não informado']);
    exit;
}

$cep = preg_replace('/\D/', '', $_GET['cep']);

if (strlen($cep) !== 8) {
    echo json_encode(['erro' => 'CEP inválido']);
    exit;
}

$url = "https://viacep.com.br/ws/{$cep}/json/";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
