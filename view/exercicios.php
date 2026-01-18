<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
$foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
$ehAdmin = (isset($_SESSION['perfil_usuario']) && $_SESSION['perfil_usuario'] == 'admin');
require_once '../model/clsExercicio.php';
$objExercicio = new clsExercicio();
$todosExercicios = $objExercicio->listar();
$exercicioSelecionado = null;
if (isset($_GET['id_ex'])) {
    mysqli_data_seek($todosExercicios, 0);
    while($ex = mysqli_fetch_assoc($todosExercicios)) {
        if ($ex['id'] == $_GET['id_ex']) {
            $exercicioSelecionado = $ex; 

            // --- ADICIONE ISSO ---
            // Busca os músculos na tabela nova para exibir as barrinhas e preencher a edição
            $vetorAtivacao = [];
            if(method_exists($objExercicio, 'listarAtivacao')) {
                $vetorAtivacao = $objExercicio->listarAtivacao($ex['id']);
            }
            // ---------------------

            break;
        }
    }
    mysqli_data_seek($todosExercicios, 0);
}
// Vetor normal (Lista simples de nomes)
$listaMusculos = [
    // Peito
    'Peitoral Superior',
    'Peitoral Médio',
    'Peitoral Inferior',

    // Ombros
    'Deltoide Anterior',
    'Deltoide Lateral',
    'Deltoide Posterior',

    // Costas
    'Dorsais',
    'Costas Superiores',
    'Trapézio',
    'Lombar',

    // Braços
    'Bíceps',
    'Tríceps',
    'Antebraço',

    // Core
    'Abdômen Superior',
    'Abdômen Inferior',
    'Oblíquos',

    // Pernas
    'Quadríceps',
    'Posterior de Coxa',
    'Glúteo',
    'Adutores',
    'Abdutores',
    'Panturrilha'
];

// Monta o HTML das opções aqui mesmo no PHP usando foreach
$opcoesSelect = '<option value="">Selecione...</option>';
foreach($listaMusculos as $musculo) {
    // Adiciona cada opção na string gigante
    $opcoesSelect .= '<option value="'.$musculo.'">'.$musculo.'</option>';
}

// Remove quebras de linha para não quebrar o JavaScript
$opcoesSelect = str_replace(["\r", "\n"], '', $opcoesSelect);
$listaEquipamentos = ["Nenhum", "Barra", "Anilha", "Haltere", "Máquina", "Outro"];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Exercícios</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/exercicios.css">
</head>
<body>
    <div class="sidebar">
        <div class="perfil-area">
            <a href="perfil.php" style="text-decoration:none; color:inherit; display:block;">
                <?php 
                $caminhoFoto = "../assets/images/users/" . $foto;
                if (!file_exists($caminhoFoto)) { $caminhoFoto = "https://via.placeholder.com/80"; }
                ?>
                <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
                <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
            </a>
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
                        <video src="<?php echo $img; ?>" class="detail-media" autoplay muted loop playsinline></video>
                    <?php else: ?>
                        <img src="<?php echo $img; ?>" class="detail-media">
                    <?php endif; ?>
                    <div class="detail-title"><?php echo $exercicioSelecionado['nome']; ?></div>
                    <div class="tags-container">
                        <span class="tag equip">Equipamento: <?php echo $exercicioSelecionado['equipamento']; ?></span>
                    </div>
                    <div class="info-grid">
                        <div class="info-item">
                            <label>Músculos</label>
                            <span>
                                <?php
                                // Exibe os músculos com barras
                                if (!empty($vetorAtivacao)) {
                                    foreach($vetorAtivacao as $item) {
                                        echo '<div style="margin-bottom:5px;">';
                                        echo '<strong>'.$item['musculo'].': </strong>';
                                        $larguraBarra = intval($item['fator'] * 100); 
                                        echo '<div style="background:#e0e0e0; width:100%; height:10px; border-radius:5px; overflow:hidden;">';
                                        echo '<div style="background:#76c7c0; width:'.$larguraBarra.'%; height:10px;"></div>';
                                        echo '</div>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo 'Nenhum músculo secundário mapeado.';
                                }
                                ?>
                            </span>
                        </div>
                        <div class="info-item">
                            <label>ID Sistema</label>
                            <span>#<?php echo $exercicioSelecionado['id']; ?></span>
                        </div>
                    </div>
                    <?php if($ehAdmin): ?>
                        <div class="admin-actions">
                            <button class="btn-action btn-yellow" onclick='editarExercicio(<?php echo json_encode($exercicioSelecionado); ?>, <?php echo json_encode($vetorAtivacao); ?>)'>
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
    <div class="library-filters">
        <div class="filter-row">
            <select id="filtroMusculo" class="lib-select" onchange="filtrarBiblioteca()">
                <option value="">Todos Músculos</option>
                <?php foreach($listaMusculos as $m): ?>
                    <option value="<?php echo strtolower($m); ?>"><?php echo $m; ?></option>
                <?php endforeach; ?>
            </select>
            
            <select id="filtroEquip" class="lib-select" onchange="filtrarBiblioteca()">
                <option value="">Todos Equipamentos</option>
                <?php foreach($listaEquipamentos as $e): ?>
                    <option value="<?php echo $e; ?>"><?php echo $e; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <input type="text" id="buscaExercicio" class="lib-search" placeholder="Buscar exercício..." onkeyup="filtrarBiblioteca()">
    </div>
    
    <div class="library-list">
        <?php
        if ($todosExercicios && mysqli_num_rows($todosExercicios) > 0) {
            mysqli_data_seek($todosExercicios, 0); 
            
            while($ex = mysqli_fetch_assoc($todosExercicios)): 
                $img = "https://via.placeholder.com/50";
                if (!empty($ex['imagem']) && file_exists("../assets/images/exercises/" . $ex['imagem'])) {
                    $img = "../assets/images/exercises/" . $ex['imagem'];
                }
                $classeAtiva = (isset($_GET['id_ex']) && $_GET['id_ex'] == $ex['id']) ? 'active' : '';
                // AGORA É SIMPLES: Pega direto da coluna do banco
                $grupoMuscular = isset($ex['grupo_muscular']) ? strtolower($ex['grupo_muscular']) : '';
            ?>
                <a href="exercicios.php?id_ex=<?php echo $ex['id']; ?>" 
                   class="lib-item <?php echo $classeAtiva; ?>"
                   data-nome="<?php echo strtolower($ex['nome']); ?>"
                   data-equip="<?php echo $ex['equipamento']; ?>"
                   data-musculo="<?php echo $grupoMuscular; ?>"> 
                   <?php if(strpos($img, '.mp4') !== false): ?> 
                        <video src="<?php echo $img; ?>" class="lib-img"></video>
                    <?php else: ?> 
                        <img src="<?php echo $img; ?>" class="lib-img"> 
                    <?php endif; ?>
                    
                    <div class="lib-info">
                        <span class="lib-name"><?php echo $ex['nome']; ?></span>
                        <span class="lib-group">
                            <?php echo $ex['grupo_muscular']; ?> • <?php echo $ex['equipamento']; ?>
                        </span>
                    </div>
                    <i class="fas fa-chevron-right lib-arrow"></i>
                </a>
            <?php endwhile; 
        } else {
            echo '<p style="padding:20px; text-align:center; color:#999;">Nenhum exercício cadastrado.</p>';
        }
        ?>
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
                    <label style="display:flex; justify-content:space-between; align-items:center;">
                        Mapeamento Muscular
                        <small style="font-weight:normal; color:#888;">(1.0 = Foco Total, 0.5 = Médio, 0.1 = Leve)</small>
                    </label>

                    <div id="container-musculos" style="margin-bottom: 10px;">
                        </div>
                    <button type="button" class="btn-small" onclick="adicionarMusculo()" 
                            style="background:#e0e0e0; color:#333; padding:5px 10px; border-radius:4px; border:none; cursor:pointer; font-size:12px;">
                        <i class="fas fa-plus"></i> Adicionar Músculo
                    </button>
                </div>
                <button type="submit" class="btn-submit">Salvar Exercício</button>
            </form>
        </div>
    </div>
    <script src="../assets/js/exercicios.js"></script>
    <script>
        function adicionarMusculo(musculoVindoDoBanco = '', valorVindoDoBanco = '') {
            var container = document.getElementById('container-musculos');

            // 1. Pega o HTML que o PHP gerou lá em cima.
            // O PHP "escreve" o texto das options diretamente dentro dessa variável JS.
            var opcoesHTML = '<?php echo $opcoesSelect; ?>';

            // (Truque simples para marcar o "selected" sem usar JSON)
            if (musculoVindoDoBanco !== '') {
                // Procura o texto value="Nome" e substitui por value="Nome" selected
                var busca = 'value="' + musculoVindoDoBanco + '"';
                opcoesHTML = opcoesHTML.replace(busca, busca + ' selected');
            }

            // 2. Cria a linha visual
            var row = document.createElement('div');
            row.style.display = 'flex';
            row.style.gap = '10px';
            row.style.marginBottom = '8px';

            row.innerHTML = `
                <select name="musculos_nome[]" style="flex: 2; padding: 8px; border:1px solid #ddd; border-radius:4px;" required>
                    ${opcoesHTML}
                </select>

                <input type="number" name="musculos_valor[]" step="0.1" min="0.1" max="1.0" placeholder="1.0" 
                       value="${valorVindoDoBanco}"
                       style="flex: 1; padding: 8px; border:1px solid #ddd; border-radius:4px;" required>

                <button type="button" onclick="this.parentElement.remove()" 
                        style="background:#ffebee; color:#c62828; border:none; padding:0 10px; border-radius:4px; cursor:pointer;">
                    <i class="fas fa-times"></i>
                </button>
            `;

            container.appendChild(row);
        }
        // Função para abrir modal LIMPO (Novo Cadastro)
        function abrirModalNovo() {
            document.getElementById('modalCriar').style.display = 'flex';
            document.getElementById('formExercicio').reset();
            document.getElementById('tituloModal').innerText = 'Adicionar Exercício';
            document.getElementById('acao_form').value = 'inserir';
            document.getElementById('id_exercicio').value = '';
                                
            // Limpa os músculos e adiciona um vazio
            document.getElementById('container-musculos').innerHTML = '';
            adicionarMusculo(); 
        }
                                
        function fecharModal() {
            document.getElementById('modalCriar').style.display = 'none';
        }
                                
        // Função chamada pelo botão Alterar
        function editarExercicio(dadosGerais, listaMusculos) {
            // 1. Abre modal e preenche dados básicos
            document.getElementById('modalCriar').style.display = 'flex';
            document.getElementById('tituloModal').innerText = 'Editar Exercício';
            document.getElementById('acao_form').value = 'editar';
            document.getElementById('id_exercicio').value = dadosGerais.id;
            document.getElementById('input_nome').value = dadosGerais.nome;
            document.getElementById('input_equipamento').value = dadosGerais.equipamento;
                                
            // 2. Preenche os músculos
            const container = document.getElementById('container-musculos');
            container.innerHTML = ''; // Limpa tudo
                                
            if (listaMusculos && listaMusculos.length > 0) {
                // Para cada músculo que veio do banco, cria uma linha preenchida
                listaMusculos.forEach(item => {
                    adicionarMusculo(item.musculo, item.fator);
                });
            } else {
                adicionarMusculo(); // Se não tiver nada, cria um vazio
            }
        }
        function filtrarBiblioteca() {
            // 1. Pega os valores (convertendo para minúsculo para garantir que encontre)
            var textoBusca = document.getElementById('buscaExercicio').value.toLowerCase();
            var filtroEquip = document.getElementById('filtroEquip').value;
            var filtroMusculo = document.getElementById('filtroMusculo').value.toLowerCase(); // Agora pega valor único
                                
            var items = document.getElementsByClassName('lib-item');
                                
            for (var i = 0; i < items.length; i++) {
                // Pega os dados guardados nos atributos data-*
                var itemNome = items[i].getAttribute('data-nome');
                var itemEquip = items[i].getAttribute('data-equip');
                // Garante que é string, caso venha vazio
                var itemMusculo = (items[i].getAttribute('data-musculo') || '').toLowerCase();
                                
                // Lógica de filtro
                var matchTexto = itemNome.indexOf(textoBusca) > -1;
                var matchEquip = (filtroEquip === "" || itemEquip === filtroEquip);
                                
                // Verifica se o músculo selecionado está contido no exercício
                // Ex: Se o exercício é "Peitoral" e você filtra "Peitoral", dá match.
                var matchMusculo = (filtroMusculo === "" || itemMusculo.indexOf(filtroMusculo) > -1);
                                
                // Se passar em TODOS os testes, mostra. Senão, esconde.
                if (matchTexto && matchEquip && matchMusculo) {
                    items[i].style.display = "";
                } else {
                    items[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>