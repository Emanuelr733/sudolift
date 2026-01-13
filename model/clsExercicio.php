<?php
// Arquivo: model/clsExercicio.php
require_once '../controller/clsConexao.php';

class clsExercicio
{
    private $id;
    private $nome;
    private $grupo_muscular;
    private $tipo;
    private $imagem; // <--- NOVA VARIÁVEL

    // Getters e Setters
    public function setId($valor) { $this->id = $valor; }
    public function getId() { return $this->id; }

    public function setNome($valor) { $this->nome = $valor; }
    public function getNome() { return $this->nome; }

    public function setGrupoMuscular($valor) { $this->grupo_muscular = $valor; }
    public function getGrupoMuscular() { return $this->grupo_muscular; }

    public function setTipo($valor) { $this->tipo = $valor; }
    public function getTipo() { return $this->tipo; }
    
    // --- NOVO GET E SET PARA A IMAGEM ---
    public function setImagem($valor) { $this->imagem = $valor; }
    public function getImagem() { return $this->imagem; }

    // --- MÉTODOS CRUD ---

    public function inserir()
    {
        $conexao = new clsConexao();
        // ATUALIZADO: Agora salvamos também a coluna 'imagem'
        $sql = "INSERT INTO exercicios (nome, grupo_muscular, tipo, imagem) 
                VALUES ('$this->nome', '$this->grupo_muscular', '$this->tipo', '$this->imagem')";
        
        return $conexao->executaSQL($sql);
    }

    public function listar()
    {
        $conexao = new clsConexao();
        $sql = "SELECT * FROM exercicios ORDER BY nome ASC";
        return $conexao->executaSQL($sql);
    }

    public function excluir($id_para_deletar)
    {
        $conexao = new clsConexao();
        $sql = "DELETE FROM exercicios WHERE id = $id_para_deletar";
        return $conexao->executaSQL($sql);
    }
}
?>