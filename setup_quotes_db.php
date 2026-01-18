<?php
require_once 'controller/clsConexao.php';

$conexao = new clsConexao();

// 1. Create Table
$sqlCreate = "CREATE TABLE IF NOT EXISTS citacoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descricao TEXT NOT NULL,
    autor VARCHAR(100) DEFAULT 'Desconhecido'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

if ($conexao->executaSQL($sqlCreate)) {
    echo "Tabela 'citacoes' criada ou já existente.\n";
} else {
    echo "Erro ao criar tabela: " . mysqli_error($conexao->getConexao()) . "\n";
}

// 2. Insert Data (Check if empty first to avoid duplicates on re-run)
$res = $conexao->executaSQL("SELECT count(*) as total FROM citacoes");
$row = mysqli_fetch_assoc($res);

if ($row['total'] == 0) {
    $citacoes = [
        ["O único treino ruim é aquele que não aconteceu.", "Desconhecido"],
        ["Motivação é o que te faz começar. Hábito é o que te faz continuar.", "Jim Ryun"],
        ["Se não te desafia, não te muda.", "Fred DeVito"],
        ["Sem dor, sem ganho.", "Ben Franklin"],
        ["O corpo alcança o que a mente acredita.", "Napoleão Hill"],
        ["A disciplina é a mãe do êxito.", "Ésquilo"],
        ["Não deixe para amanhã o que você pode treinar hoje.", "Desconhecido"],
        ["Força não vem da capacidade física. Vem de uma vontade indomável.", "Mahatma Gandhi"]
    ];

    foreach ($citacoes as $c) {
        $desc = $c[0];
        $autor = $c[1];
        $sqlInsert = "INSERT INTO citacoes (descricao, autor) VALUES ('$desc', '$autor')";
        $conexao->executaSQL($sqlInsert);
    }
    echo "Citações iniciais inseridas com sucesso.\n";
} else {
    echo "Tabela já contém dados. Pulei a inserção.\n";
}
