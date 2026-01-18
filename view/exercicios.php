<?php
session_start();

if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil_usuario'] == 'escrivao') {
    header('Location: login.php');
    exit();
}

// Configurações visuais e de acesso
$foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
$permissao = (isset($_SESSION['perfil_usuario']) && ($_SESSION['perfil_usuario'] == 'admin' || $_SESSION['perfil_usuario'] == 'instrutor'));

require_once '../model/clsExercicio.php';
$objExercicio = new clsExercicio();

// Busca a lista completa para exibir na tabela/sidebar
$todosExercicios = $objExercicio->listar();

// Em vez de percorrer todos os dados com um while, buscamos direto no banco.
$exercicioSelecionado = null;
$vetorAtivacao = [];

if (isset($_GET['id_ex'])) {
    $id_ex = (int)$_GET['id_ex'];
    
    // Usa o método eficiente do Model
    $exercicioSelecionado = $objExercicio->buscarPorId($id_ex);
    
    // Se achou o exercício, busca os músculos
    if ($exercicioSelecionado) {
        $vetorAtivacao = $objExercicio->listarAtivacao($id_ex);
    }
}

$listaMusculos = ['Peitoral Superior', 'Peitoral Médio', 'Peitoral Inferior', 'Deltoide Anterior', 'Deltoide Lateral', 'Deltoide Posterior', 'Dorsais', 'Costas Superiores', 'Trapézio', 'Lombar', 'Bíceps', 'Tríceps', 'Antebraço', 'Abdômen Superior', 'Abdômen Inferior', 'Oblíquos', 'Quadríceps', 'Posterior de Coxa', 'Glúteo', 'Adutores', 'Abdutores', 'Panturrilha'];

// Monta o HTML do select de músculos (Performance: faz isso apenas uma vez no server)
$opcoesSelect = '<option value="">Selecione...</option>';
foreach ($listaMusculos as $musculo) {
    $opcoesSelect .= '<option value="' . $musculo . '">' . $musculo . '</option>';
}
// Remove quebras de linha para evitar erro se for usado dentro de JavaScript
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
                <img src="../assets/images/users/<?php echo $foto; ?>" 
                     class="perfil-foto" 
                     onerror="this.src='../assets/images/users/padrao.png'">

                <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
            </a>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>
            <?php if ($_SESSION['perfil_usuario'] != 'escrivao'): ?>
                <a href="rotinas.php" class="menu-item"><i class="fas fa-dumbbell"></i> Rotinas</a>
                <a href="exercicios.php" class="menu-item ativo"><i class="fas fa-running"></i> Exercícios</a>
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
    <div class="center-panel">
        <div class="center-header">
            <h1>Exercícios</h1>
            <?php if ($permissao): ?>
                <button class="btn-criar" onclick="abrirModalNovo()">+ Novo Exercício</button>
            <?php endif; ?>
        </div>
        
        <div class="center-content">
            <?php if ($exercicioSelecionado): ?>
                <div class="detail-card">
                    
                    <?php
                    // Lógica simplificada de Mídia (sem file_exists para não travar o carregamento)
                    $arquivo = !empty($exercicioSelecionado['imagem']) ? $exercicioSelecionado['imagem'] : '';
                    $caminho = "../assets/images/exercises/" . $arquivo;
                    $isVideo = (stripos($arquivo, '.mp4') !== false);
                    ?>

                    <?php if ($isVideo): ?>
                        <video src="<?php echo $caminho; ?>" class="detail-media" autoplay muted loop playsinline></video>
                    <?php else: ?>
                        <img src="<?php echo $caminho; ?>" 
                             class="detail-media" 
                             onerror="this.src='../assets/images/exercises/sem_imagem.png'">
                    <?php endif; ?>

                    <div class="detail-title"><?php echo htmlspecialchars($exercicioSelecionado['nome']); ?></div>
                    
                    <div class="tags-container">
                        <span class="tag equip">
                            Equipamento: <?php echo htmlspecialchars($exercicioSelecionado['equipamento']); ?>
                        </span>
                    </div>

                    <div class="info-grid">
                        <div class="info-item">
                            <label>Músculos</label>
                            <span>
                                <?php
                                if (!empty($vetorAtivacao)) {
                                    foreach ($vetorAtivacao as $item) {
                                        // Cálculo da barra de progresso
                                        $largura = intval($item['fator'] * 100);
                                        $nomeMusc = htmlspecialchars($item['musculo']);
                                        
                                        echo '<div style="margin-bottom:5px;">';
                                        echo "<strong>{$nomeMusc}: </strong>";
                                        echo '<div style="background:#e0e0e0; width:100%; height:10px; border-radius:5px; overflow:hidden;">';
                                        echo '<div style="background:#76c7c0; width:' . $largura . '%; height:10px;"></div>';
                                        echo '</div></div>';
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

                    <?php if ($permissao): ?>
                        <div class="admin-actions">
                            <button class="btn-action btn-yellow" onclick='editarExercicio(<?php echo json_encode($exercicioSelecionado); ?>, <?php echo json_encode($vetorAtivacao); ?>)'>
                                <i class="fas fa-edit"></i> Alterar
                            </button>
                            
                            <a href="../controller/ctrl_Exercicio.php?acao=excluir&id=<?php echo $exercicioSelecionado['id']; ?>" 
                               class="btn-action btn-red" 
                               onclick="return confirm('Tem certeza que deseja excluir este exercício permanentemente?')">
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
                    <?php foreach ($listaMusculos as $m): ?>
                        <option value="<?php echo htmlspecialchars(strtolower($m)); ?>">
                            <?php echo htmlspecialchars($m); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <select id="filtroEquip" class="lib-select" onchange="filtrarBiblioteca()">
                    <option value="">Todos Equipamentos</option>
                    <?php foreach ($listaEquipamentos as $e): ?>
                        <option value="<?php echo htmlspecialchars($e); ?>">
                            <?php echo htmlspecialchars($e); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="search" id="buscaExercicio" class="lib-search" placeholder="Buscar exercício..." onkeyup="filtrarBiblioteca()">
        </div>

        <div class="library-list">
            <?php
            if ($todosExercicios && mysqli_num_rows($todosExercicios) > 0) {
                // Reinicia o ponteiro caso tenha sido usado antes
                mysqli_data_seek($todosExercicios, 0);

                while ($ex = mysqli_fetch_assoc($todosExercicios)):
                    // Lógica simplificada de imagem (sem file_exists)
                    $nomeArquivo = !empty($ex['imagem']) ? $ex['imagem'] : '';
                    $caminhoImg = "../assets/images/exercises/" . $nomeArquivo;
                    
                    // Verifica se é vídeo de forma rápida
                    $isVideo = (stripos($nomeArquivo, '.mp4') !== false);
                    
                    // Classes e Dados para o Filtro JS
                    $classeAtiva = (isset($_GET['id_ex']) && $_GET['id_ex'] == $ex['id']) ? 'active' : '';
                    $grupoMuscular = isset($ex['grupo_muscular']) ? strtolower($ex['grupo_muscular']) : '';
            ?>
                    <a href="exercicios.php?id_ex=<?php echo $ex['id']; ?>"
                       class="lib-item <?php echo $classeAtiva; ?>"
                       data-nome="<?php echo htmlspecialchars(strtolower($ex['nome'])); ?>"
                       data-equip="<?php echo htmlspecialchars($ex['equipamento']); ?>"
                       data-musculo="<?php echo htmlspecialchars($grupoMuscular); ?>">
                        
                        <?php if ($isVideo): ?>
                            <video src="<?php echo $caminhoImg; ?>" class="lib-img"></video>
                        <?php else: ?>
                            <img src="<?php echo $caminhoImg; ?>" 
                                 class="lib-img" 
                                 onerror="this.src='../assets/images/exercises/sem_imagem.png'">
                        <?php endif; ?>

                        <div class="lib-info">
                            <span class="lib-name"><?php echo htmlspecialchars($ex['nome']); ?></span>
                            <span class="lib-group">
                                <?php echo htmlspecialchars($ex['grupo_muscular']); ?> • <?php echo htmlspecialchars($ex['equipamento']); ?>
                            </span>
                        </div>
                        <i class="fas fa-chevron-right lib-arrow"></i>
                    </a>
            <?php 
                endwhile;
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
                    <input type="file" name="arquivo" accept="image/*,video/mp4">
                    <small style="color:#999; font-size:11px;">Deixe vazio para manter a atual na edição.</small>
                </div>

                <div class="form-group">
                    <label>Nome do Exercício</label>
                    <input type="text" name="nome" id="input_nome" placeholder="Ex: Supino Reto" required>
                </div>

                <div class="form-group">
                    <label>Equipamento</label>
                    <select name="equipamento" id="input_equipamento">
                        <?php foreach ($listaEquipamentos as $equip): ?>
                            <option value="<?php echo htmlspecialchars($equip); ?>">
                                <?php echo htmlspecialchars($equip); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label style="display:flex; justify-content:space-between; align-items:center;">
                        Mapeamento Muscular
                        <small style="font-weight:normal; color:#888;">(1.0 = Foco Total, 0.5 = Médio)</small>
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
    <script>
        function adicionarMusculo(musculoVindoDoBanco = '', valorVindoDoBanco = '') {
            var container = document.getElementById('container-musculos');
            
            // Pega a lista de opções gerada pelo PHP na Parte 1
            var opcoesHTML = '<?php echo $opcoesSelect; ?>';
            
            // Se for edição, marca o option correto como selected
            if (musculoVindoDoBanco !== '') {
                var busca = 'value="' + musculoVindoDoBanco + '"';
                opcoesHTML = opcoesHTML.replace(busca, busca + ' selected');
            }
            
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

        function abrirModalNovo() {
            document.getElementById('modalCriar').style.display = 'flex';
            document.getElementById('formExercicio').reset();
            document.getElementById('tituloModal').innerText = 'Adicionar Exercício';
            document.getElementById('acao_form').value = 'inserir';
            document.getElementById('id_exercicio').value = '';
            
            // Limpa lista de músculos e adiciona um vazio
            document.getElementById('container-musculos').innerHTML = '';
            adicionarMusculo();
        }

        function fecharModal() {
            document.getElementById('modalCriar').style.display = 'none';
        }

        function editarExercicio(dadosGerais, listaMusculos) {
            document.getElementById('modalCriar').style.display = 'flex';
            document.getElementById('tituloModal').innerText = 'Editar Exercício';
            document.getElementById('acao_form').value = 'editar';
            document.getElementById('id_exercicio').value = dadosGerais.id;
            
            document.getElementById('input_nome').value = dadosGerais.nome;
            document.getElementById('input_equipamento').value = dadosGerais.equipamento;
            
            const container = document.getElementById('container-musculos');
            container.innerHTML = '';
            
            if (listaMusculos && listaMusculos.length > 0) {
                listaMusculos.forEach(item => {
                    adicionarMusculo(item.musculo, item.fator);
                });
            } else {
                adicionarMusculo();
            }
        }

        // Fecha ao clicar fora
        window.onclick = function(event) {
            const modal = document.getElementById('modalCriar');
            if (event.target == modal) {
                fecharModal();
            }
        }

        function filtrarBiblioteca() {
            var textoBusca = document.getElementById('buscaExercicio').value.toLowerCase();
            var filtroEquip = document.getElementById('filtroEquip').value;
            var filtroMusculo = document.getElementById('filtroMusculo').value.toLowerCase();
            
            var items = document.getElementsByClassName('lib-item');
            
            for (var i = 0; i < items.length; i++) {
                var itemNome = items[i].getAttribute('data-nome');
                var itemEquip = items[i].getAttribute('data-equip');
                var itemMusculo = (items[i].getAttribute('data-musculo') || '').toLowerCase();
                
                var matchTexto = itemNome.indexOf(textoBusca) > -1;
                var matchEquip = (filtroEquip === "" || itemEquip === filtroEquip);
                var matchMusculo = (filtroMusculo === "" || itemMusculo.indexOf(filtroMusculo) > -1);
                
                if (matchTexto && matchEquip && matchMusculo) {
                    items[i].style.display = ""; // Mostra (flex ou block dependendo do CSS)
                } else {
                    items[i].style.display = "none";
                }
            }
        }
    </script>
</body>
</html>