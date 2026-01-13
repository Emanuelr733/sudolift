<?php
// Arquivo: view/treino_detalhes.php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
if (!isset($_GET['id_treino'])) { header('Location: rotinas.php'); exit(); }

$id_treino = $_GET['id_treino'];
$foto_user = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

require_once '../model/clsExercicio.php';
require_once '../model/clsItemTreino.php';
require_once '../model/clsTreino.php';
require_once '../model/clsSerie.php';

// --- ALTERAÇÃO 1: Buscando o nome real do treino ---
$objTreino = new clsTreino();
$dadosTreino = $objTreino->buscarPorId($id_treino); // Pega o nome atual (ex: "Nova Rotina")

// 1. Busca TODOS exercícios (Para a lista da direita)
$objExercicio = new clsExercicio();
$todosExercicios = $objExercicio->listar();

// 2. Busca ITENS do treino (Para o centro e sumário)
$objItem = new clsItemTreino();
$meusItens = $objItem->listarDoTreino($id_treino);

// Cálculos do Sumário
$totalExercicios = mysqli_num_rows($meusItens);
$totalSeries = 0;

// Precisamos reiniciar o ponteiro do banco para usar no loop depois
$arrayItens = [];
while($row = mysqli_fetch_assoc($meusItens)) {
    $totalSeries += $row['series'];
    $arrayItens[] = $row; // Guarda na memória para listar no centro
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Editor de Rotina</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        
        /* --- 1. SIDEBAR (ESQUERDA) --- */
        .sidebar { width: 260px; background: white; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 20px; flex-shrink: 0; }
        .perfil-area { text-align: center; margin-bottom: 40px; }
        .perfil-foto { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin-bottom: 10px; }
        .perfil-nome { font-weight: bold; font-size: 18px; margin: 0; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; color: #555; text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: 0.2s; font-weight: 500; }
        .menu-item:hover, .menu-item.ativo { background-color: #e3f2fd; color: #007bff; }
        .menu-item i { margin-right: 12px; width: 20px; text-align: center; }
        .sair-btn { margin-top: auto; color: #dc3545; }

        /* --- 2. CENTRO (EDITOR) --- */
        .center-panel { flex: 1; display: flex; flex-direction: column; background-color: #f4f7f6; border-right: 1px solid #e0e0e0; }
        
        /* Header do Centro */
        .center-header { 
            padding: 20px 30px; 
            background: white; 
            border-bottom: 1px solid #e0e0e0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center;
        }
        .back-link { text-decoration: none; color: #333; font-weight: bold; font-size: 18px; display: flex; align-items: center; gap: 10px; }
        .back-link:hover { color: #007bff; }
        .btn-salvar { background-color: #28a745; color: white; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 14px; }
        .btn-salvar:hover { background-color: #218838; }

        /* Lista de Itens (Scrollável) */
        .center-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .item-card { 
            background: white; 
            border-radius: 8px; 
            padding: 15px; 
            margin-bottom: 15px; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05); 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
        }
        .item-info h4 { margin: 0 0 5px 0; color: #333; }
        .item-info span { font-size: 13px; color: #777; }
        
        .item-inputs { display: flex; gap: 15px; align-items: center; }
        .input-group { text-align: center; }
        .input-group label { display: block; font-size: 10px; color: #999; text-transform: uppercase; margin-bottom: 2px;}
        .input-group input { width: 50px; text-align: center; padding: 5px; border: 1px solid #ddd; border-radius: 4px; }
        
        .btn-delete { color: #e74c3c; cursor: pointer; padding: 8px; margin-left: 10px; }

        /* --- 3. DIREITA (BIBLIOTECA) --- */
        .right-panel { width: 320px; background: white; display: flex; flex-direction: column; flex-shrink: 0; }
        
        /* Sumário */
        .summary-box { 
            padding: 20px; 
            border-bottom: 1px solid #e0e0e0; 
            display: flex; 
            justify-content: space-around; 
            background-color: #fafafa; 
        }
        .stat-item { text-align: center; }
        .stat-title { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .stat-value { font-size: 24px; font-weight: bold; color: #333; }

        /* Lista de Exercícios Disponíveis */
        .library-list { flex: 1; overflow-y: auto; padding: 0; }
        .lib-item { 
            display: flex; 
            align-items: center; 
            padding: 15px 20px; 
            border-bottom: 1px solid #f0f0f0; 
            transition: 0.2s; 
        }
        .lib-item:hover { background-color: #f9f9f9; }
        
        .lib-img { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; background: #eee; margin-right: 15px; }
        .lib-info { flex: 1; }
        .lib-name { font-size: 14px; font-weight: bold; color: #333; display: block; }
        .lib-group { font-size: 12px; color: #888; }
        
        .btn-add { 
            width: 30px; height: 30px; 
            background-color: #f0f2f5; 
            color: #007bff; 
            border-radius: 50%; 
            display: flex; align-items: center; justify-content: center; 
            text-decoration: none; 
            font-weight: bold;
            transition: 0.2s;
        }
        .btn-add:hover { background-color: #007bff; color: white; }

        /* --- ALTERAÇÃO 2: Estilo do Input de Nome --- */
        .rotina-nome-input {
            width: 100%;
            padding: 10px 30px;
            font-size: 24px;
            font-weight: bold;
            color: #333;
            border: none;
            background-color: transparent;
            outline: none;
            border-bottom: 2px solid transparent;
            transition: 0.3s;
            box-sizing: border-box; /* Importante para não quebrar o layout */
        }
        .rotina-nome-input:focus { 
            border-bottom: 2px solid #007bff; 
            background-color: rgba(255,255,255,0.5); 
        }
        .rotina-nome-input::placeholder { color: #ccc; }

        /* --- NOVO CSS PARA O CARD COMPLEXO --- */
        .item-card { 
            flex-direction: column; /* Muda para coluna */
            align-items: stretch; 
            gap: 15px; 
        }
        
        .card-header { display: flex; justify-content: space-between; align-items: center; }
        .header-left { display: flex; align-items: center; gap: 10px; }
        .card-img { width: 50px; height: 50px; border-radius: 6px; object-fit: cover; background: #eee; }
        
        /* Área de Notas e Tempo */
        .card-notes { 
            display: flex; 
            flex-direction: column; /* Um embaixo do outro */
            gap: 10px; 
            margin-bottom: 10px;
        }
        .note-input { width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; font-family: inherit; font-size: 13px; resize: vertical; box-sizing: border-box; }
        .time-input { 
            width: 100px; /* Largura fixa para ficar bonito */
            padding: 8px; 
            border: 1px solid #ddd; 
            border-radius: 4px; 
            text-align: center; 
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* Tabela */
        .set-table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .set-table th { font-size: 11px; color: #999; text-align: center; padding-bottom: 5px; }
        .set-table td { padding: 5px; text-align: center; }
        
        .set-number { background: #eee; width: 24px; height: 24px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 12px; font-weight: bold; color: #555; }
        
        /* Inputs da tabela */
        .input-mini { width: 60px; padding: 6px; border: 1px solid #ddd; border-radius: 4px; text-align: center; font-weight: bold; }
        .input-label {
            font-size: 11px;
            font-weight: bold;
            color: #555;
            margin-bottom: 3px;
            display: block;
            text-transform: uppercase;
        }
        
        /* Botão Adicionar Série */
        .btn-add-set { width: 100%; padding: 8px; background: #e3f2fd; color: #007bff; border: none; border-radius: 4px; cursor: pointer; font-weight: bold; margin-top: 5px; font-size: 13px; }
        .btn-add-set:hover { background: #bbdefb; }

        /* Botão X da linha da tabela */
        .btn-remove-row {
            color: #ccc;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            padding: 5px;
        }
        .btn-remove-row:hover { color: #e74c3c; }

    </style>
</head>
<body>

    <div class="sidebar">
        <div class="perfil-area">
            <?php 
            $caminhoFoto = "../images/" . $foto_user;
            if (!file_exists($caminhoFoto)) { $caminhoFoto = "https://via.placeholder.com/80"; }
            ?>
            <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
            <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>
            <a href="rotinas.php" class="menu-item ativo"><i class="fas fa-dumbbell"></i> Rotinas</a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="center-panel">
        
        <div class="center-header">
            <?php
            // LÓGICA DO BOTÃO VOLTAR
            // Verifica se esta rotina é a que está marcada como rascunho
            if (isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id_treino) {
                // SE FOR RASCUNHO: O botão voltar DELETA a rotina (Desistir)
                $linkVoltar = "../controller/ctrl_Treino.php?acao=excluir_treino&id=$id_treino";
                $textoVoltar = "Cancelar e Descartar";
            } else {
                // SE NÃO FOR RASCUNHO (Edição normal): Apenas volta
                $linkVoltar = "rotinas.php";
                $textoVoltar = "Voltar para Rotinas";
            }
            ?>

            <a href="<?php echo $linkVoltar; ?>" class="back-link">
                <i class="fas fa-arrow-left"></i> <?php echo $textoVoltar; ?>
            </a>
            
            <button class="btn-salvar" onclick="document.getElementById('formTreino').submit()">Salvar Rotina</button>
        </div>

        <form id="formTreino" method="POST" action="../controller/ctrl_ItemTreino.php" style="display: flex; flex-direction: column; height: 100%;">
            <input type="hidden" name="acao" value="atualizar_tudo">
            <input type="hidden" name="treino_id" value="<?php echo $id_treino; ?>">
            <input type="hidden" name="id_exercicio_add" id="id_exercicio_add" value="">

            <div style="padding: 10px 30px 0 30px;">
                <input type="text" name="nome_treino" class="rotina-nome-input" 
                       value="<?php echo $dadosTreino['nome_treino']; ?>" 
                       placeholder="Nome da Rotina (Ex: Treino A)">
            </div>

            <div class="center-content">
                <?php if(empty($arrayItens)): ?>
                    <div style="text-align:center; margin-top:50px; color:#999;">
                        <i class="fas fa-dumbbell" style="font-size:40px; margin-bottom:15px; opacity:0.3;"></i>
                        <p>Rotina vazia.</p>
                        <p>Adicione exercícios usando a lista da direita (+).</p>
                    </div>
                <?php else: ?>
                    
                    <div style="margin-top: 20px;">
                        <?php foreach($arrayItens as $item): ?>
                            
                            <?php 
                                // Busca Imagem
                                $imgEx = "../images/sem_imagem.png"; 
                                mysqli_data_seek($todosExercicios, 0); 
                                while($busca = mysqli_fetch_assoc($todosExercicios)) {
                                    if($busca['id'] == $item['exercicio_id'] && !empty($busca['imagem'])) {
                                        $imgEx = "../images/" . $busca['imagem']; break;
                                    }
                                }

                                // BUSCA AS SÉRIES INDIVIDUAIS DO BANCO
                                $objSerie = new clsSerie();
                                $listaSeries = $objSerie->listarPorItem($item['id']);
                            ?>

                            <div class="item-card">
                                
                                <div class="card-header">
                                    <div class="header-left">
                                        <?php if(strpos($imgEx, '.mp4') !== false): ?> 
                                            <video src="<?php echo $imgEx; ?>" class="card-img"></video>
                                        <?php else: ?> 
                                            <img src="<?php echo $imgEx; ?>" class="card-img"> 
                                        <?php endif; ?>
                                        
                                        <div>
                                            <h4 style="margin:0; color:#333;"><?php echo $item['nome_exercicio']; ?></h4>
                                            <span style="font-size:12px; color:#777;"><?php echo $item['grupo_muscular']; ?></span>
                                        </div>
                                    </div>
                                    
                                    <a href="../controller/ctrl_ItemTreino.php?acao=excluir&id_item=<?php echo $item['id']; ?>&id_treino=<?php echo $id_treino; ?>" class="btn-delete">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>

                                <div class="card-notes">
                                    <div>
                                        <label class="input-label">Observações</label>
                                        <textarea name="itens_pai[<?php echo $item['id']; ?>][obs]" class="note-input" rows="2"><?php echo isset($item['observacao']) ? $item['observacao'] : ''; ?></textarea>
                                    </div>
                                    <div>
                                        <label class="input-label">Descanso</label>
                                        <input type="text" name="itens_pai[<?php echo $item['id']; ?>][descanso]" class="time-input" placeholder="00:00" oninput="mascaraTempo(this)" value="<?php echo isset($item['descanso']) ? $item['descanso'] : ''; ?>">
                                    </div>
                                </div>

                                <table class="set-table">
                                    <thead>
                                        <tr>
                                            <th width="10%">SET</th>
                                            <th>KG</th>
                                            <th>REPS</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Loop nas séries reais do banco
                                        $contador = 1;
                                        while($serie = mysqli_fetch_assoc($listaSeries)): 
                                        ?>
                                        <tr>
                                            <td><span class="set-number"><?php echo $contador++; ?></span></td>
                                            <td>
                                                <input type="text" 
                                                       name="series_individuais[<?php echo $serie['id']; ?>][carga]" 
                                                       class="input-mini" 
                                                       value="<?php echo $serie['carga_kg']; ?>">
                                            </td>
                                            <td>
                                                <input type="text" 
                                                       name="series_individuais[<?php echo $serie['id']; ?>][reps]" 
                                                       class="input-mini" 
                                                       value="<?php echo $serie['repeticoes']; ?>">
                                            </td>
                                            <td>
                                                <a href="../controller/ctrl_ItemTreino.php?acao=remove_set&id_item=<?php echo $item['id']; ?>&id_treino=<?php echo $id_treino; ?>" class="btn-remove-row">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>

                                <a href="../controller/ctrl_ItemTreino.php?acao=add_set&id_item=<?php echo $item['id']; ?>&id_treino=<?php echo $id_treino; ?>" style="text-decoration:none;">
                                    <button type="button" class="btn-add-set">+ Adicionar Série</button>
                                </a>

                            </div>

                        <?php endforeach; ?>
                    </div>

                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="right-panel">
        
        <div class="summary-box">
            <div class="stat-item">
                <div class="stat-title">Exercícios</div>
                <div class="stat-value"><?php echo $totalExercicios; ?></div>
            </div>
            <div class="stat-item">
                <div class="stat-title">Séries</div>
                <div class="stat-value"><?php echo $totalSeries; ?></div>
            </div>
        </div>

        <div class="library-list">
            <div style="padding:15px; color:#888; font-size:12px; font-weight:bold;">BIBLIOTECA DE EXERCÍCIOS</div>
            
            <?php while($ex = mysqli_fetch_assoc($todosExercicios)): ?>
                <?php 
                    // Verifica mídia
                    $img = "https://via.placeholder.com/50";
                    if (!empty($ex['imagem']) && file_exists("../images/" . $ex['imagem'])) {
                        $img = "../images/" . $ex['imagem'];
                        // Se for video, mostra o mesmo icone ou uma thumb fixa por enquanto
                    }
                ?>
                <div class="lib-item">
                    <?php if(strpos($img, '.mp4') !== false): ?>
                        <video src="<?php echo $img; ?>" class="lib-img"></video>
                    <?php else: ?>
                        <img src="<?php echo $img; ?>" class="lib-img">
                    <?php endif; ?>

                    <div class="lib-info">
                        <span class="lib-name"><?php echo $ex['nome']; ?></span>
                        <span class="lib-group"><?php echo $ex['grupo_muscular']; ?></span>
                    </div>

                    <button type="button" class="btn-add" style="border:none; cursor:pointer;" onclick="adicionarExercicio(<?php echo $ex['id']; ?>)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            <?php endwhile; ?>

        </div>
    </div>
    <script>
    function adicionarExercicio(id) {
        // 1. Preenche o input escondido com o ID do exercício que você clicou
        document.getElementById('id_exercicio_add').value = id;
        
        // 2. Envia o formulário principal (isso salva o Nome E adiciona o item)
        document.getElementById('formTreino').submit();
    }
    // Função para formatar o tempo como 00:00 automaticamente
    function mascaraTempo(input) {
        // Remove tudo que não for número
        var valor = input.value.replace(/\D/g, "");
        
        // Limita a 4 números (MMSS)
        if (valor.length > 4) valor = valor.slice(0, 4);

        // Adiciona os dois pontos depois do segundo número
        if (valor.length > 2) {
            valor = valor.slice(0, 2) + ":" + valor.slice(2);
        }
        
        input.value = valor;
    }
    </script>

</body>
</html>