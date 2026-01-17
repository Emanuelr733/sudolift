<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SudoLift - Criar Conta</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/cadastro.css">
</head>
<body>
    <div class="main-wrapper">
        <div class="box-container">
            <div class="brand">
                <i class="fas fa-user-plus"></i>
                <h2>Crie sua conta</h2>
            </div>
            <p class="subtitle">Preencha os dados abaixo</p>

            <form action="../controller/ctrl_Cadastro.php" method="POST" enctype="multipart/form-data">
                
                <div class="input-group">
                    <i class="fas fa-user icon"></i>
                    <input type="text" name="nome" placeholder="Seu Nome Completo" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-envelope icon"></i>
                    <input type="email" name="email" placeholder="Seu E-mail" required>
                </div>

                <div class="input-group">
                    <i class="fas fa-lock icon"></i>
                    <input type="password" name="senha" placeholder="Crie uma Senha" required>
                </div>

                <div class="file-group">
                    <label>
                        <i class="fas fa-camera"></i> Foto de Perfil (Opcional)
                    </label>
                    <input type="file" name="foto" accept="image/*" class="input-file">
                </div>

                <button type="submit" class="btn-register">
                    CADASTRAR <i class="fas fa-check"></i>
                </button>
            </form>

            <div class="footer-links">
                <p>Já tem conta? <a href="login.php">Faça Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>