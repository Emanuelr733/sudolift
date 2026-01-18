<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

require_once '../model/clsTreino.php';
setlocale(LC_TIME, 'pt_BR.UTF-8', 'pt_BR', 'portuguese');

$nome = $_SESSION['nome_usuario'];
$primeiroNome = explode(" ", $nome)[0];

$foto = isset($_SESSION['foto_usuario']) && !empty($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
$id_usuario = $_SESSION['id_usuario'];

$objTreino = new clsTreino();
$todosTreinosResult = $objTreino->listarMeusTreinos($id_usuario);
$totalTreinos = mysqli_num_rows($todosTreinosResult);
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
            if (!file_exists($caminhoFoto)) {
                $caminhoFoto = "https://via.placeholder.com/80";
            }
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
                <i class="fas fa-running"></i> Exercícios
            </a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn">
            <i class="fas fa-sign-out-alt"></i> Sair
        </a>
    </div>

    <div class="main-content">

        <div class="dashboard-header">
            <div class="welcome-text">
                <h1>Olá, <?php echo $primeiroNome; ?>! 👋</h1>
                <p>Vamos superar seus limites hoje?</p>
            </div>
            <div class="current-date">
                <i class="far fa-calendar"></i> <?php echo strftime('%d de %B de %Y', time()); ?>
            </div>
        </div>

        <div class="stats-grid">
            <!-- Card 1 -->
            <div class="stat-card">
                <div class="stat-header">
                    <div class="stat-icon icon-blue"><i class="fas fa-list-ul"></i></div>
                    <span class="stat-title">Rotinas Salvas</span>
                </div>
                <div class="stat-value"><?php echo $totalTreinos; ?></div>
                <div class="stat-desc">
                    <span class="up"><i class="fas fa-upload"></i></span> Prontas para uso
                </div>
                <i class="fas fa-dumbbell stat-icon-bg"></i>
            </div>

            <!-- Card 2 -->
            <div class="stat-card" onclick="window.location='exercicios.php'" style="cursor:pointer">
                <div class="stat-header">
                    <div class="stat-icon icon-green"><i class="fas fa-running"></i></div>
                    <span class="stat-title">Biblioteca</span>
                </div>
                <div class="stat-value" style="font-size:20px;">Ver Exercícios</div>
                <div class="stat-desc">
                    Explore novas opções
                </div>
                <i class="fas fa-running stat-icon-bg"></i>
            </div>

            <div class="quote-card">
                <i class="fas fa-quote-left quote-icon"></i>
                <p class="quote-text">Carregando citação...</p>
                <span class="quote-author">- Carregando...</span>
            </div>
        </div>

        <!-- GIF Card (Full Width) -->
        <div class="content-card iframe-card">
            <iframe
                src="https://tenor.com/embed/3702210595977902030"
                frameborder="0">
            </iframe>
        </div>
    </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('../api/citacao/')
                .then(response => response.json())
                .then(data => {
                    if (data.texto && data.autor) {
                        document.querySelector('.quote-text').innerText = '"' + data.texto + '"';
                        document.querySelector('.quote-author').innerText = '- ' + data.autor;
                    }
                })
                .catch(err => console.error('Erro ao buscar citação:', err));
        });
    </script>
</body>

</html>