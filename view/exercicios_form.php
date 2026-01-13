<?php
// Arquivo: view/exercicios_form.php
session_start();
// Verifica se está logado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Cadastro</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .form-container { max-width: 500px; margin: 0 auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input, select { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; width: 100%; font-size: 16px; }
        button:hover { background-color: #218838; }
        .btn-voltar { display: block; text-align: center; margin-top: 15px; text-decoration: none; color: #666; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Novo Exercício</h2>
    
    <form action="../controller/ctrl_Exercicio.php" method="POST" enctype="multipart/form-data">
        
        <label>Nome do Exercício:</label>
        <input type="text" name="nome" placeholder="Ex: Leg Press 45" required>

        <label>Grupo Muscular:</label>
        <select name="grupo" required>
            <option value="">Selecione...</option>
            <option value="Peito">Peito</option>
            <option value="Costas">Costas</option>
            <option value="Pernas">Pernas</option>
            <option value="Ombros">Ombros</option>
            <option value="Bíceps">Bíceps</option>
            <option value="Tríceps">Tríceps</option>
            <option value="Abdômen">Abdômen</option>
        </select>

        <label>Tipo de Equipamento:</label>
        <select name="tipo" required>
            <option value="">Selecione...</option>
            <option value="Barra">Barra</option>
            <option value="Halter">Halter</option>
            <option value="Máquina">Máquina</option>
            <option value="Peso Corporal">Peso Corporal</option>
            <option value="Polia">Polia (Cabo)</option>
        </select>

        <label>Demonstração (GIF, Imagem ou MP4):</label>
        <input type="file" name="arquivo_midia" accept=".jpg, .jpeg, .png, .gif, .mp4">

        <br><br>
        <button type="submit">Salvar Exercício</button>
    </form>

    <a href="exercicios_lista.php" class="btn-voltar">Voltar para a Lista</a>
</div>

</body>
</html>