<?php
require_once '../controller/clsConexao.php';

class clsExercicio
{
    private $id;
    private $nome;
    private $imagem;
    private $equipamento;

    public function setId($v) { $this->id = $v; }
    public function setNome($v) { $this->nome = $v; }
    public function setImagem($v) { $this->imagem = $v; }
    public function setEquipamento($v) { $this->equipamento = $v; }

    public function inserir()
    {
        $conexao = new clsConexao();
        // Removemos grupo_muscular e grupo_secundario do INSERT
        $sql = "INSERT INTO exercicios (nome, imagem, equipamento) 
                VALUES ('$this->nome', '$this->imagem', '$this->equipamento')";
        
        $conexao->executaSQL($sql);
        
        // Retorna o ID gerado para vincular os músculos
        $res = $conexao->executaSQL("SELECT LAST_INSERT_ID() as id");
        $linha = mysqli_fetch_assoc($res);
        return $linha['id']; 
    }

    public function editar()
    {
        $conexao = new clsConexao();
        // Removemos os grupos do UPDATE
        $sql = "UPDATE exercicios SET 
                nome='$this->nome', 
                equipamento='$this->equipamento' 
                WHERE id=$this->id";
        return $conexao->executaSQL($sql);
    }
    
    public function atualizarImagem($img) {
        $conexao = new clsConexao();
        $this->imagem = $img;
        $conexao->executaSQL("UPDATE exercicios SET imagem='$img' WHERE id=$this->id");
    }

    // Salva os músculos na tabela 'ativacao_muscular'
    // Método Salvar Músculos (Agora com inteligência para definir o Principal)
    public function salvarMusculos($idExercicio, $nomes, $valores) 
    {
        $conexao = new clsConexao();

        // 1. Limpa anteriores
        $conexao->executaSQL("DELETE FROM ativacao_muscular WHERE exercicio_id = $idExercicio");

        $maiorFator = -1;
        $musculoPrincipal = "";

        // 2. Insere novos e descobre o maior
        if (!empty($nomes) && is_array($nomes)) {
            for ($i = 0; $i < count($nomes); $i++) {
                
                $musculo = $nomes[$i];
                $fator   = isset($valores[$i]) ? (float)$valores[$i] : 0;

                if (!empty($musculo)) {
                    // Salva na tabela filha
                    $sql = "INSERT INTO ativacao_muscular (exercicio_id, musculo, fator) 
                            VALUES ($idExercicio, '$musculo', '$fator')";
                    $conexao->executaSQL($sql);

                    // Verifica se este é o novo "Campeão" de ativação
                    if ($fator > $maiorFator) {
                        $maiorFator = $fator;
                        $musculoPrincipal = $musculo;
                    }
                }
            }
        }

        // 3. Atualiza o "Crachá" na tabela de exercícios
        // Se achou algum músculo, salva ele como o principal.
        if (!empty($musculoPrincipal)) {
            $sqlUpdate = "UPDATE exercicios SET grupo_muscular = '$musculoPrincipal' WHERE id = $idExercicio";
            $conexao->executaSQL($sqlUpdate);
        }
    }

// Método Listar (Fica simples novamente, sem JOIN complexo)
    public function listar()
    {
        $conexao = new clsConexao();
        // A tabela exercicios volta a ter a coluna grupo_muscular
        $sql = "SELECT * FROM exercicios ORDER BY nome ASC";
        return $conexao->executaSQL($sql);
    }
    
    // Adicione este método na classe clsExercicio
    public function estaEmUso($id) {
        $conexao = new clsConexao();
        // Verifica na tabela de itens_treino se existe alguma referência a este ID
        $sql = "SELECT count(*) as total FROM itens_treino WHERE exercicio_id = $id";
        $resultado = $conexao->executaSQL($sql);
        $dados = mysqli_fetch_assoc($resultado);
        
        return $dados['total'] > 0; // Retorna TRUE se estiver em uso, FALSE se estiver livre
    }
    
    // E atualize o método excluir para retornar se deu certo ou não
    public function excluir($id) {
        $conexao = new clsConexao();
        
        // 1. Apaga as ativações musculares (filhos diretos, geralmente configurados como CASCADE, mas bom garantir)
        $conexao->executaSQL("DELETE FROM ativacao_muscular WHERE exercicio_id = $id");
        
        // 2. Tenta apagar o exercício
        $sql = "DELETE FROM exercicios WHERE id = $id";
        return $conexao->executaSQL($sql); // Retorna o objeto resultado (ou false se falhar)
    }
    
    // Método auxiliar para pegar o nome da imagem antes de excluir
    public function buscarImagem($id) {
        $conexao = new clsConexao();
        $sql = "SELECT imagem FROM exercicios WHERE id = $id";
        $res = $conexao->executaSQL($sql);
        $dados = mysqli_fetch_assoc($res);
        return $dados ? $dados['imagem'] : null;
    }

    public function buscarPorId($id) {
        $conexao = new clsConexao();
        $res = $conexao->executaSQL("SELECT * FROM exercicios WHERE id = $id");
        return mysqli_fetch_assoc($res);
    }
    public function listarAtivacao($idExercicio)
    {
        $conexao = new clsConexao();
        // (CORRIGIDO: exercicio_id)
        $sql = "SELECT * FROM ativacao_muscular WHERE exercicio_id = $idExercicio";
        $resultado = $conexao->executaSQL($sql);
        
        $lista = [];
        while($row = mysqli_fetch_assoc($resultado)){
            $lista[] = $row;
        }
        return $lista;
    }
}
?>