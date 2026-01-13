<?php
// Arquivo: view/exercicios_lista.php
session_start();

// 1. Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// 2. Importa o modelo para buscar os dados
require_once '../model/clsExercicio.php';

// 3. Busca a lista de exercícios no banco
$objExercicio = new clsExercicio();
$resultado = $objExercicio->listar();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Gerenciar Exercícios</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #333; color: white; }
        tr:hover { background-color: #f5f5f5; }
        
        .btn { padding: 10px 15px; text-decoration: none; border-radius: 4px; color: white; font-size: 14px; }
        .btn-novo { background-color: #28a745; float: right; }
        .btn-editar { background-color: #007bff; margin-right: 5px; }
        .btn-excluir { background-color: #dc3545; }
        
        .header-area { overflow: hidden; margin-bottom: 20px; }
        h2 { float: left; margin: 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="header-area">
        <h2>Exercícios Cadastrados</h2>
        <a href="exercicios_form.php" class="btn btn-novo">+ Novo Exercício</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Mídia</th> <th>Nome</th>
                <th>Grupo</th>
                <th>Tipo</th>
                <th width="150">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (mysqli_num_rows($resultado) > 0) {
                while ($linha = mysqli_fetch_assoc($resultado)) {
                    echo "<tr>";
                    echo "<td>" . $linha['id'] . "</td>";
                    
                    // --- LÓGICA DE EXIBIÇÃO DA MÍDIA ---
                    echo "<td align='center'>";
                    $arquivo = $linha['imagem'];
                    
                    if (!empty($arquivo) && file_exists("../images/" . $arquivo)) {
                        $extensao = pathinfo($arquivo, PATHINFO_EXTENSION);
                        
                        if ($extensao == 'mp4') {
                            // Se for vídeo, mostra um player pequeno sem som
                            echo "<video width='80' height='80' muted loop onmouseover='this.play()' onmouseout='this.pause()'>";
                            echo "<source src='../images/$arquivo' type='video/mp4'>";
                            echo "</video>";
                        } else {
                            // Se for imagem ou GIF
                            echo "<img src='../images/$arquivo' width='80' style='border-radius:4px;'>";
                        }
                    } else {
                        echo "<span style='color:#ccc; font-size:12px;'>Sem mídia</span>";
                    }
                    echo "</td>";
                    // ------------------------------------

                    echo "<td>" . $linha['nome'] . "</td>";
                    echo "<td>" . $linha['grupo_muscular'] . "</td>";
                    echo "<td>" . $linha['tipo'] . "</td>";
                    echo "<td>";
                        echo "<a href='exercicios_form.php?id=" . $linha['id'] . "' class='btn btn-editar'>Editar</a>";
                        echo "<a href='../controller/ctrl_Exercicio.php?acao=excluir&id=" . $linha['id'] . "' class='btn btn-excluir' onclick='return confirm(\"Tem certeza?\")'>X</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' align='center'>Nenhum exercício cadastrado.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    
    <br>
    <a href="dashboard.php">Voltar ao Painel</a>
</div>

</body>
</html>