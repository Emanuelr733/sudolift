<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

require_once '../../controller/clsConexao.php';

$conexao = new clsConexao();

// Busca uma citação aleatória
$sql = "SELECT descricao, autor FROM citacoes ORDER BY RAND() LIMIT 1";
$result = $conexao->executaSQL($sql);

$response = [];

if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response = [
        'texto' => $row['descricao'],
        'autor' => $row['autor']
    ];
}
echo json_encode($response, JSON_UNESCAPED_UNICODE);
