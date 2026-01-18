<?php
require_once '../controller/clsConexao.php';

class clsAdmin
{
    private $conexao;

    // 1. Conecta apenas uma vez ao instanciar a classe
    public function __construct()
    {
        $this->conexao = new clsConexao();
    }

    public function getEstatisticas()
    {
        // 2. Busca estatísticas gerais do sistema
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM usuarios) as total_usuarios,
                    (SELECT COUNT(*) FROM exercicios) as total_exercicios";
        
        $result = $this->conexao->executaSQL($sql);
        return mysqli_fetch_assoc($result);
    }

    public function listarUsuarios()
    {
        // 3. Busca lista de usuários cadastrados evitando a senha
        $sql = "SELECT id, nome, email, perfil, data_cadastro, foto_perfil
                FROM usuarios 
                ORDER BY id DESC";
                
        return $this->conexao->executaSQL($sql);
    }
}
?>