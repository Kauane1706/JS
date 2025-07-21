<?php

class Produto {
    private $id;
    private $nome;
    private $preco;
    private $descricao;

    public function __construct($nome, $preco, $descricao) {
        $this->nome = $nome;
        $this->preco = $preco;
        $this->descricao = $descricao;
    }

    public function criar($conexao) {
        $sql = "INSERT INTO produtos (nome, preco, descricao) VALUES (?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sds", $this->nome, $this->preco, $this->descricao);
        return $stmt->execute();
    }

    public static function ler($conexao) {
        $sql = "SELECT * FROM produtos";
        $result = $conexao->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function editar($conexao, $id) {
        $sql = "UPDATE produtos SET nome = ?, preco = ?, descricao = ? WHERE id = ?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sdsi", $this->nome, $this->preco, $this->descricao, $id);
        return $stmt->execute();
    }
}
?>