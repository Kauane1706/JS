<?php
class Pessoa {
    private $conexao;
    private $tabela = "pessoas";
    private $id;
    private $nome;
    private $idade;

    // Construtor: para criar, informe nome e idade; id é opcional para update/delete
    public function __construct($conexao, $nome = null, $idade = null, $id = null) {
        $this->conexao = $conexao;
        $this->nome = $nome;
        $this->idade = $idade;
        $this->id = $id;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function setIdade($idade) {
        $this->idade = $idade;
    }

    // Verifica se a pessoa já existe no banco (mesmo nome e idade)
    public function existePessoa() {
        $sql = "SELECT COUNT(*) FROM {$this->tabela} WHERE nome = :nome AND idade = :idade";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([
            ':nome' => $this->nome,
            ':idade' => $this->idade
        ]);
        return $stmt->fetchColumn() > 0;
    }

    // Inserir nova pessoa (ID gerado automaticamente pelo banco)
    public function criar() {
        if ($this->existePessoa()) {
            // Pessoa já cadastrada, evita duplicata
            return false;
        }

        $sql = "INSERT INTO {$this->tabela} (nome, idade) VALUES (:nome, :idade)";
        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            ':nome' => $this->nome,
            ':idade' => $this->idade
        ]);
    }

    // Listar todas as pessoas
    public function ler() {
        $sql = "SELECT * FROM {$this->tabela} ORDER BY id ASC";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Atualizar idade pelo ID
    public function alterarIdade($novaIdade) {
        if (!$this->id) {
            throw new Exception("ID não definido para alteração.");
        }

        $sql = "UPDATE {$this->tabela} SET idade = :idade WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            ':idade' => $novaIdade,
            ':id' => $this->id
        ]);
    }

    // Excluir pessoa pelo ID
    public function excluir() {
        if (!$this->id) {
            throw new Exception("ID não definido para exclusão.");
        }

        $sql = "DELETE FROM {$this->tabela} WHERE id = :id";
        $stmt = $this->conexao->prepare($sql);

        $idLimpo = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $idLimpo);

        return $stmt->execute();
    }
}
?>
