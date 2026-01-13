<?php
// Arquivo: view/meus_treinos.php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../model/clsTreino.php';

// Busca os treinos SÓ desse usuário logado
$objTreino = new clsTreino();
$resultado = $objTreino->listarMeusTreinos($_SESSION['id_usuario']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Meus Treinos</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        
        .card-novo { background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); text-align: center; }
        
        .treino-item { background: white; padding: 15px; border-radius: 8px; margin-bottom: 10px; border-left: 5px solid #007bff; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        .treino-data { font-size: 12px; color: #888; }
        .treino-nome { font-size: 18px; font-weight: bold; margin: 5px 0; }
        
        input[type="text"] { padding: 10px; width: 70%; border: 1px solid #ccc; border-radius: 4px; }
        button { padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <a href="dashboard.php" style="text-decoration:none; color: #555;">&larr; Voltar ao Painel</a>
    <h2>Meus Treinos</h2>

    <div class="card-novo">
        <h3>Começar Agora</h3>
        <form action="../controller/ctrl_Treino.php" method="POST">
            <input type="hidden" name="acao" value="novo">
            <input type="text" name="nome_treino" placeholder="Nome do treino (Opcional)">
            <button type="submit">Iniciar +</button>
        </form>
    </div>

    <hr>

    <?php
    if (mysqli_num_rows($resultado) > 0) {
        while ($linha = mysqli_fetch_assoc($resultado)) {
            echo "<div class='treino-item'>";
            echo "<div class='treino-data'>" . date('d/m/Y', strtotime($linha['data_treino'])) . "</div>";
            echo "<div class='treino-nome'>" . $linha['nome_treino'] . "</div>";
            echo "<a href='treino_detalhes.php?id_treino=" . $linha['id'] . "'>Ver Detalhes / Continuar</a>";
            echo "</div>";
        }
    } else {
        echo "<p align='center'>Você ainda não registrou nenhum treino. Bora começar?</p>";
    }
    ?>
</div>

</body>
</html>