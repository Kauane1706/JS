<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Pessoas e Produtos</title>
    <link rel="stylesheet" href="estilo.css">
    <style>
        .person, .product {
            margin-bottom: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }
        .alterar-form {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>Lista de Pessoas e Produtos Cadastrados</h1>
    </header>

    <section>
        <h2>Pessoas Cadastradas</h2>
        <?php
        require_once 'conexao.php';
        require_once 'pessoa.php';
        require_once 'produto.php';

        $database = new BancoDeDados();
        $db = $database->obterConexao();

        if ($db === null) {
            die("<p class='error'>Erro: Não foi possível conectar ao banco de dados.</p>");
        }

        // Se o formulário de alteração de idade foi enviado
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_id']) && isset($_POST['nova_idade'])) {
            $pessoa = new Pessoa($db);
            $pessoa->id = $_POST['alterar_id'];
            $novaIdade = $_POST['nova_idade'];

            if ($pessoa->alterarIdade($novaIdade)) {
                echo "<p style='color:green;'>Idade alterada com sucesso para o ID {$_POST['alterar_id']}!</p>";
            } else {
                echo "<p class='error'>Erro ao alterar a idade.</p>";
            }
        }

        // Listar pessoas
        $pessoa = new Pessoa($db);
        $dados = $pessoa->ler(); // retorna array de pessoas
        $num_linhas = count($dados);

        if ($num_linhas > 0) {
            foreach ($dados as $linha) {
                echo "<div class='person'>";
                echo "<p>ID: " . $linha['id'] . "</p>";
                echo "<p>Nome: " . $linha['nome'] . "</p>";
                echo "<p>Idade: " . $linha['idade'] . "</p>";
                echo "<form class='alterar-form' method='post' action=''>
                        <input type='hidden' name='alterar_id' value='" . $linha['id'] . "'>
                        <input type='number' name='nova_idade' min='0' placeholder='Nova idade' required>
                        <button type='submit'>Alterar Idade</button>
                      </form>";
                echo "</div>";
            }
        } else {
            echo "<p class='error'>Nenhum registro de pessoas encontrado.</p>";
        }
        ?>
    </section>

    <section>
        <h2>Produtos Cadastrados</h2>
        <?php
        // Listar produtos
        $produtoObj = new Produto($db); // cria o objeto passando a conexão
        $produtos = $produtoObj->ler(); // chama o método da instância

        if (!empty($produtos)) {
            foreach ($produtos as $produto) {
                echo "<div class='product'>";
                echo "<p>ID: " . $produto['id'] . "</p>";
                echo "<p>Nome: " . $produto['nome'] . "</p>";
                echo "<p>Código: " . $produto['codigo'] . "</p>";
                echo "<p>Quantidade: " . $produto['quantidade'] . "</p>";
                echo "<p>Preço: R$ " . number_format($produto['preco'], 2, ',', '.') . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p class='error'>Nenhum registro de produtos encontrado.</p>";
        }
        ?>
    </section>        