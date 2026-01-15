<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Entrar</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <h2>SudoLift</h2>
        <p>Faça login para treinar</p>
        <?php
        if (isset($_GET['erro'])) {
            echo '<div class="erro">E-mail ou senha incorretos!</div>';
        }
        ?>
        <form action="../controller/ctrl_Login.php" method="POST">
            <input type="email" name="email" placeholder="Seu E-mail" required>
            <input type="password" name="senha" placeholder="Sua Senha" required>
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>