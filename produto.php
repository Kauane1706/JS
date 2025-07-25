<?php
class Produto {
    private $pdo;
    private $nome;
    private $codigo;
    private $quantidade;
    private $preco;

    public function __construct($pdo, $nome = null, $codigo = null, $quantidade = null, $preco = null) {
        $this->pdo = $pdo;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
    }

    public function criar() {
        $sql = "INSERT INTO produtos (nome, codigo, quantidade, preco) VALUES (:nome, :codigo, :quantidade, :preco)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':codigo', $this->codigo);
        $stmt->bindValue(':quantidade', $this->quantidade);
        $stmt->bindValue(':preco', $this->preco);
        return $stmt->execute();
    }

    public function ler() {
        $sql = "SELECT * FROM produtos ORDER BY nome";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizarPorCodigo($codigoOriginal) {
        $sql = "UPDATE produtos SET nome = :nome, codigo = :codigo, quantidade = :quantidade, preco = :preco WHERE codigo = :codigoOriginal";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':codigo', $this->codigo);
        $stmt->bindValue(':quantidade', $this->quantidade);
        $stmt->bindValue(':preco', $this->preco);
        $stmt->bindValue(':codigoOriginal', $codigoOriginal);
        return $stmt->execute();
    }

    public function excluirPorCodigo($codigo) {
        $sql = "DELETE FROM produtos WHERE codigo = :codigo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':codigo', $codigo);
        return $stmt->execute();
    }
}
?>
