<link rel="stylesheet" href="estilo.css">
<?php
require_once 'conexao.php';
require_once 'pessoa.php';

$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $idade = $_POST['idade'] ?? '';

    $database = new BancoDeDados();
    $conexao = $database->obterConexao();

    if ($conexao) {
        $pessoa = new Pessoa($conexao, $id, $nome, $idade);
        if ($pessoa->criar()) {
            $mensagem = "Pessoa cadastrada com sucesso!";
        } else {
            $mensagem = "Erro ao cadastrar pessoa. Verifique se o ID já existe.";
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
    <title>Cadastro de Pessoa</title>
</head>
<body>
    <h1>Cadastro de Pessoa</h1>
    <form method="POST">
        <label for="id">ID:</label>
        <input type="number" name="id" required><br><br>

        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br><br>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" min="0" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <p style="color: green;"><?php echo $mensagem; ?></p>
    <?php endif; ?>
</body>
</html>
