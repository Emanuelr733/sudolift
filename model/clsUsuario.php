<?php
// Arquivo: model/clsUsuario.php
require_once '../controller/clsConexao.php';

class clsUsuario
{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $perfil; 
    private $foto_perfil; // <--- NOVO

    // Getters e Setters
    public function setId($v) { $this->id = $v; }
    public function getId() { return $this->id; }

    public function setNome($v) { $this->nome = $v; }
    public function getNome() { return $this->nome; }

    public function setEmail($v) { $this->email = $v; }
    public function getEmail() { return $this->email; }

    public function setSenha($v) { $this->senha = $v; }
    public function getSenha() { return $this->senha; }

    public function setPerfil($v) { $this->perfil = $v; }
    public function getPerfil() { return $this->perfil; }
    
    // Novo Setter para Foto
    public function setFotoPerfil($v) { $this->foto_perfil = $v; }
    public function getFotoPerfil() { return $this->foto_perfil; }

    // --- MÉTODOS ---

    // 1. CADASTRAR NOVO USUÁRIO (Sign Up)
    public function cadastrar()
    {
        $conexao = new clsConexao();
        // Por segurança, todo cadastro externo entra como 'atleta'
        $sql = "INSERT INTO usuarios (nome, email, senha, perfil, foto_perfil) 
                VALUES ('$this->nome', '$this->email', '$this->senha', 'atleta', '$this->foto_perfil')";
        return $conexao->executaSQL($sql);
    }

    // 2. LOGAR (Atualizado para pegar a foto)
    public function logar()
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM usuarios WHERE email = '$this->email'";
        $resultado = $conexao->executaSQL($sql);

        if (mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);
            if (password_verify($this->senha, $dados['senha'])) {
                $this->id          = $dados['id'];
                $this->nome        = $dados['nome'];
                $this->perfil      = $dados['perfil'];
                $this->foto_perfil = $dados['foto_perfil']; // <--- Pega a foto do banco
                return true; 
            }
        }
        return false;
    }

    // 3. ALTERAR SENHA
    public function atualizarSenha($nova_senha_hash)
    {
        $conexao = new clsConexao();
        $sql = "UPDATE usuarios SET senha = '$nova_senha_hash' WHERE id = $this->id";
        return $conexao->executaSQL($sql);
    }
}
?>