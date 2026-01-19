<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../controller/clsConexao.php';

// É importante buscar do banco e não só da sessão, pois o usuário pode ter alterado algo recentemente.
$id_user = (int)$_SESSION['id_usuario'];
$conexao = new clsConexao();
$sql = "SELECT * FROM usuarios WHERE id = $id_user";
$res = $conexao->executaSQL($sql);
$dadosUsuario = mysqli_fetch_assoc($res);

// Define foto da sessão (sidebar)
$fotoSessao = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

// Define foto do formulário (pode ser a mesma)
$fotoForm = !empty($dadosUsuario['foto_perfil']) ? $dadosUsuario['foto_perfil'] : 'padrao.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Meu Perfil</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/perfil.css">
</head>
<body>
    
    <div class="sidebar">
        <div class="perfil-area">
            <a href="perfil.php" style="text-decoration:none; color:inherit; display:block;">
                <img src="../assets/images/users/<?php echo $fotoSessao; ?>" 
                     class="perfil-foto" 
                     onerror="this.src='../assets/images/users/padrao.png'">
                <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
            </a>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>
            
            <?php if ($_SESSION['perfil_usuario'] != 'escrivao'): ?>
                <a href="rotinas.php" class="menu-item"><i class="fas fa-dumbbell"></i> Rotinas</a>
                <a href="exercicios.php" class="menu-item"><i class="fas fa-running"></i> Exercícios</a>
            <?php else: ?>
                <a href="citacoes.php" class="menu-item"><i class="fas fa-quote-right"></i> Editar Citações</a>
            <?php endif; ?>

            <?php if (isset($_SESSION['perfil_usuario']) && $_SESSION['perfil_usuario'] == 'admin'): ?>
                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 0;"></div>
                <a href="admin.php" class="menu-item" style="color: #ff6b6b;">
                    <i class="fas fa-user-shield"></i> Painel Admin
                </a>
            <?php endif; ?>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="main-content">
        <div class="profile-card">
            <div class="card-header-bg"></div>

            <form action="../controller/ctrl_Usuario.php" method="POST" enctype="multipart/form-data" class="profile-form">
                
                <input type="hidden" name="acao" value="editar_perfil">
                
                <div class="photo-upload-section">
                    <div class="photo-wrapper">
                        <img src="../assets/images/users/<?php echo $fotoForm; ?>" 
                             id="preview-foto"
                             onerror="this.src='../assets/images/users/padrao.png'">
                        
                        <label for="input-foto" class="btn-edit-photo">
                            <i class="fas fa-camera"></i>
                        </label>
                    </div>
                    <input type="file" name="foto_perfil" id="input-foto" accept="image/*" onchange="previewImagem(this)">
                </div>

                <div class="input-grid">
                    <div class="form-group">
                        <label>Nome Completo</label>
                        <div class="input-wrapper">
                            <i class="fas fa-user"></i>
                            <input type="text" name="nome" value="<?php echo htmlspecialchars($dadosUsuario['nome']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <div class="input-wrapper">
                            <i class="fas fa-envelope"></i>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($dadosUsuario['email']); ?>" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Nova Senha <small>(Deixe em branco para manter)</small></label>
                        <div class="input-wrapper">
                            <i class="fas fa-lock"></i>
                            <input type="password" name="senha" placeholder="********">
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-save">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
            </form>
        </div>
    </div>

    <script>
        function previewImagem(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-foto').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>