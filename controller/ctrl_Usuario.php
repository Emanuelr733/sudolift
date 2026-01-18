<?php
session_start();
require_once 'clsConexao.php';
if (!isset($_SESSION['id_usuario'])) {
    header('Location: ../view/login.php');
    exit();
}

$conexao = new clsConexao();
$acao = isset($_POST['acao']) ? $_POST['acao'] : '';

if ($acao == 'editar_perfil') {
    $id_usuario = $_SESSION['id_usuario'];
    
    $nome  = mysqli_real_escape_string($conexao->getConexao(), $_POST['nome']);
    $email = mysqli_real_escape_string($conexao->getConexao(), $_POST['email']);
    $nova_senha = $_POST['senha'];

    // Começa a montar o SQL
    $sql = "UPDATE usuarios SET nome = '$nome', email = '$email'";

    // Se digitou senha, adiciona ao SQL (com hash)
    if (!empty($nova_senha)) {
        $senhaHash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql .= ", senha = '$senhaHash'";
    }

    // Lógica da Foto
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
        $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
        $permitidos = ['jpg', 'jpeg', 'png'];

        if (in_array(strtolower($extensao), $permitidos)) {
            $novoNomeFoto = "user_" . $id_usuario . "_" . time() . "." . $extensao;
            $destino = "../assets/images/users/" . $novoNomeFoto;

            // LIMPEZA: Antes de subir a nova, apaga a antiga (se não for a padrão)
            if (isset($_SESSION['foto_usuario']) && $_SESSION['foto_usuario'] != 'padrao.png') {
                $caminhoAntigo = "../assets/images/users/" . $_SESSION['foto_usuario'];
                if (file_exists($caminhoAntigo)) {
                    unlink($caminhoAntigo);
                }
            }

            // Faz o upload
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
                $sql .= ", foto_perfil = '$novoNomeFoto'";
                
                // Atualiza a sessão imediatamente para a foto aparecer na sidebar
                $_SESSION['foto_usuario'] = $novoNomeFoto;
            }
        }
    }

    // Finaliza o SQL
    $sql .= " WHERE id = $id_usuario";

    if ($conexao->executaSQL($sql)) {
        // Atualiza o nome na sessão também
        $_SESSION['nome_usuario'] = $nome;

        echo "<script>
                alert('Perfil atualizado com sucesso!');
                window.location.href = '../view/perfil.php';
              </script>";
    } else {
        echo "<script>
                alert('Erro ao atualizar. O e-mail pode já estar em uso.');
                window.location.href = '../view/perfil.php';
              </script>";
    }
} else {
    // Se tentar acessar direto sem ação
    header('Location: ../view/perfil.php');
}
?>