<link rel="stylesheet" href="estilo.css">
<?php
class Produto {
    private $conexao;
    private $tabela = "produtos";
    private $nome;
    private $codigo;
    private $quantidade;
    private $preco;

    // Construtor com os atributos principais, sem id
    public function __construct($conexao, $nome = null, $codigo = null, $quantidade = null, $preco = null) {
        $this->conexao = $conexao;
        $this->nome = $nome;
        $this->codigo = $codigo;
        $this->quantidade = $quantidade;
        $this->preco = $preco;
    }

    // Verifica se o código já existe no banco
    public function codigoExiste() {
        $sql = "SELECT COUNT(*) FROM " . $this->tabela . " WHERE codigo = :codigo";
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute([':codigo' => $this->codigo]);
        return $stmt->fetchColumn() > 0;
    }

    // Inserir novo produto, evitando código duplicado
    public function criar() {
        if ($this->codigoExiste()) {
            throw new Exception("Código duplicado: este código já está cadastrado.");
        }

        $sql = "INSERT INTO " . $this->tabela . " (nome, codigo, quantidade, preco) VALUES (:nome, :codigo, :quantidade, :preco)";
        $stmt = $this->conexao->prepare($sql);
        return $stmt->execute([
            ':nome' => $this->nome,
            ':codigo' => $this->codigo,
            ':quantidade' => $this->quantidade,
            ':preco' => $this->preco
        ]);
    }

    // Ler todos os produtos
    public function ler() {
        $sql = "SELECT * FROM " . $this->tabela;
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Excluir produto pelo código (como não temos id)
    public function excluirPorCodigo($codigo) {
        $query = "DELETE FROM " . $this->tabela . " WHERE codigo = :codigo";
        $stmt = $this->conexao->prepare($query);

        $codigoSanitizado = htmlspecialchars(strip_tags($codigo));
        $stmt->bindParam(':codigo', $codigoSanitizado);

        return $stmt->execute();
    }

    // Atualizar produto pelo código
    public function atualizarPorCodigo($codigo) {
        $sql = "UPDATE " . $this->tabela . " SET nome = :nome, quantidade = :quantidade, preco = :preco WHERE codigo = :codigo";
        $stmt = $this->conexao->prepare($sql);

        return $stmt->execute([
            ':nome' => $this->nome,
            ':quantidade' => $this->quantidade,
            ':preco' => $this->preco,
            ':codigo' => $codigo
        ]);
    }
}
?>
