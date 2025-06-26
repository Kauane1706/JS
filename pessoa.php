<link rel="stylesheet" href="estilo.css">
<?php
class Pessoa { // Define a classe Pessoa, que representa uma entidade do banco de dados.
    private $conexao; // Armazena a conexão com o banco de dados.
    private $nome_tabela = "pessoas"; // Nome da tabela no banco de dados.

    public $id; // Propriedade pública para armazenar o ID da pessoa.
    public $nome; // Propriedade pública para armazenar o nome da pessoa.
    public $idade; // Propriedade pública para armazenar a idade da pessoa.

   
    // Construtor da classe, recebe a conexão com o banco de dados como parâmetro.
    public function __construct($db){
        $this->conexao = $db; // Inicializa a conexão com o banco de dados.
    }
     // Método para criar uma nova pessoa no banco de dados.
    public function criar(){
         // Define a query SQL para inserir uma nova pessoa.
        $query = "INSERT INTO " . $this->nome_tabela . " SET nome = :nome, idade = :idade";
        $stmt = $this->conexao->prepare($query); // Prepara a query para execução.

           // Sanitiza os dados para evitar ataques de injeção de SQL.
        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->idade = htmlspecialchars(strip_tags($this->idade));
         // Associa os valores às variáveis da query.
        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":idade", $this->idade);
         // Executa a query e retorna o resultado (true ou false).
        return $stmt->execute();
    }
     // Método para ler todas as pessoas do banco de dados.
    public function ler(){
          // Define a query SQL para selecionar todas as pessoas, ordenadas por nome.
        $query = "SELECT id, nome, idade FROM " . $this->nome_tabela . " ORDER BY nome ASC";
        $stmt = $this->conexao->prepare($query); 
        $stmt->execute();  // Executa a query.
         // Retorna o resultado da execução da query.
        return $stmt;
    }
    // Método para alterar a idade de uma pessoa específica.
    public function alterarIdade($novoIdade) {
        // Define a query SQL para atualizar a idade de uma pessoa com base no ID.
        $query = "UPDATE " . $this->nome_tabela . " SET idade = :idade WHERE id = :id";
        $stmt = $this->conexao->prepare($query);  // Prepara a query para execução.

        // Sanitiza os dados para evitar ataques de injeção de SQL.
        $this->id = htmlspecialchars(strip_tags($this->id));
        $novoIdade = htmlspecialchars(strip_tags($novoIdade));
        // Associa os valores às variáveis da query.
        $stmt->bindParam(":id", $this->id);
        $stmt->bindParam(":idade", $novoIdade);
        // Executa a query e retorna o resultado (true ou false).
        return $stmt->execute();
    }
}
