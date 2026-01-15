<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Criar Conta</title>
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>
<body>
    <div class="box">
        <h2>Crie sua conta</h2>
        <form action="../controller/ctrl_Cadastro.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="nome" placeholder="Seu Nome Completo" required>
            <input type="email" name="email" placeholder="Seu E-mail" required>
            <input type="password" name="senha" placeholder="Crie uma Senha" required>
            <label style="display:block; text-align:left; font-size:12px; margin-top:10px; color:#666;">Foto de Perfil:</label>
            <input type="file" name="foto" accept="image/*">
            <button type="submit">Cadastrar</button>
        </form>
        <a href="login.php" class="link">Já tem conta? Faça Login</a>
    </div>
</body>
</html>