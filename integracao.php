<!DOCTYPE html>
<html lang="pt-BR"> <!-- Define o tipo de documento como HTML5 e o idioma como português do Brasil. -->
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres da página como UTF-8, garantindo suporte a caracteres especiais. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Define a página como responsiva para dispositivos móveis. -->
    <title>Cadastro de Pessoa</title> <!-- Define o título da página. -->
    <link rel="stylesheet" href="estilo.css"> <!-- Inclui o arquivo de estilo CSS para estilização da página. -->
</head>
<body>
    <div class="container"> <!-- Define um contêiner principal para organizar o conteúdo da página. -->
        <header>
            <h1>Cadastro de Pessoa</h1> <!-- Título principal da página. -->
        </header>
        <section>
            <?php
            require_once 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados.
            require_once 'pessoa.php'; // Inclui o arquivo da classe Pessoa.

            $mensagem = ''; // Inicializa a variável para armazenar mensagens de erro ou sucesso.
            $cadastroSucesso = false; // Inicializa a variável para indicar se o cadastro foi bem-sucedido.

            $database = new BancoDeDados(); // Cria uma instância da classe BancoDeDados.
            $db = $database->obterConexao(); // Obtém a conexão com o banco de dados.

            if ($db === null) { // Verifica se a conexão com o banco de dados falhou.
                $mensagem = "Erro: Não foi possível conectar ao banco de dados"; // Define a mensagem de erro.
            } else {
                if ($_SERVER["REQUEST_METHOD"] == "POST") { // Verifica se o formulário foi enviado via método POST.
                    $pessoa = new Pessoa($db); // Cria uma instância da classe Pessoa, passando a conexão com o banco.
                    $pessoa->nome = $_POST['nome']; // Define o nome da pessoa com o valor enviado pelo formulário.
                    $pessoa->idade = $_POST['idade']; // Define a idade da pessoa com o valor enviado pelo formulário.

                    if ($pessoa->criar()) { // Chama o método criar() para inserir a pessoa no banco de dados.
                        $mensagem = "Pessoa '{$pessoa->nome}' cadastrada com sucesso!"; // Define a mensagem de sucesso.
                        $cadastroSucesso = true; // Define que o cadastro foi bem-sucedido.
                    } else {
                        $mensagem = "Erro ao cadastrar a pessoa."; // Define a mensagem de erro caso o cadastro falhe.
                    }
                }
            }
            ?>

            <!-- Formulário para cadastro de pessoa -->
            <form action="" method="post" id="formCadastroPessoa">
                <label for="nome">Nome:</label> <!-- Rótulo para o campo de nome. -->
                <input type="text" id="nome" name="nome" required> <!-- Campo de entrada para o nome, obrigatório. -->

                <label for="idade">Idade:</label> <!-- Rótulo para o campo de idade. -->
                <input type="number" id="idade" name="idade" required> <!-- Campo de entrada para a idade, obrigatório. -->

                <input type="submit" value="Cadastrar"> <!-- Botão para enviar o formulário. -->
            </form>
        </section>
    </div>

    <script>
        const mensagemDoPHP = "<?php echo $mensagem; ?>"; // Obtém a mensagem do PHP para exibição no JavaScript.
        const cadastroFoiSucesso = <?php echo json_encode($cadastroSucesso); ?>; // Converte a variável PHP para JSON.

        if (mensagemDoPHP) { // Verifica se há uma mensagem para exibir.
            alert(mensagemDoPHP); // Exibe a mensagem em um alerta.

            if (cadastroFoiSucesso) { // Se o cadastro foi bem-sucedido:
                document.getElementById('nome').value = ''; // Limpa o campo de nome.
                document.getElementById('idade').value = ''; // Limpa o campo de idade.
                document.getElementById('nome').focus(); // Foca no campo de nome.
            }
        }
    </script>
</body>
</html>
