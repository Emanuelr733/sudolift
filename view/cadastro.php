<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Criar Conta</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f0f2f5; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background-color: white; padding: 40px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); width: 350px; text-align: center; }
        input { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; font-weight: bold; margin-top: 10px;}
        button:hover { background-color: #218838; }
        h2 { color: #333; margin-bottom: 20px; }
        .link { display: block; margin-top: 15px; color: #007bff; text-decoration: none; font-size: 14px; }
    </style>
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