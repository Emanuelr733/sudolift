<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SudoLift - Entrar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="box-container">
            <div class="brand">
                <i class="fas fa-dumbbell"></i>
                <h2>SudoLift</h2>
            </div>
            <p class="subtitle">Faça login para treinar</p>

            <?php
            if (isset($_GET['erro'])) {
                echo '<div class="erro-msg"><i class="fas fa-exclamation-circle"></i> E-mail ou senha incorretos!</div>';
            }
            ?>

            <form action="../controller/ctrl_Login.php" method="POST">
                
                <div class="input-group">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" placeholder="Seu E-mail" required>
                </div>
                
                <div class="input-group">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="senha" placeholder="Sua Senha" required>
                </div>

                <button type="submit" class="btn-login">
                    ENTRAR <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="footer-links">
                <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se aqui</a></p>
            </div>
        </div>
    </div>
</body>
</html>