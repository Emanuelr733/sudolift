<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Entrar</title>
    <style>
        /* Um estilo simples e limpo para parecer profissional */
        body { font-family: Arial, sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .login-container { background-color: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); width: 300px; text-align: center; }
        input[type="email"], input[type="password"] { width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background-color: #0056b3; }
        .erro { color: red; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>

    <div class="login-container">
        <h2>SudoLift</h2>
        <p>Faça login para treinar</p>

        <?php
        // Mostra mensagem de erro se o Controller devolver ?erro=1 na URL
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