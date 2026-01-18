<?php
class clsConexao
{
    private $host;
    private $usuario;
    private $senha;
    private $banco;

    private $conexao;

    function __construct()
    {
        $this->host    = 'srv1596.hstgr.io';
        $this->usuario = 'u459760425_sudolift';
        $this->senha   = 'Sudolift123';
        $this->banco   = 'u459760425_sudolift';

        $this->conecta();
    }

    private function conecta()
    {
        // Tenta conectar
        $this->conexao = mysqli_connect($this->host, $this->usuario, $this->senha, $this->banco);

        // Se der erro, para tudo e mostra o motivo
        if (!$this->conexao) {
            die("Erro fatal de conexão: " . mysqli_connect_error());
        }

        // Define charset para evitar erros de acentuação
        mysqli_set_charset($this->conexao, "utf8");
    }
    public function executaSQL($sql)
    {
        // Executa a query
        $resposta = mysqli_query($this->conexao, $sql);
        
        // Verifica se a query falhou (ex: erro de sintaxe SQL)
        if (!$resposta) {
             die("Erro na Query: " . mysqli_error($this->conexao) . " | SQL: " . $sql);
        }
        
        return $resposta;
    }
    // Usado para pegar o ID gerado na última inserção para a clsTreino
    public function ultimoID(){
        return mysqli_insert_id($this->conexao);
    }
    public function getConexao() {
        return $this->conexao;
    }
    // Fecha a conexão automaticamente ao final do script
    function __destruct() {
        if ($this->conexao) {
            mysqli_close($this->conexao);
        }
    }
}
?>