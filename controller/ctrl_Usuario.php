<?php
session_start();
require_once 'clsConexao.php'; // Ajuste o caminho conforme sua estrutura

$conexao = new clsConexao();
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

if ($acao == 'editar_perfil') {
    $id_usuario = $_POST['id_usuario'];
    $nome = mysqli_real_escape_string($conexao->getConexao(), $_POST['nome']);
    $email = mysqli_real_escape_string($conexao->getConexao(), $_POST['email']);
    $nova_senha = $_POST['senha'];

    // 1. Monta o SQL básico (Nome e Email)
    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email'";

    // 2. Se digitou senha, criptografa e adiciona no SQL
    if (!empty($nova_senha)) {
        // Se você usa MD5 (como no login antigo):
        //$senhaHash = md5($nova_senha);
        // Se usa password_hash (recomendado):
        $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        $sql .= ", senha = '$senhaHash'";
    }

    // 3. Upload da Foto (se enviou)
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $novoNomeFoto = "user_" . $id_usuario . "_" . time() . "." . $extensao;
        $destino = "../assets/images/users/" . $novoNomeFoto;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $destino)) {
            $sql .= ", foto = '$novoNomeFoto'";
            
            // Atualiza a sessão IMEDIATAMENTE para a foto mudar na sidebar sem relogar
            $_SESSION['foto_usuario'] = $novoNomeFoto;
        }
    }

    $sql .= " WHERE id = $id_usuario";

    // 4. Executa
    if ($conexao->executaSQL($sql)) {
        // Atualiza o nome na sessão também
        $_SESSION['nome_usuario'] = $nome;
        
        echo "<script>
                alert('Perfil atualizado com sucesso!');
                window.location.href = '../view/perfil.php';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao atualizar perfil.');
                window.location.href = '../view/perfil.php';
              </script>";
    }
}
?>