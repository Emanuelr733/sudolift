<?php
session_start();
require_once '../model/clsAdmin.php';

// --- SEGURANÇA MÁXIMA ---
// Se não estiver logado OU não for admin, expulsa.
if (!isset($_SESSION['id_usuario']) || $_SESSION['perfil_usuario'] != 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Instancia a classe e busca os dados prontos
$objAdmin = new clsAdmin();

// 1. Busca Estatísticas (retorna um array)
$stats = $objAdmin->getEstatisticas();
$totalUsers = $stats['total_usuarios'];
$totalExer  = $stats['total_exercicios'];

// 2. Busca Lista de Usuários (retorna o objeto do banco)
$listaUsuarios = $objAdmin->listarUsuarios();

// Foto do usuário logado (para a sidebar)
$foto_user = isset($_SESSION['foto_usuario']) ? $_SESSION['foto_usuario'] : 'padrao.png';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>SudoLift - Painel Admin</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <div class="sidebar">
        <div class="perfil-area">
            <a href="perfil.php" style="text-decoration:none; color:inherit; display:block;">
                <img src="../assets/images/users/<?php echo $foto_user; ?>" class="perfil-foto">
                <h3 class="perfil-nome"><?php echo $_SESSION['nome_usuario']; ?></h3>
                <span class="perfil-tipo" style="color:#ff6b6b;">ADMINISTRADOR</span>
            </a>
        </div>
        <nav>
            <a href="dashboard.php" class="menu-item"><i class="fas fa-home"></i> Início</a>
            <a href="rotinas.php" class="menu-item"><i class="fas fa-dumbbell"></i> Rotinas</a>
            <a href="exercicios.php" class="menu-item"><i class="fas fa-running"></i> Exercícios</a>
            
            <div style="border-top: 1px solid rgba(255,255,255,0.1); margin: 10px 0;"></div>
            <a href="admin.php" class="menu-item ativo" style="color: #ff6b6b;">
                <i class="fas fa-user-shield"></i> Painel Admin
            </a>
        </nav>
        <a href="../controller/logout.php" class="menu-item sair-btn"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>

    <div class="main-content">
        <div class="admin-header">
            <h1>Administração do Sistema</h1>
            <p>Gerencie usuários e conteúdo global.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="icon-stat users"><i class="fas fa-users"></i></div>
                <div>
                    <h3><?php echo $totalUsers; ?></h3>
                    <span>Usuários Cadastrados</span>
                </div>
            </div>
            <div class="stat-card">
                <div class="icon-stat db"><i class="fas fa-database"></i></div>
                <div>
                    <h3><?php echo $totalExer; ?></h3>
                    <span>Exercícios no Banco</span>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="table-header">
                <h2>Gerenciar Usuários</h2>
            </div>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuário</th>
                        <th>Status Atual</th> <th>Alterar Cargo / Ações</th> </tr>
                </thead>
                <tbody>
    <?php while($u = mysqli_fetch_assoc($listaUsuarios)): 
        
        // --- LÓGICA DA FOTO (Igual à anterior) ---
        $nomeArquivo = isset($u['foto_perfil']) ? $u['foto_perfil'] : '';
        $caminhoFisico = __DIR__ . "/../assets/images/users/" . $nomeArquivo;
        $caminhoWeb = "../assets/images/users/" . $nomeArquivo;

        if (!empty($nomeArquivo) && file_exists($caminhoFisico)) {
            $fotoFinal = $caminhoWeb;
        } else {
            $fotoFinal = "../assets/images/users/padrao.png";
        }

        // --- LÓGICA DO PERFIL ---
        // Normaliza o nome do perfil para garantir que o CSS funcione
        $perfilDB = isset($u['perfil_usuario']) ? $u['perfil_usuario'] : (isset($u['perfil']) ? $u['perfil'] : 'usuario');
        
        // Define a classe CSS baseada no perfil
        $badgeClass = 'usuario';
        $badgeLabel = 'Atleta';

        if ($perfilDB == 'admin') {
            $badgeClass = 'admin';
            $badgeLabel = 'Admin';
        } elseif ($perfilDB == 'instrutor') {
            $badgeClass = 'instrutor';
            $badgeLabel = 'Instrutor';
        } elseif ($perfilDB == 'escrivao') {
            $badgeClass = 'escrivao';
            $badgeLabel = 'Escrivão';
        }
    ?>
    <tr>
        <td>#<?php echo $u['id']; ?></td>
        <td>
            <div class="user-cell">
                <img src="<?php echo $fotoFinal; ?>" class="mini-avatar">
                <span><?php echo $u['nome']; ?></span>
            </div>
        </td>
        <td>
            <span class="badge <?php echo $badgeClass; ?>"><?php echo $badgeLabel; ?></span>
        </td>
        <td>
            <div class="actions">
                
                <?php if($u['id'] != $_SESSION['id_usuario']): ?>
                
                <form action="../controller/ctrl_Admin.php" method="POST" style="display:flex; align-items:center; gap:5px;">
                    <input type="hidden" name="acao" value="alterar_nivel">
                    <input type="hidden" name="id_usuario" value="<?php echo $u['id']; ?>">
                    
                    <select name="novo_perfil" class="select-perfil">
                        <option value="usuario"   <?php echo ($perfilDB=='usuario')?'selected':''; ?>>Atleta</option>
                        <option value="instrutor" <?php echo ($perfilDB=='instrutor')?'selected':''; ?>>Instrutor</option>
                        <option value="escrivao"  <?php echo ($perfilDB=='escrivao')?'selected':''; ?>>Escrivão</option>
                        <option value="admin"     <?php echo ($perfilDB=='admin')?'selected':''; ?>>Admin</option>
                    </select>
                    
                    <button type="submit" class="btn-icon" title="Salvar Alteração" style="color:#28a745;">
                        <i class="fas fa-check-circle"></i>
                    </button>
                </form>

                <a href="#" onclick="confirmarExclusao(<?php echo $u['id']; ?>)" class="btn-icon delete" title="Excluir Usuário" style="margin-left:10px;">
                    <i class="fas fa-trash"></i>
                </a>

                <?php else: ?>
                    <span style="font-size:12px; color:#ccc;">(Você)</span>
                <?php endif; ?>
            </div>
        </td>
    </tr>
    <?php endwhile; ?>
</tbody>
            </table>
        </div>
    </div>

    <script>
        function confirmarExclusao(id) {
            if(confirm('Tem certeza? Isso apagará todos os treinos e dados deste usuário.')) {
                window.location.href = '../controller/ctrl_Admin.php?acao=excluir_usuario&id=' + id;
            }
        }
    </script>
</body>
</html>