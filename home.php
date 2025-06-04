<?php
include "conexao.php";

$sql = "select * from projeto";    // String com o comando SQL a ser executado
$comando = $pdo->query($sql);       // Montamos e deixamos o comando SQL preparado
$resultado = $comando->fetchAll();  // Executamos o comando $sql, nesse caso, todo o conteudo da tabela projeto

$sql2 = "select * from notificacao";
$comando2 = $pdo->query($sql2);
$notificacoes = $comando2->fetchAll();

function limitarTexto($texto, $limite)
{
    // Verifica se o comprimento do texto é maior que o limite
    if (strlen($texto) > $limite) {
        // Trunca o texto e adiciona "..."
        return substr($texto, 0, $limite) . '...';
    }
    return $texto; // Retorna o texto original se não exceder o limite
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link rel="stylesheet" href="styles/home.css">
</head>

<body>
    <!-- Cabeçalho com logomarca da Tray -->
    <header id="cabecalho">
        <img id="logo" src="assets/logo.png" alt="logo da Tray">

        <!-- "Barra" onde fica o indicador de usuário e o sino de notificações -->
        <div id="barraUser">
            <div id="user">
                <img id="userImg" src="assets/user.png" alt="Imagem de usuário">
                <p>(Nome do usuário)</p>
            </div>
            <a id="sino" href=""><img id="sinoImg" src="assets/sino.png" alt="sino de notificações"></a>
        </div>
    </header>

    <main>
        <!-- Barra de criação de projeto -->
        <div id="containerCriaProjeto">
            <div id="criarProjeto">
                <h1>Criar Projeto</h1>
                <a id="criar">
                    <p>+</p>
                </a>
            </div>
        </div>

        <!-- Local onde ficam os projetos já existentes -->
        <div id="containerProjetos">
            <div id="projetos">
                <div id="cabecalhoProjetos">
                    <h1>Projetos</h1>
                    <div id="barraBusca">
                        <img src="assets/lupa.png" alt="lupa da barra de busca" id="lupa">
                        <input type="text" name="busca" id="busca" placeholder="Buscar">
                    </div>
                </div>

                <div id="listaProjetos">
                    <!-- Código em PHP que vai fazer as inclusões dos produtos -->
                    <?php foreach ($resultado as $projeto) { ?>
                        <div class="degradeFundo">
                            <div class="infoProjeto">
                                <a href="abreProjeto.php?id=<?= $projeto["idProj"] ?>">
                                    <h2><?= $projeto["nomeProj"] ?></h2>
                                </a>
                                <div class="barra">
                                    <div class="progressoBarra"></div>
                                </div>
                                <div class="maisInfoProjeto">
                                    <a class="maisLink" href="#">...</a> <!-- editar nome, descrição e prazo, excluir projeto ou marcar como concluído -->
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </main>

    <div id="modalCriarProjeto">
        <div id="criandoProjeto">
            <span id="fecharCriarProjeto">&times;</span>
            <h1>Criar Novo Projeto</h1>
            <div id="containerForm">
                <form action="" id="formCriaProjeto">
                    <div class="campoForm">
                        <label for="nome">Nome</label>
                        <input type="text" name="nome" required id="inputNomeProjeto">
                    </div>

                    <div class="campoForm">
                        <label for="descricao">Descrição</label>
                        <input type="text" name="descricao" required id="inputDescProjeto">
                    </div>

                    <div id="btn">
                        <button id="btnCriar">Criar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="modalNotificacoes">
        <div id="notificacoes">
            <!-- Código em PHP para puxar as notificações do banco -->
            <?php foreach ($notificacoes as $notificacao) { ?>
                <div class="notificacao">
                    <h3><?= $notificacao["tarefaNot"] ?></h3>
                    <p><?= limitarTexto($notificacao["conteudoNot"], 80) ?></p>
                </div>
            <?php } ?>
        </div>
    </div>

    <script type="text/javascript" src="js/home.js"></script>
</body>

</html>