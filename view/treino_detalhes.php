<?php
session_start();
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
if (!isset($_GET['id_treino'])) { header('Location: rotinas.php'); exit(); }
$id_treino = $_GET['id_treino'];
$foto_user = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
require_once '../model/clsExercicio.php';
require_once '../model/clsItemTreino.php';
require_once '../model/clsTreino.php';
require_once '../model/clsSerie.php';
$objTreino = new clsTreino();
$dadosTreino = $objTreino->buscarPorId($id_treino);
$objExercicio = new clsExercicio();
$todosExercicios = $objExercicio->listar();
$objItem = new clsItemTreino();
$meusItens = $objItem->listarDoTreino($id_treino);
$totalExercicios = mysqli_num_rows($meusItens);
$totalSeries = 0;
$arrayItens = [];
while($row = mysqli_fetch_assoc($meusItens)) {
    $totalSeries += $row['series'];
    $arrayItens[] = $row;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Editor de Rotina</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/treino_detalhes.css">
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
            <a href="rotinas.php" class="menu-item ativo"><i class="fas fa-dumbbell"></i> Rotinas</a>
            <a href="exercicios.php" class="menu-item"><i class="fas fa-dumbbell"></i> Exercícios</a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <div class="center-panel">
        <div class="center-header">
            <?php
            if (isset($_SESSION['rascunho_id']) && $_SESSION['rascunho_id'] == $id_treino) {
                $linkVoltar = "../controller/ctrl_Treino.php?acao=excluir_treino&id=$id_treino";
                $textoVoltar = "Cancelar e Descartar";
            } else {
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
                                $imgEx = "../assets/images/exercises/sem_imagem.png"; 
                                mysqli_data_seek($todosExercicios, 0); 
                                while($busca = mysqli_fetch_assoc($todosExercicios)) {
                                    if($busca['id'] == $item['exercicio_id'] && !empty($busca['imagem'])) {
                                        $imgEx = "../assets/images/exercises/" . $busca['imagem']; break;
                                    }
                                }
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
                    $img = "https://via.placeholder.com/50";
                    if (!empty($ex['imagem']) && file_exists("../assets/images/exercises/" . $ex['imagem'])) {
                        $img = "../assets/images/exercises/" . $ex['imagem'];
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
        document.getElementById('id_exercicio_add').value = id;
        document.getElementById('formTreino').submit();
    }
    function mascaraTempo(input) {
        var valor = input.value.replace(/\D/g, "");
        if (valor.length > 4) valor = valor.slice(0, 4);
        if (valor.length > 2) {
            valor = valor.slice(0, 2) + ":" + valor.slice(2);
        }
        input.value = valor;
    }
    </script>
</body>
</html>