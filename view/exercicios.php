<?php
// Arquivo: view/exercicios.php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }

$foto_user = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
$ehAdmin = (isset($_SESSION['perfil_usuario']) && $_SESSION['perfil_usuario'] == 'admin');

require_once '../model/clsExercicio.php';

// 1. Busca TODOS os exercícios
$objExercicio = new clsExercicio();
$todosExercicios = $objExercicio->listar();

// 2. Detalhes do Selecionado
$exercicioSelecionado = null;
if (isset($_GET['id_ex'])) {
    mysqli_data_seek($todosExercicios, 0);
    while($ex = mysqli_fetch_assoc($todosExercicios)) {
        if ($ex['id'] == $_GET['id_ex']) {
            $exercicioSelecionado = $ex; break;
        }
    }
    mysqli_data_seek($todosExercicios, 0);
}

// ARRAYS PARA OS DROPDOWNS (Facilita a manutenção)
$listaMusculos = [
    "Abdominais", "Abdutores", "Adutores", "Antebraço", "Bíceps", "Corpo inteiro", 
    "Costas Superiores", "Dorsais", "Glúteos", "Isquiossurais", "Lombar", 
    "Ombros", "Panturrilhas", "Peito", "Quadríceps", "Trapézio", "Tríceps", "Outro"
];
$listaEquipamentos = ["Nenhum", "Barra", "Anilha", "Haltere", "Máquina", "Outro"];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Exercícios</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/sudolift/assets/css/style.css">
    <link rel="stylesheet" href="/sudolift/assets/css/exercicios.css">
</head>
<body>

    <div class="sidebar">
        <div class="perfil-area">
            <?php 
            $caminhoFoto = "../assets/images/users/" . $foto_user;
            if (!file_exists($caminhoFoto)) { $caminhoFoto = "https://via.placeholder.com/80"; }
            ?>
            <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
            <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>
            <a href="rotinas.php" class="menu-item"><i class="fas fa-dumbbell"></i> Rotinas</a>
            <a href="exercicios.php" class="menu-item ativo"><i class="fas fa-running"></i> Exercícios</a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="center-panel">
        <div class="center-header">
            <h1>Exercícios</h1>
            <?php if($ehAdmin): ?>
                <button class="btn-criar" onclick="abrirModalNovo()">+ Novo Exercício</button>
            <?php endif; ?>
        </div>

        <div class="center-content">
            <?php if ($exercicioSelecionado): ?>
                
                <div class="detail-card">
                    <?php 
                        $img = "../assets/images/exercises/sem_imagem.png";
                        if (!empty($exercicioSelecionado['imagem']) && file_exists("../assets/images/exercises/" . $exercicioSelecionado['imagem'])) {
                            $img = "../assets/images/exercises/" . $exercicioSelecionado['imagem'];
                        }
                    ?>

                    <?php if(strpos($img, '.mp4') !== false): ?>
                        <video src="<?php echo $img; ?>" class="detail-media" controls autoplay muted loop></video>
                    <?php else: ?>
                        <img src="<?php echo $img; ?>" class="detail-media">
                    <?php endif; ?>

                    <div class="detail-title"><?php echo $exercicioSelecionado['nome']; ?></div>
                    
                    <div class="tags-container">
                        <span class="tag primary">Primário: <?php echo $exercicioSelecionado['grupo_muscular']; ?></span>
                        <span class="tag equip">Equip: <?php echo $exercicioSelecionado['equipamento']; ?></span>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Músculos Secundários</label>
                            <span>
                                <?php echo !empty($exercicioSelecionado['grupo_secundario']) ? $exercicioSelecionado['grupo_secundario'] : 'Nenhum'; ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label>ID Sistema</label>
                            <span>#<?php echo $exercicioSelecionado['id']; ?></span>
                        </div>
                    </div>

                    <?php if($ehAdmin): ?>
                        <div class="admin-actions">
                            <button class="btn-action btn-yellow" onclick='editarExercicio(<?php echo json_encode($exercicioSelecionado); ?>)'>
                                <i class="fas fa-edit"></i> Alterar
                            </button>
                            
                            <a href="../controller/ctrl_Exercicio.php?acao=excluir&id=<?php echo $exercicioSelecionado['id']; ?>" class="btn-action btn-red" onclick="return confirm('Tem certeza que deseja excluir este exercício permanentemente?')">
                                <i class="fas fa-trash"></i> Excluir
                            </a>
                        </div>
                    <?php endif; ?>
                    </div>

            <?php else: ?>
                <div class="empty-card">
                    <i class="fas fa-dumbbell"></i>
                    <h3>Escolha um exercício na biblioteca<br>para visualizar os detalhes.</h3>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="right-panel">
        <div class="library-title">BIBLIOTECA</div>
        <div class="library-list">
            <?php while($ex = mysqli_fetch_assoc($todosExercicios)): ?>
                <?php 
                    $img = "https://via.placeholder.com/50";
                    if (!empty($ex['imagem']) && file_exists("../assets/images/exercises/" . $ex['imagem'])) $img = "../assets/images/exercises/" . $ex['imagem'];
                    $classeAtiva = (isset($_GET['id_ex']) && $_GET['id_ex'] == $ex['id']) ? 'active' : '';
                ?>
                <a href="exercicios.php?id_ex=<?php echo $ex['id']; ?>" class="lib-item <?php echo $classeAtiva; ?>">
                    <?php if(strpos($img, '.mp4') !== false): ?> <video src="<?php echo $img; ?>" class="lib-img"></video>
                    <?php else: ?> <img src="<?php echo $img; ?>" class="lib-img"> <?php endif; ?>
                    <div class="lib-info">
                        <span class="lib-name"><?php echo $ex['nome']; ?></span>
                        <span class="lib-group"><?php echo $ex['grupo_muscular']; ?></span>
                    </div>
                    <i class="fas fa-chevron-right lib-arrow"></i>
                </a>
            <?php endwhile; ?>
        </div>
    </div>

    <div id="modalCriar" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h2 id="tituloModal">Adicionar Exercício</h2>
                <span class="close-modal" onclick="fecharModal()">&times;</span>
            </div>
            
            <form action="../controller/ctrl_Exercicio.php" method="POST" enctype="multipart/form-data" id="formExercicio">
                
                <input type="hidden" name="acao" id="acao_form" value="inserir">
                <input type="hidden" name="id" id="id_exercicio" value="">
                
                <div class="form-group">
                    <label>Mídia (Imagem ou Vídeo MP4)</label>
                    <input type="file" name="arquivo">
                    <small style="color:#999; font-size:11px;">Deixe vazio para manter a atual na edição.</small>
                </div>

                <div class="form-group">
                    <label>Nome do Exercício</label>
                    <input type="text" name="nome" id="input_nome" placeholder="Ex: Supino Reto" required>
                </div>

                <div class="form-group">
                    <label>Equipamento</label>
                    <select name="equipamento" id="input_equipamento">
                        <?php foreach($listaEquipamentos as $equip): ?>
                            <option value="<?php echo $equip; ?>"><?php echo $equip; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Grupo Muscular Primário</label>
                    <select name="grupo_primario" id="input_primario" required>
                        <?php foreach($listaMusculos as $musculo): ?>
                            <option value="<?php echo $musculo; ?>"><?php echo $musculo; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Grupos Secundários</label>
                    <div class="checkbox-scroll">
                        <?php foreach($listaMusculos as $musculo): ?>
                            <label class="checkbox-item">
                                <input type="checkbox" name="grupo_secundario[]" value="<?php echo $musculo; ?>">
                                <?php echo $musculo; ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Salvar Exercício</button>
            </form>
        </div>
    </div>

    <script src="../assets/js/exercicios.js"></script>
</body>
</html>