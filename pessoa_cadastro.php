<?php
require_once 'conexao.php';
require_once 'pessoa.php';

$mensagem = '';

// Só processa se for POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'] ?? '';
    $idade = $_POST['idade'] ?? '';

    $database = new BancoDeDados();
    $conexao = $database->obterConexao();

    if ($conexao) {
        $pessoa = new Pessoa($conexao, $nome, $idade);
        if ($pessoa->criar()) {
            // Após criar, redireciona para evitar duplicação
            header("Location: cadastro_pessoa.php?sucesso=1");
            exit;
        } else {
            $mensagem = "Erro ao cadastrar pessoa.";
        }
    } else {
        $mensagem = "Erro de conexão com o banco de dados.";
    }
}

// Mostrar mensagem de sucesso se existir ?sucesso=1 na URL
if (isset($_GET['sucesso']) && $_GET['sucesso'] == 1) {
    $mensagem = "Pessoa cadastrada com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Pessoa</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <h1>Cadastro de Pessoa</h1>
    <form method="POST">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" required><br><br>

        <label for="idade">Idade:</label>
        <input type="number" name="idade" min="0" required><br><br>

        <button type="submit">Cadastrar</button>
    </form>

    <?php if (!empty($mensagem)): ?>
        <p style="color: green;"><?php echo htmlspecialchars($mensagem); ?></p>
    <?php endif; ?>
</body>
</html>
