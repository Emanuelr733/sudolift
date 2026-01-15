<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
$nome = $_SESSION['nome_usuario'];
$perfil = $_SESSION['perfil_usuario'];
$foto = isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">

</head>
<body>
    <div class="sidebar">
        <div class="perfil-area">
            <?php 
            $caminhoFoto = "../assets/images/users/" . $foto;
            if (!file_exists($caminhoFoto)) { $caminhoFoto = "https://via.placeholder.com/80"; }
            ?>
            <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
            <h3 class="perfil-nome"><?php echo $nome; ?></h3>
            <span class="perfil-tipo">ATLETA</span>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item ativo">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="rotinas.php" class="menu-item">
                <i class="fas fa-dumbbell"></i> Rotinas
            </a>
            <a href="exercicios.php" class="menu-item">
                <i class="fas fa-dumbbell"></i> Exercícios
            </a>
            </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>
    </div>
    <div class="main-content">
        <h1>Início</h1>
        <p style="color: #666;">Bem-vindo ao seu painel de controle.</p>
        <div class="card-aviso">
            <img src="https://cdn-icons-png.flaticon.com/512/2921/2921222.png" width="80" style="opacity:0.5; margin-bottom:15px;">
            <h3>Histórico de Treinos</h3>
            <p>Em breve, aqui você verá gráficos da sua evolução e o calendário de dias treinados.</p>
            <p>Por enquanto, acesse a aba <b>Rotinas</b> para gerenciar seus treinos.</p>
            <a href="rotinas.php" style="display:inline-block; margin-top:10px; text-decoration:none; color:#007bff; font-weight:bold;">Ir para Rotinas &rarr;</a>
            <a href="exercicios.php" style="display:inline-block; margin-top:10px; text-decoration:none; color:#007bff; font-weight:bold;">Ir para Exercícios &rarr;</a>
        </div>
    </div>
</body>
</html>