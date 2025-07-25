<?php
require_once 'conexao.php';
require_once 'pessoa.php';
require_once 'produto.php';

$database = new BancoDeDados();
$db = $database->obterConexao();

if ($db === null) {
    die("<p class='error'>Erro: Não foi possível conectar ao banco de dados.</p>");
}

// --- PESSOA: Alterar idade ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_idade'])) {
    $pessoa = new Pessoa($db);
    $pessoa->setId($_POST['id']);
    $novaIdade = $_POST['nova_idade'];

    if ($pessoa->alterarIdade($novaIdade)) {
        echo "<p style='color:green;'>Idade alterada com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao alterar idade.</p>";
    }
}

// --- PESSOA: Excluir pessoa ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_pessoa'])) {
    $pessoa = new Pessoa($db);
    $pessoa->setId($_POST['id']);

    if ($pessoa->excluir()) {
        echo "<p style='color:green;'>Pessoa excluída com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao excluir pessoa.</p>";
    }
}

// --- PRODUTO: Alterar dados ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_produto'])) {
    $produto = new Produto($db, $_POST['nome'], $_POST['codigo'], $_POST['quantidade'], $_POST['preco']);

    if ($produto->atualizarPorCodigo($_POST['codigo_original'])) {
        echo "<p style='color:green;'>Produto alterado com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao alterar produto.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
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
    <h1>Lista de Pessoas e Produtos Cadastrados</h1>

    <section>
        <h2>Pessoas Cadastradas</h2>
        <?php
        $pessoaObj = new Pessoa($db);
        $pessoas = $pessoaObj->ler();

        if (!empty($pessoas)) {
            foreach ($pessoas as $p) {
                echo "<div class='person'>";
                echo "<p>ID: {$p['id']}</p>";
                echo "<p>Nome: {$p['nome']}</p>";
                echo "<p>Idade: {$p['idade']}</p>";

                // Alterar idade
                echo "<form class='alterar-form' method='post'>
                        <input type='hidden' name='id' value='{$p['id']}'>
                        <input type='number' name='nova_idade' placeholder='Nova idade' required>
                        <button type='submit' name='alterar_idade'>Alterar Idade</button>
                      </form>";

                // Excluir pessoa
                echo "<form method='post' onsubmit='return confirm(\"Deseja excluir esta pessoa?\");'>
                        <input type='hidden' name='id' value='{$p['id']}'>
                        <button type='submit' name='excluir_pessoa'>Excluir</button>
                      </form>";

                echo "</div>";
            }
        } else {
            echo "<p class='error'>Nenhuma pessoa cadastrada.</p>";
        }
        ?>
    </section>

    

    <section>
    <h2>Produtos Cadastrados</h2>
    <?php
    // Alterar produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterar_codigo'])) {
    $produto = new Produto(
        $db,
        $_POST['novo_nome'],
        $_POST['novo_codigo'],
        $_POST['nova_quantidade'],
        $_POST['novo_preco']
    );

    if ($produto->atualizarPorCodigo($_POST['alterar_codigo'])) {
        echo "<p style='color:green;'>Produto atualizado com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao atualizar o produto.</p>";
    }
}

// Excluir produto
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_codigo'])) {
    $produto = new Produto($db);
    if ($produto->excluirPorCodigo($_POST['excluir_codigo'])) {
        echo "<p style='color:green;'>Produto excluído com sucesso!</p>";
    } else {
        echo "<p class='error'>Erro ao excluir o produto.</p>";
    }
}

    $produtoObj = new Produto($db);
    $produtos = $produtoObj->ler();

    if (!empty($produtos)) {
        foreach ($produtos as $produto) {
            echo "<div class='product'>";
            echo "<form class='alterar-form' method='post' action=''>";
            echo "<input type='hidden' name='alterar_codigo' value='" . htmlspecialchars($produto['codigo']) . "'>";

            echo "<label>Nome:</label><br>";
            echo "<input type='text' name='novo_nome' value='" . htmlspecialchars($produto['nome']) . "' required><br>";

            echo "<label>Código:</label><br>";
            echo "<input type='text' name='novo_codigo' value='" . htmlspecialchars($produto['codigo']) . "' required><br>";

            echo "<label>Quantidade:</label><br>";
            echo "<input type='number' name='nova_quantidade' value='" . htmlspecialchars($produto['quantidade']) . "' required><br>";

            echo "<label>Preço:</label><br>";
            echo "<input type='number' step='0.01' name='novo_preco' value='" . htmlspecialchars($produto['preco']) . "' required><br><br>";

            echo "<button type='submit'>Salvar Alterações</button>";
            echo "</form>";

            // Botão de exclusão
            echo "<form method='post' action='' style='margin-top:5px;'>";
            echo "<input type='hidden' name='excluir_codigo' value='" . htmlspecialchars($produto['codigo']) . "'>";
            echo "<button type='submit' style='background:red;color:white;'>Excluir</button>";
            echo "</form>";

            echo "</div>";
        }
    } else {
        echo "<p class='error'>Nenhum produto cadastrado.</p>";
    }
    ?>
</section>

</body>
</html>
