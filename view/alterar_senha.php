<?php
// Arquivo: view/alterar_senha.php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Alterar Senha</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .box { max-width: 400px; margin: 50px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        h2 { text-align: center; }
    </style>
</head>
<body>

<div class="box">
    <h2>Alterar Senha</h2>
    <p style="text-align:center; font-size:14px;">Digite sua nova senha abaixo.</p>

    <form action="../controller/ctrl_AlterarSenha.php" method="POST">
        <label>Senha Atual (Confirmação):</label>
        <input type="password" name="senha_atual" placeholder="Digite sua senha atual" required>

        <label>Nova Senha:</label>
        <input type="password" name="nova_senha" placeholder="Digite a nova senha" required>

        <button type="submit">Salvar Nova Senha</button>
    </form>
    
    <br>
    <center><a href="dashboard.php" style="text-decoration:none; color: #555;">Cancelar e Voltar</a></center>
</div>

</body>
</html>