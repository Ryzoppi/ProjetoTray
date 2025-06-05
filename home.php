<?php
session_start();
include "conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
}

$sql = "SELECT p.nomeProj, p.idProj FROM projeto p "; // String com o comando SQL a ser executado

if ($_SESSION["tipo"] == "funcionario") {
    $sql .= "INNER JOIN funcionario_has_projeto fp 
                ON fp.projeto_idProj = p.idProj
            WHERE fp.funcionario_idFunc = " . $_SESSION["usuario_id"];

    $achaLogin = "SELECT l.idLogin FROM login l
                INNER JOIN funcionario f
                    ON f.login_idLogin = l.idLogin
                WHERE f.idFunc = " . $_SESSION["usuario_id"];
} else {
    $sql .= "INNER JOIN cliente_has_projeto cp 
                ON cp.projeto_idProj = p.idProj
            WHERE cp.cliente_idCli = " . $_SESSION["usuario_id"];

    $achaLogin = "SELECT l.idLogin FROM login l
                INNER JOIN cliente c
                    ON c.login_idLogin = l.idLogin
                WHERE c.idCli = " . $_SESSION["usuario_id"];
}

$comando = $pdo->query($sql);       // Montamos e deixamos o comando SQL preparado
$resultado = $comando->fetchAll();  // Executamos o comando $sql, nesse caso, todo o conteudo da tabela projeto

$comando = $pdo->query($achaLogin);
$idLogin = $comando->fetch();

$sql2 = "SELECT n.idNot, n.tarefaNot, n.conteudoNot FROM notificacao n 
        INNER JOIN destinatario d
            ON d.notificacao_idNot = n.idNot
        WHERE d.login_idLogin = " . $idLogin["idLogin"];
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
                <p><?= $_SESSION["nome"] ?></p>
            </div>
            <a id="sino" href=""><img id="sinoImg" src="assets/sino.png" alt="sino de notificações"></a>
        </div>
    </header>

    <main>
        <?php if ($_SESSION["tipo"] == "funcionario") { ?>
        <!-- Barra de criação de projeto -->
        <div id="containerCriaProjeto">
            <div id="criarProjeto">
                <h1>Criar Projeto</h1>
                <a id="criar">
                    <p>+</p>
                </a>
            </div>
        </div>
        <?php } ?>

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
                <form method="post" action="criar_projeto.php" id="formCriaProjeto">
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