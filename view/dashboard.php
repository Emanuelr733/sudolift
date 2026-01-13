<?php
// Arquivo: view/dashboard.php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }

$nome = $_SESSION['nome_usuario'];
$perfil = $_SESSION['perfil_usuario'];
$foto = isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

// Se for ADMIN, redireciona para a lista de exercícios (ou mantém o layout antigo)
if ($perfil == 'admin') {
    header('Location: exercicios_lista.php'); // Simplificando pro Admin
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"> <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        
        /* --- SIDEBAR (BARRA LATERAL) --- */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid #e0e0e0;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.02);
        }

        .perfil-area { text-align: center; margin-bottom: 40px; }
        .perfil-foto { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin-bottom: 10px; }
        .perfil-nome { font-weight: bold; font-size: 18px; color: #333; margin: 0; }
        .perfil-tipo { font-size: 12px; color: #777; background: #eee; padding: 2px 8px; border-radius: 10px; }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #555;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: 0.2s;
            font-weight: 500;
        }
        .menu-item:hover, .menu-item.ativo { background-color: #e3f2fd; color: #007bff; }
        .menu-item i { margin-right: 12px; width: 20px; text-align: center; }
        
        .sair-btn { margin-top: auto; color: #dc3545; }
        .sair-btn:hover { background-color: #fce8e8; color: #dc3545; }

        /* --- CONTEÚDO PRINCIPAL --- */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .card-aviso {
            background: white; padding: 40px; border-radius: 12px; text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05); max-width: 600px; margin: 50px auto;
        }
    </style>
</head>
<body>

    <div class="sidebar">
        <div class="perfil-area">
            <?php 
            $caminhoFoto = "../images/" . $foto;
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
        </div>
    </div>

</body>
</html>