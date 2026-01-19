<?php
session_start();
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit();
}

// Verifica permissão (apenas escrivão)
if ($_SESSION['perfil_usuario'] != 'escrivao') {
    echo "Acesso Negado.";
    exit();
}

$nome = $_SESSION['nome_usuario'];
$foto = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';

require_once '../model/clsCitacao.php';
$objCitacao = new clsCitacao();

// Paginação
$itensPorPagina = 5;
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
if ($pagina < 1) $pagina = 1;
$inicio = ($pagina - 1) * $itensPorPagina;

$todasCitacoes = $objCitacao->listarPaginado($inicio, $itensPorPagina);
$totalCitacoes = $objCitacao->contarTotal();
$totalPaginas = ceil($totalCitacoes / $itensPorPagina);

// Lógica de Edição (se id_edit for passado)
$editando = false;
$id_edit = '';
$desc_edit = '';
$autor_edit = '';

if (isset($_GET['id_edit'])) {
    $dados = $objCitacao->buscarPorId($_GET['id_edit']);
    if ($dados) {
        $editando = true;
        $id_edit = $dados['id'];
        $desc_edit = $dados['descricao'];
        $autor_edit = $dados['autor'];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>SudoLift - Citações</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/citacoes.css">
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
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>

            <?php if ($_SESSION['perfil_usuario'] != 'escrivao'): ?>
                <a href="rotinas.php" class="menu-item"><i class="fas fa-dumbbell"></i> Rotinas</a>
                <a href="exercicios.php" class="menu-item"><i class="fas fa-running"></i> Exercícios</a>
            <?php endif; ?>

            <?php if ($_SESSION['perfil_usuario'] == 'escrivao'): ?>
                <a href="citacoes.php" class="menu-item ativo"><i class="fas fa-quote-right"></i> Editar Citações</a>
            <?php endif; ?>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="center-panel">
        <div class="center-header" style="margin-bottom: 30px;">
            <h1 style="font-size: 28px; color: #1f2937;">Gerenciar Citações</h1>
        </div>

        <div class="form-container">
            <form action="../controller/ctrl_Citacao.php" method="POST">
                <input type="hidden" name="acao" value="<?php echo $editando ? 'editar' : 'inserir'; ?>">
                <?php if ($editando): ?>
                    <input type="hidden" name="id_citacao" value="<?php echo $id_edit; ?>">
                <?php endif; ?>

                <div class="form-row">
                    <div class="form-group" style="flex:3;">
                        <label>Citação</label>
                        <input type="text" name="descricao" class="form-control" placeholder="Digite a frase..." value="<?php echo htmlspecialchars($desc_edit); ?>" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label>Autor</label>
                        <input type="text" name="autor" class="form-control" placeholder="Autor" value="<?php echo htmlspecialchars($autor_edit); ?>" required>
                    </div>
                </div>
                <div style="text-align: right;">
                    <?php if ($editando): ?>
                        <a href="citacoes.php" style="color: #6b7280; margin-right: 15px; text-decoration: none;">Cancelar</a>
                    <?php endif; ?>
                    <button type="submit" class="btn-salvar"><?php echo $editando ? 'Atualizar' : 'Salvar'; ?></button>
                </div>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Citação</th>
                        <th>Autor</th>
                        <th style="width: 100px;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($c = mysqli_fetch_assoc($todasCitacoes)): ?>
                        <tr>
                            <td><?php echo $c['descricao']; ?></td>
                            <td><?php echo $c['autor']; ?></td>
                            <td class="actions">
                                <a href="citacoes.php?id_edit=<?php echo $c['id']; ?>&pagina=<?php echo $pagina; ?>" class="btn-icon btn-edit"><i class="fas fa-edit"></i></a>
                                <a href="../controller/ctrl_Citacao.php?acao=excluir&id=<?php echo $c['id']; ?>" class="btn-icon btn-delete" onclick="return confirm('Tem certeza?');"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>

        <!-- Paginação -->
        <?php if ($totalPaginas > 1): ?>
            <div class="pagination">
                <?php if ($pagina > 1): ?>
                    <a href="?pagina=<?php echo $pagina - 1; ?>" class="page-link">&laquo; Anterior</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
                    <a href="?pagina=<?php echo $i; ?>" class="page-link <?php echo ($i == $pagina) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>

                <?php if ($pagina < $totalPaginas): ?>
                    <a href="?pagina=<?php echo $pagina + 1; ?>" class="page-link">Próxima &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>

</html>