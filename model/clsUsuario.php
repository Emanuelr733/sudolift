<?php
require_once '../controller/clsConexao.php';

class clsUsuario
{
    private $id;
    private $nome;
    private $email;
    private $senha;
    private $perfil; 
    private $foto_perfil;
    
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    // Getters e Setters
    public function setId($v) { $this->id = $v; }
    public function getId() { return $this->id; }
    
    public function setNome($v) { $this->nome = $v; }
    public function getNome() { return $this->nome; }
    
    public function setEmail($v) { $this->email = $v; }
    public function getEmail() { return $this->email; }
    
    public function setSenha($v) { $this->senha = $v; }
    
    public function setPerfil($v) { $this->perfil = $v; }
    public function getPerfil() { return $this->perfil; }
    
    public function setFotoPerfil($v) { $this->foto_perfil = $v; }
    public function getFotoPerfil() { return $this->foto_perfil; }

    public function cadastrar()
    {
        $nome   = mysqli_real_escape_string($this->conexao->getConexao(), $this->nome);
        $email  = mysqli_real_escape_string($this->conexao->getConexao(), $this->email);
        $perfil = mysqli_real_escape_string($this->conexao->getConexao(), $this->perfil);
        $foto   = mysqli_real_escape_string($this->conexao->getConexao(), $this->foto_perfil);

        // 2. CRÍTICO: Criptografar a senha antes de salvar
        // Se salvar sem hash, o password_verify do login NUNCA vai funcionar.
        $senhaHash = password_hash($this->senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha, perfil, foto_perfil) 
                VALUES ('$nome', '$email', '$senhaHash', '$perfil', '$foto')";
        
        $this->conexao->executaSQL($sql);
        
        return $this->conexao->ultimoID();
    }

    public function logar()
    {
        $email = mysqli_real_escape_string($this->conexao->getConexao(), $this->email);

        $sql = "SELECT * FROM usuarios WHERE email = '$email'";
        $resultado = $this->conexao->executaSQL($sql);

        if (mysqli_num_rows($resultado) > 0) {
            $dados = mysqli_fetch_assoc($resultado);
            
            // Verifica se a senha digitada ($this->senha) bate com o hash do banco
            if (password_verify($this->senha, $dados['senha'])) {
                
                // Preenche o objeto com os dados do banco
                $this->id          = $dados['id'];
                $this->nome        = $dados['nome'];
                $this->perfil      = $dados['perfil'];
                $this->foto_perfil = $dados['foto_perfil'];
                
                return true; 
            }
        }
        return false;
    }

    public function verificarSenhaAtual($senha_plana)
    {
        $id = (int)$this->id;
        $sql = "SELECT senha FROM usuarios WHERE id = $id";
        $resultado = $this->conexao->executaSQL($sql);
        
        if ($dados = mysqli_fetch_assoc($resultado)) {
            // Compara a senha digitada com o hash do banco
            return password_verify($senha_plana, $dados['senha']);
        }
        return false;
    }

    public function atualizarSenha($nova_senha_hash)
    {
        $id = (int)$this->id;
        
        $sql = "UPDATE usuarios SET senha = '$nova_senha_hash' WHERE id = $id";
        return $this->conexao->executaSQL($sql);
    }
}
?>