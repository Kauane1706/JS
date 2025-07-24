<link rel="stylesheet" href="estilo.css">
<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'conexao.php';
require_once 'produto.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $codigo = $_POST['codigo'];
    $quantidade = $_POST['quantidade'];
    $preco = $_POST['preco'];

    $database = new BancoDeDados();
    $conexao = $database->obterConexao();

    if ($conexao) {
        $produto = new Produto($conexao, $nome, $codigo, $quantidade, $preco);
        try {
            if ($produto->criar()) {
                $mensagem = "Produto cadastrado com sucesso!";
            } else {
                $mensagem = "Erro ao cadastrar produto.";
            }
        } catch (Exception $e) {
            $mensagem = "Erro: " . $e->getMessage();
        }
    } else {
        $mensagem = "Erro de conexão com o banco de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Cadastro de Produto</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required><br><br>

        <label for="codigo">Código:</label>
        <input type="text" id="codigo" name="codigo" required><br><br>

        <label for="quantidade">Quantidade:</label>
        <input type="number" id="quantidade" name="quantidade" required><br><br>

        <label for="preco">Preço:</label>
        <input type="number" id="preco" name="preco" step="0.01" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <p style="color: yellow; margin-top: 20px;"><?php echo $mensagem; ?></p>
    <?php endif; ?>
</body>
</html>
