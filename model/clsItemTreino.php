<?php
require_once '../controller/clsConexao.php';

class clsItemTreino
{
    private $id;
    private $treino_id;
    private $exercicio_id;
    private $series;
    private $repeticoes;
    private $carga_kg;
    private $observacao;
    private $descanso;
    
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function setId($v) { $this->id = $v; }
    public function getId()   { return $this->id; }
    public function setTreinoId($v) { $this->treino_id = $v; }
    public function setExercicioId($v) { $this->exercicio_id = $v; }
    public function setSeries($v) { $this->series = $v; }
    public function setRepeticoes($v) { $this->repeticoes = $v; }
    public function setCarga($v) { $this->carga_kg = $v; }
    public function setObservacao($v) { $this->observacao = $v; }
    public function setDescanso($v) { $this->descanso = $v; }

    public function inserir()
    {
        $treino_id    = (int)$this->treino_id;
        $exercicio_id = (int)$this->exercicio_id;
        
        $series = mysqli_real_escape_string($this->conexao->getConexao(), $this->series);
        $reps   = mysqli_real_escape_string($this->conexao->getConexao(), $this->repeticoes);
        $carga  = mysqli_real_escape_string($this->conexao->getConexao(), $this->carga_kg);
        $obs    = mysqli_real_escape_string($this->conexao->getConexao(), $this->observacao);
        $desc   = mysqli_real_escape_string($this->conexao->getConexao(), $this->descanso);

        $sql = "INSERT INTO itens_treino (treino_id, exercicio_id, series, repeticoes, carga_kg, observacao, descanso) 
                VALUES ($treino_id, $exercicio_id, '$series', '$reps', '$carga', '$obs', '$desc')";
        
        $this->conexao->executaSQL($sql);
        $this->id = $this->conexao->ultimoID();
        
        return $this->id;
    }

    public function listarDoTreino($id_treino)
    {
        $id_treino = (int)$id_treino;

        $sql = "SELECT it.*, ex.nome as nome_exercicio, ex.grupo_muscular, ex.imagem 
                FROM itens_treino it 
                INNER JOIN exercicios ex ON it.exercicio_id = ex.id 
                WHERE it.treino_id = $id_treino";
                
        return $this->conexao->executaSQL($sql);
    }

    public function excluir($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM itens_treino WHERE id = $id";
        return $this->conexao->executaSQL($sql);
    }
}
?>