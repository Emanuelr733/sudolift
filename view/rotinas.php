<?php
// Arquivo: view/rotinas.php
session_start();
require_once '../model/clsTreino.php';
require_once '../model/clsItemTreino.php';

if (!isset($_SESSION['id_usuario'])) { header('Location: login.php'); exit(); }
$id_usuario = $_SESSION['id_usuario'];
$foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

// Busca Rotinas
$objTreino = new clsTreino();
$listaRotinas = $objTreino->listarMeusTreinos($id_usuario);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Minhas Rotinas</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { margin: 0; font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; display: flex; height: 100vh; overflow: hidden; }
        
        /* SIDEBAR */
        .sidebar { width: 260px; background: white; border-right: 1px solid #e0e0e0; display: flex; flex-direction: column; padding: 20px; }
        .perfil-area { text-align: center; margin-bottom: 40px; }
        .perfil-foto { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; border: 3px solid #007bff; margin-bottom: 10px; }
        .perfil-nome { font-weight: bold; font-size: 18px; margin: 0; }
        .menu-item { display: flex; align-items: center; padding: 12px 15px; color: #555; text-decoration: none; border-radius: 8px; margin-bottom: 5px; transition: 0.2s; font-weight: 500; }
        .menu-item:hover, .menu-item.ativo { background-color: #e3f2fd; color: #007bff; }
        .menu-item i { margin-right: 12px; width: 20px; text-align: center; }
        .sair-btn { margin-top: auto; color: #dc3545; }

        /* CONTEÚDO */
        .main-content { flex: 1; padding: 30px; overflow-y: auto; }
        
        .header-rotinas { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn-nova { background-color: #007bff; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; display: flex; align-items: center; gap: 8px; box-shadow: 0 2px 5px rgba(0,123,255,0.3); cursor: pointer; border: none; font-size: 14px;}
        .btn-nova:hover { background-color: #0056b3; }

        .grid-rotinas { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        
        .card-rotina { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); position: relative; transition: transform 0.2s; border: 1px solid transparent; }
        .card-rotina:hover { transform: translateY(-3px); border-color: #007bff; }
        
        .rotina-titulo { font-size: 18px; font-weight: bold; color: #333; margin-bottom: 5px; }
        .rotina-data { font-size: 12px; color: #999; margin-bottom: 15px; }
        .rotina-resumo { font-size: 14px; color: #666; margin-bottom: 20px; min-height: 40px; }
        
        /* --- MENU 3 PONTOS (Atualizado) --- */
        .menu-dots { position: absolute; top: 15px; right: 15px; cursor: pointer; color: #999; padding: 10px; z-index: 5; }
        .menu-dots:hover { color: #333; }
        
        .dropdown-content {
            display: none; /* Começa escondido */
            position: absolute;
            right: 10px;
            top: 40px;
            background-color: white;
            min-width: 140px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            border-radius: 6px;
            z-index: 10;
            overflow: hidden;
            border: 1px solid #eee;
        }
        /* Classe que o JS vai adicionar quando clicar */
        .show { display: block; }

        .dropdown-content a { color: black; padding: 12px 16px; text-decoration: none; display: block; font-size: 14px; }
        .dropdown-content a:hover { background-color: #f1f1f1; }
        .dropdown-content a.delete { color: #dc3545; }
        
        .btn-abrir { display: block; text-align: center; background: #f8f9fa; color: #333; padding: 8px; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px; }
        .btn-abrir:hover { background: #e2e6ea; }
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
            <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
        </div>

        <nav>
            <a href="dashboard.php" class="menu-item">
                <i class="fas fa-home"></i> Início
            </a>
            <a href="rotinas.php" class="menu-item ativo">
                <i class="fas fa-dumbbell"></i> Rotinas
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
                    
                    // Resumo dos exercícios
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
                    
                    // --- MENU 3 PONTOS COM CLICK ---
                    // O onclick chama a função JavaScript lá embaixo passando o ID único deste menu
                    echo '<i class="fas fa-ellipsis-v menu-dots" onclick="toggleMenu(\'menu_'.$rotina['id'].'\')"></i>';
                    
                    echo '<div id="menu_'.$rotina['id'].'" class="dropdown-content">';
                        echo '<a href="treino_detalhes.php?id_treino='.$rotina['id'].'">Editar Rotina</a>';
                        // Link de Excluir (ainda com alert, vamos implementar a lógica depois)
                        echo '<a href="#" onclick="if(confirm(\'Deseja excluir esta rotina inteira?\')) { window.location.href=\'../controller/ctrl_Treino.php?acao=excluir_treino&id='.$rotina['id'].'\' }" class="delete">Excluir</a>';
                    echo '</div>';
                    // -------------------------------

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
        /* Quando clicar no ícone, alterna a classe 'show' no menu correspondente */
        function toggleMenu(idMenu) {
            var menu = document.getElementById(idMenu);
            
            // Fecha outros menus que possam estar abertos
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                if (dropdowns[i].id !== idMenu) {
                    dropdowns[i].classList.remove('show');
                }
            }
            
            // Abre ou fecha o atual
            menu.classList.toggle("show");
            
            // Impede que o clique no ícone feche o menu imediatamente pelo evento window.onclick
            event.stopPropagation();
        }

        /* Fecha o menu se clicar em qualquer lugar fora dele */
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