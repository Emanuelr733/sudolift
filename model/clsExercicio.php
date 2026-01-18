<?php
require_once '../controller/clsConexao.php';

class clsExercicio
{
    private $id;
    private $nome;
    private $imagem;
    private $equipamento;
    
    private $conexao;

    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function setId($v) { $this->id = $v; }
    public function setNome($v) { $this->nome = $v; }
    public function setImagem($v) { $this->imagem = $v; }
    public function setEquipamento($v) { $this->equipamento = $v; }

    public function inserir()
    {
        $nome = mysqli_real_escape_string($this->conexao->getConexao(), $this->nome);
        $img  = mysqli_real_escape_string($this->conexao->getConexao(), $this->imagem);
        $equip = mysqli_real_escape_string($this->conexao->getConexao(), $this->equipamento);

        $sql = "INSERT INTO exercicios (nome, imagem, equipamento) 
                VALUES ('$nome', '$img', '$equip')";
        
        $this->conexao->executaSQL($sql);
        
        return $this->conexao->ultimoID(); 
    }

    public function editar()
    {
        $id = (int)$this->id;
        $nome = mysqli_real_escape_string($this->conexao->getConexao(), $this->nome);
        $equip = mysqli_real_escape_string($this->conexao->getConexao(), $this->equipamento);

        $sql = "UPDATE exercicios SET 
                nome='$nome', 
                equipamento='$equip' 
                WHERE id=$id";
                
        return $this->conexao->executaSQL($sql);
    }

    public function atualizarImagem($img) {
        $id = (int)$this->id;
        $img = mysqli_real_escape_string($this->conexao->getConexao(), $img);
        
        $this->imagem = $img;
        $this->conexao->executaSQL("UPDATE exercicios SET imagem='$img' WHERE id=$id");
    }

    public function salvarMusculos($idExercicio, $nomes, $valores) 
    {
        $idExercicio = (int)$idExercicio;
        
        // 1. Limpa os anteriores para evitar duplicação
        $this->conexao->executaSQL("DELETE FROM ativacao_muscular WHERE exercicio_id = $idExercicio");
        
        $maiorFator = -1;
        $musculoPrincipal = "";

        if (!empty($nomes) && is_array($nomes)) {
            for ($i = 0; $i < count($nomes); $i++) {
                $musculo = mysqli_real_escape_string($this->conexao->getConexao(), $nomes[$i]);
                $fator   = isset($valores[$i]) ? (float)$valores[$i] : 0;

                if (!empty($musculo)) {
                    $sql = "INSERT INTO ativacao_muscular (exercicio_id, musculo, fator) 
                            VALUES ($idExercicio, '$musculo', '$fator')";
                    
                    $this->conexao->executaSQL($sql);

                    // Lógica para definir o músculo principal
                    if ($fator > $maiorFator) {
                        $maiorFator = $fator;
                        $musculoPrincipal = $musculo;
                    }
                }
            }
        }

        // Atualiza o músculo principal no cadastro do exercício
        if (!empty($musculoPrincipal)) {
            $musculoPrincipal = mysqli_real_escape_string($this->conexao->getConexao(), $musculoPrincipal);
            $sqlUpdate = "UPDATE exercicios SET grupo_muscular = '$musculoPrincipal' WHERE id = $idExercicio";
            $this->conexao->executaSQL($sqlUpdate);
        }
    }

    public function listar()
    {
        $sql = "SELECT * FROM exercicios ORDER BY nome ASC";
        return $this->conexao->executaSQL($sql);
    }

    public function estaEmUso($id) {
        $id = (int)$id;
        $sql = "SELECT count(*) as total FROM itens_treino WHERE exercicio_id = $id";
        $resultado = $this->conexao->executaSQL($sql);
        $dados = mysqli_fetch_assoc($resultado);
        
        return $dados['total'] > 0;
    }

    public function excluir($id) {
        $id = (int)$id;
        // Exclui primeiro os filhos (ativação muscular) para manter integridade
        $this->conexao->executaSQL("DELETE FROM ativacao_muscular WHERE exercicio_id = $id");
        
        $sql = "DELETE FROM exercicios WHERE id = $id";
        return $this->conexao->executaSQL($sql);
    }

    public function buscarImagem($id) {
        $id = (int)$id;
        $sql = "SELECT imagem FROM exercicios WHERE id = $id";
        $res = $this->conexao->executaSQL($sql);
        $dados = mysqli_fetch_assoc($res);
        return $dados ? $dados['imagem'] : null;
    }

    public function buscarPorId($id) {
        $id = (int)$id;
        $res = $this->conexao->executaSQL("SELECT * FROM exercicios WHERE id = $id");
        return mysqli_fetch_assoc($res);
    }

    public function listarAtivacao($idExercicio)
    {
        $idExercicio = (int)$idExercicio;
        $sql = "SELECT * FROM ativacao_muscular WHERE exercicio_id = $idExercicio";
        $resultado = $this->conexao->executaSQL($sql);
        
        $lista = [];
        while($row = mysqli_fetch_assoc($resultado)){
            $lista[] = $row;
        }
        return $lista;
    }
}
?>