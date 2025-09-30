<?php
$conexao = new mysqli("localhost", "admin", "admin", "user_tn");

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}
?>
