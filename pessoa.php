<link rel="stylesheet" href="estilo.css">
<?php
class Pessoa {
    private $conexao;
    private $tabela = "pessoas";
    private $id;
    private $nome;
    private $idade;

    public function __construct($conexao, $nome = null, $idade = null, $id = null) {
        $this->conexao = $conexao;
        $this->nome = $nome;
        $this->idade = $idade;
        $this->id = $id;
    }

    public function criar() {
        $sql = "INSERT INTO pessoas (nome, idade) VALUES (:nome, :idade)";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            ':nome' => $this->nome,
            ':idade' => $this->idade
        ]);
    }

    public function ler() {
        $sql = "SELECT * FROM pessoas";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function excluir() {
        if (!$this->id) {
            throw new Exception("ID não definido para exclusão.");
        }

        $query = "DELETE FROM " . $this->tabela . " WHERE id = :id";
        $stmt = $this->conexao->prepare($query);

        $idSanitizado = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $idSanitizado);

        return $stmt->execute();
    }
}
?>
