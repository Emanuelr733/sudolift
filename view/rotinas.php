<?php
session_start();
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil_usuario'] == 'escrivao') {
    header('Location: login.php');
    exit();
}

require_once '../model/clsTreino.php';
require_once '../model/clsItemTreino.php';

$id_usuario = $_SESSION['id_usuario'];
$foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

$objTreino = new clsTreino();
$listaRotinas = $objTreino->listarMeusTreinos($id_usuario);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>SudoLift - Minhas Rotinas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/rotinas.css">
</head>

<body>
    <div class="sidebar">
        <div class="perfil-area">
            <a href="perfil.php" style="text-decoration:none; color:inherit; display:block;">
                <?php
                $caminhoFoto = "../assets/images/users/" . $foto;
                if (!file_exists($caminhoFoto)) {
                    $caminhoFoto = "https://via.placeholder.com/80";
                }
                ?>
                <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
                <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
            </a>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item">
                <i class="fas fa-home"></i> Início
            </a>
            <?php if ($_SESSION['perfil_usuario'] != 'escrivao'): ?>
                <a href="rotinas.php" class="menu-item ativo"><i class="fas fa-dumbbell"></i> Rotinas</a>
                <a href="exercicios.php" class="menu-item"><i class="fas fa-running"></i> Exercícios</a>
            <?php else: ?>
                <a href="citacoes.php" class="menu-item"><i class="fas fa-quote-right"></i> Editar Citações</a>
            <?php endif; ?>
            <?php if(isset($_SESSION['perfil_usuario']) && $_SESSION['perfil_usuario'] == 'admin'): ?>
                <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 0;"></div>
                
                <a href="admin.php" class="menu-item" style="color: #ff6b6b;">
                    <i class="fas fa-user-shield"></i> Painel Admin
                </a>
            <?php endif; ?>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="main-content">
        <div class="header-rotinas">
            <div>
                <h1>Minhas Rotinas</h1>
                <p style="color:#666; font-size:14px; margin-top:5px;">Gerencie seus treinos e acompanhe seu progresso.</p>
            </div>

            <form action="../controller/ctrl_Treino.php" method="POST" style="margin:0;">
                <input type="hidden" name="acao" value="novo">
                <input type="hidden" name="nome_treino" value="Nova Rotina">
                <button type="submit" class="btn-nova">
                    <i class="fas fa-plus"></i> Criar Rotina
                </button>
            </form>
        </div>

        <div class="grid-rotinas">
            <?php
            if (mysqli_num_rows($listaRotinas) > 0) {
                while ($rotina = mysqli_fetch_assoc($listaRotinas)) {
                    $objItem = new clsItemTreino();
                    $itens = $objItem->listarDoTreino($rotina['id']);

                    // Lógica para Resumo e Tags de Músculos
                    $count = 0;
                    $musculosEncontrados = [];

                    while ($ex = mysqli_fetch_assoc($itens)) {
                        $count++;
                        // Coleta os grupos musculares (se existirem e não forem vazios)
                        if (!empty($ex['grupo_muscular'])) {
                            $musculosEncontrados[] = $ex['grupo_muscular'];
                        }
                    }

                    // Remove duplicados e pega os 2 primeiros para mostrar na tag
                    $musculosUnicos = array_unique($musculosEncontrados);
                    $tagsMusculos = array_slice($musculosUnicos, 0, 3);

                    // Define ícone baseado no nome (frescura visual legal)
                    $iconeTreino = "fa-dumbbell";
                    $nomeLower = strtolower($rotina['nome_treino']);
                    if (strpos($nomeLower, 'perna') !== false) $iconeTreino = "fa-running";
                    if (strpos($nomeLower, 'costa') !== false) $iconeTreino = "fa-child";
                    if (strpos($nomeLower, 'cardio') !== false) $iconeTreino = "fa-heartbeat";

            ?>
                    <div class="card-rotina">
                        <div class="card-header-visual">
                            <i class="fas <?php echo $iconeTreino; ?>"></i>
                        </div>

                        <div class="card-body">
                            <div class="rotina-top">
                                <div class="rotina-titulo"><?php echo $rotina['nome_treino']; ?></div>
                                <div class="menu-container">
                                    <i class="fas fa-ellipsis-v menu-dots" onclick="toggleMenu('menu_<?php echo $rotina['id']; ?>')"></i>
                                    <div id="menu_<?php echo $rotina['id']; ?>" class="dropdown-content">
                                        <a href="treino_detalhes.php?id_treino=<?php echo $rotina['id']; ?>">
                                            <i class="fas fa-edit"></i> Editar
                                        </a>
                                        <a href="#" onclick="if(confirm('Deseja excluir esta rotina inteira?')) { window.location.href='../controller/ctrl_Treino.php?acao=excluir_treino&id=<?php echo $rotina['id']; ?>' }" class="delete">
                                            <i class="fas fa-trash"></i> Excluir
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="rotina-info">
                                <span><i class="far fa-calendar-alt"></i> <?php echo date('d/m/Y', strtotime($rotina['data_treino'])); ?></span>
                                <span><i class="fas fa-list-ul"></i> <?php echo $count; ?> exercícios</span>
                            </div>

                            <div class="tags-area">
                                <?php if (count($tagsMusculos) > 0): ?>
                                    <?php foreach ($tagsMusculos as $musculo): ?>
                                        <span class="mini-tag"><?php echo $musculo; ?></span>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <span class="mini-tag" style="background:#f0f0f0; color:#999;">Geral</span>
                                <?php endif; ?>
                            </div>

                            <a href="treino_detalhes.php?id_treino=<?php echo $rotina['id']; ?>" class="btn-abrir">
                                Abrir Treino <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                <?php
                }
            } else {
                ?>
                <div class="empty-state">
                    <div class="empty-icon"><i class="fas fa-clipboard-list"></i></div>
                    <h3>Você ainda não tem rotinas.</h3>
                    <p>Crie sua primeira rotina de treino para começar a acompanhar sua evolução.</p>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

    <script>
        function toggleMenu(idMenu) {
            var menu = document.getElementById(idMenu);
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].id !== idMenu) {
                    dropdowns[i].classList.remove('show');
                }
            }
            menu.classList.toggle("show");
            event.stopPropagation();
        }

        window.onclick = function(event) {
            if (!event.target.matches('.menu-dots')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>

</html>