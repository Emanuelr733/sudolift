<?php
session_start();
require_once '../model/clsTreino.php';
require_once '../model/clsItemTreino.php';
if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
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
            <?php 
            $caminhoFoto = "../assets/images/users/" . $foto;
            if (!file_exists($caminhoFoto)) { $caminhoFoto = "https://via.placeholder.com/80"; }
            ?>
            <img src="<?php echo $caminhoFoto; ?>" class="perfil-foto">
            <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="rotinas.php" class="menu-item ativo">
                <i class="fas fa-dumbbell"></i> Rotinas
            </a>
            <a href="exercicios.php" class="menu-item">
                <i class="fas fa-dumbbell"></i> Exercícios
            </a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <div class="main-content">
        <div class="header-rotinas">
            <h1>Minhas Rotinas</h1>
            <form action="../controller/ctrl_Treino.php" method="POST" style="margin:0;">
                <input type="hidden" name="acao" value="novo">
                <input type="hidden" name="nome_treino" value="Nova Rotina">
                <button type="submit" class="btn-nova"><i class="fas fa-plus"></i> Nova Rotina</button>
            </form>
        </div>
        <div class="grid-rotinas">
            <?php
            if (mysqli_num_rows($listaRotinas) > 0) {
                while ($rotina = mysqli_fetch_assoc($listaRotinas)) {
                    $objItem = new clsItemTreino();
                    $itens = $objItem->listarDoTreino($rotina['id']);
                    $count = 0; $resumoExercicios = "";
                    while($ex = mysqli_fetch_assoc($itens)) {
                        if($count < 3) $resumoExercicios .= $ex['nome_exercicio'] . ", ";
                        $count++;
                    }
                    $resumoExercicios = rtrim($resumoExercicios, ", ");
                    if ($count > 3) $resumoExercicios .= "...";
                    if ($count == 0) $resumoExercicios = "Nenhum exercício.";
                    echo '<div class="card-rotina">';
                    echo '<i class="fas fa-ellipsis-v menu-dots" onclick="toggleMenu(\'menu_'.$rotina['id'].'\')"></i>';                    
                    echo '<div id="menu_'.$rotina['id'].'" class="dropdown-content">';
                        echo '<a href="treino_detalhes.php?id_treino='.$rotina['id'].'">Editar Rotina</a>';
                        echo '<a href="#" onclick="if(confirm(\'Deseja excluir esta rotina inteira?\')) { window.location.href=\'../controller/ctrl_Treino.php?acao=excluir_treino&id='.$rotina['id'].'\' }" class="delete">Excluir</a>';
                    echo '</div>';
                    echo '<div class="rotina-titulo">' . $rotina['nome_treino'] . '</div>';
                    echo '<div class="rotina-data">Criado em: ' . date('d/m/Y', strtotime($rotina['data_treino'])) . '</div>';
                    echo '<div class="rotina-resumo">';
                    echo '<i class="fas fa-list-ul" style="font-size:12px; margin-right:5px;"></i> ' . $count . ' exercícios<br>';
                    echo '<span style="font-size:12px; color:#999">' . $resumoExercicios . '</span>';
                    echo '</div>';
                    echo '<a href="treino_detalhes.php?id_treino='.$rotina['id'].'" class="btn-abrir">Ver Rotina</a>';
                    echo '</div>';
                }
            } else {
                echo "<p>Nenhuma rotina criada.</p>";
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