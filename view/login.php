<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SudoLift - Entrar</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/login.css">
    
    <style>
        .erro-msg {
            background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb;
            padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px;
        }
        .success-msg {
            background: #d4edda; color: #155724; border: 1px solid #c3e6cb;
            padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px;
        }
    </style>
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
            // MENSAGEM DE SUCESSO (Vindo do Cadastro)
            if (isset($_GET['msg']) && $_GET['msg'] == 'cadastrado') {
                echo '<div class="success-msg"><i class="fas fa-check-circle"></i> Conta criada com sucesso! Faça login.</div>';
            }

            // MENSAGENS DE ERRO (Vindo do Controller)
            if (isset($_GET['erro'])) {
                $textoErro = "E-mail ou senha incorretos!";
                
                // Tratamento para campo vazio (adicionado no controller otimizado)
                if ($_GET['erro'] == 'vazio') {
                    $textoErro = "Por favor, preencha todos os campos.";
                }
                
                echo '<div class="erro-msg"><i class="fas fa-exclamation-circle"></i> ' . $textoErro . '</div>';
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