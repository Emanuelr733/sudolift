<?php
require_once '../controller/clsConexao.php';

class clsCitacao
{
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function inserir($descricao, $autor)
    {
        // Protege contra caracteres especiais (aspas, etc.)
        $descricao = mysqli_real_escape_string($this->conexao->getConexao(), $descricao);
        $autor     = mysqli_real_escape_string($this->conexao->getConexao(), $autor);

        $sql = "INSERT INTO citacoes (descricao, autor) VALUES ('$descricao', '$autor')";
        return $this->conexao->executaSQL($sql);
    }

    public function atualizar($id, $descricao, $autor)
    {
        $id = (int)$id; 
        
        $descricao = mysqli_real_escape_string($this->conexao->getConexao(), $descricao);
        $autor     = mysqli_real_escape_string($this->conexao->getConexao(), $autor);

        $sql = "UPDATE citacoes SET descricao = '$descricao', autor = '$autor' WHERE id = $id";
        return $this->conexao->executaSQL($sql);
    }

    public function excluir($id)
    {
        $id = (int)$id;
        
        $sql = "DELETE FROM citacoes WHERE id = $id";
        return $this->conexao->executaSQL($sql);
    }

    public function listar()
    {
        $sql = "SELECT * FROM citacoes ORDER BY id DESC";
        return $this->conexao->executaSQL($sql);
    }

    public function buscarPorId($id)
    {
        $id = (int)$id;

        $sql = "SELECT * FROM citacoes WHERE id = $id";
        $res = $this->conexao->executaSQL($sql);
        return mysqli_fetch_assoc($res);
    }

    public function listarPaginado($inicio, $limite)
    {
        $sql = "SELECT * FROM citacoes ORDER BY id DESC LIMIT $inicio, $limite";
        return $this->conexao->executaSQL($sql);
    }

    public function contarTotal()
    {
        $sql = "SELECT COUNT(*) as total FROM citacoes";
        $res = $this->conexao->executaSQL($sql);
        $row = mysqli_fetch_assoc($res);
        return $row['total'];
    }
}
?>