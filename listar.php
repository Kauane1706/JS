<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres da página -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Responsividade -->
    <title>Document</title> <!-- Título da página -->
    <style>
        .person { margin-bottom: 20px; border: 1px solid #ccc; padding: 10px; }
        .alterar-form { margin-top: 10px; }
    </style>
</head>
<body>
    <header>
        <h1>Lista de Pessoas Cadastradas</h1> <!-- Título principal da página -->
    </header>
    <section>
        <?php
        require_once 'conexao.php';
        require_once 'pessoa.php';

        $database = new BancoDeDados();
        $db = $database->obterConexao();

        if ($db === null) {
            die("<p class='error'>Erro: Não foi possível conectar ao banco de dados.</p>");
        }

        // Se o formulário de alteração foi enviado
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

        $pessoa = new Pessoa($db);
        $stmt = $pessoa->ler();
        $num_linhas = $stmt->rowCount();

        if ($num_linhas > 0) {
            while ($linha = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<div class='person'>";
                echo "<p>ID: " . $linha['id'] . "</p>";
                echo "<p>Nome: " . $linha['nome'] . "</p>";
                echo "<p>Idade: " . $linha['idade'] . "</p>";
                // Formulário para alterar idade
                echo "<form class='alterar-form' method='post' action=''>
                        <input type='hidden' name='alterar_id' value='" . $linha['id'] . "'>
                        <input type='number' name='nova_idade' min='0' placeholder='Nova idade' required>
                        <button type='submit'>Alterar Idade</button>
                      </form>";
                echo "</div>";
            }
        } else {
            echo "<p class='error'>Nenhum registro encontrado.</p>";
        }
        ?>
    </section>
</body>
</html>