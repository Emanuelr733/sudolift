<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Alterar Senha</title>
    <link rel="stylesheet" href="../assets/css/alterar_senha.css">
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