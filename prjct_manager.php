<?php
session_start();
$_SESSION['tipo'] = 'funcionario';

if (!isset($_SESSION["idProj"])) {
    header("Location: login.php");
}

function info_proj(PDO $pdo)
{
    $sql = "SELECT nomeProj, descProj FROM projeto WHERE idProj = " . $_SESSION["idProj"];
    $comando = $pdo->query($sql);
    $projeto = $comando->fetch();

    return $projeto;
}

function puxa_colunas(PDO $pdo)
{
    $sql = "SELECT DISTINCT c.idCol, c.nomeCol
            FROM coluna c 
            INNER JOIN projeto_has_coluna_has_tarefa pct 
                ON c.idCol = pct.coluna_idCol 
            WHERE pct.projeto_idProj = " . $_SESSION["idProj"];
    $comando = $pdo->query($sql);
    $colunas = $comando->fetchAll();

    return $colunas;
}

function puxa_tarefas(PDO $pdo)
{
    $colunas = puxa_colunas($pdo);
    if (empty($colunas)) {
        return [];
    }

    $idsColunas = array_column($colunas, 'idCol');
    $placeholders = implode(',', array_fill(0, count($idsColunas), '?'));

    $sql = "SELECT t.idTarefa, t.nomeTarefa, t.descTarefa, pct.estado_tarefa, pct.coluna_idCol 
            FROM tarefa t 
            INNER JOIN projeto_has_coluna_has_tarefa pct 
                ON t.idTarefa = pct.tarefa_idTarefa 
            WHERE pct.coluna_idCol IN ($placeholders) AND pct.projeto_idProj = " . $_SESSION['idProj'];

    $comando = $pdo->prepare($sql);
    $comando->execute($idsColunas);
    return $comando->fetchAll();
}

function puxa_notificacoes(PDO $pdo)
{
    $sql = "SELECT * FROM notificacao";
    $comando = $pdo->query($sql);

    return $comando->fetchAll();
}

function puxa_sugestoes_em_analise(PDO $pdo)
{
    $sql = "SELECT tarefa, mensagem, categoria FROM sugestoes WHERE status = 'em_analise'";
    $comando = $pdo->query($sql);

    return $comando->fetchAll();
}

function puxa_sugestoes_com_feedback(PDO $pdo)
{
    $sql = "SELECT tarefa, mensagem, resposta_funcionario, feedback FROM sugestoes WHERE status = 'com_feedback'";
    $comando = $pdo->query($sql);

    return $comando->fetchAll();
}

function listar_projetos(PDO $pdo)
{
    $sql = "SELECT idProj, nomeProj FROM projeto";
    return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
}


include "conexao.php";

$projeto = info_proj($pdo);
$lista_colunas = puxa_colunas($pdo);
$lista_tarefas = puxa_tarefas($pdo);
$lista_notificacoes = puxa_notificacoes($pdo);
$lista_sugestoes_em_analise = puxa_sugestoes_em_analise(($pdo));
$lista_sugestoes_com_feedback = puxa_sugestoes_com_feedback(($pdo));

$colunas_json = json_encode($lista_colunas);
$tarefas_json = json_encode($lista_tarefas);

$projetos = listar_projetos($pdo);

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="styles/prjct_manager.css">
    <title>Gerenciador</title>
    <script>
        const colunasData = <?= $colunas_json ?>;
        const tarefasData = <?= $tarefas_json ?>;
    </script>
</head>

<body>
    <!--Cabeçalho com logo, usuário e sino de notificações-->
    <header id="cabecalho">
        <img id="logo" src="assets/logo.png" alt="logo da Tray">

        <h1><?= $projeto["nomeProj"] ?></h1>
        <!-- "Barra" onde fica o indicador de usuário e o sino de notificações -->
        <div id="icones">
            <img id="userImg" src="assets/user.png" alt="Imagem de usuário">
            <a id="sino" href="#"><img src="assets/sino.png" alt="sino de notificações"></a> <!-- Quando quer que o 'a' leve a lugar nenhum, deixa um # no href -->
        </div>
    </header>

    <main>
        <div class="main">
            <!-- Linha do tempo do projeto -->
            <div class="timeline">
                <h2>Desenvolvimento</h2>
                <button id="opnTimelineModal">Open Modal Teste</button>
                <dialog id="dialogTimelineModal">
                        <form method="post" action="adicionar_fase.php">
                            <label for="idProjeto">Projeto:</label>
                                <select id="idProjeto" name="idProjeto" required>
                                  <option value="">Selecione um projeto</option>
                                  <?php foreach ($projetos as $proj): ?>
                                    <option value="<?= htmlspecialchars($proj['idProj']) ?>">
                                      <?= htmlspecialchars($proj['nomeProj']) ?>
                                    </option>
                                  <?php endforeach; ?>
                                </select>
                                
                                <br><br>
                                
                                <label for="nomeFase">Nome da Fase:</label>
                                <input type="text" id="nomeFase" name="nomeFase" required>
                                
                                <br><br>
                                
                                <label for="nomeTarefa">Nome da Primeira Tarefa:</label>
                                <input type="text" id="nomeTarefa" name="nomeTarefa" required>
                                
                                <br><br>
                                
                                <label for="descTarefa">Descrição da Primeira Tarefa:</label>
                                <textarea id="descTarefa" name="descTarefa"></textarea>
                                
                                <br><br>
                                
                                <button type="submit">Adicionar Fase com Tarefa</button>
                        </form>
                    <button id="closeTimelineModal">Close</button>
                </dialog>
                <div class="etapas">
                    <?php foreach ($lista_colunas as $coluna) { ?>
                        <div class="fase" data-id="<?= $coluna['idCol'] ?>"><?= $coluna["nomeCol"] ?></div>
                    <?php } ?>
                </div>
            </div>

            <div id="detalhes_e_form">
                <div class="detalhes_fase" style="<?php echo ($_SESSION['tipo'] == 'cliente') ? 'width: 100%;' : ''; ?>">
                    <h2 id="nome-fase"></h2>

                    <div class="barra-progresso">
                        <div class="progresso"></div>
                    </div>

                    <p>Tarefas completas: <span id="completas"></span>/<span id="total"></span></p>
                    <p>Status: <span id="status"></span></p>
                    <p>Última atualização: <span id="atualizacao">Hoje</span></p> <!-- Fixo por enquanto -->
                </div>

                <?php if ($_SESSION['tipo'] == 'funcionario') { ?>
                    <div class="adicionar_tarefa">
                        <h2>Adicionar tarefa</h2>

                        <form action="salvar_tarefa.php" id="form_tarefa" method="POST">
                            <input type="hidden" name="idTarefa" id="idTarefa" value="">
                            <input type="hidden" name="coluna_idCol" id="coluna_idCol" value="">

                            <div class="form_group">
                                <input type="text" name="nome" id="nomeTarefa" placeholder="Nome" required>
                            </div>

                            <div class="form_group">
                                <input type="text" name="desc" id="descTarefa" placeholder="Descrição">
                            </div>

                            <div class="form_group">
                                <select name="estado" id="estadoTarefa" class="form-control">
                                    <option value="0">Não iniciada</option>
                                    <option value="1">Em andamento</option>
                                    <option value="2">Concluída</option>
                                </select>
                            </div>

                            <div id="btn_adiciona_tarefa">
                                <button type="submit" id="enviar_form">Adicionar</button>
                            </div>
                        </form>
                    </div>
                <?php } ?>
            </div>


            <div id="lista_tarefas">
                <h2>Tarefas</h2>

                <div id="tarefas"></div>
            </div>
        </div>

        <div class="sugestao-container">
            <h3>Sugestões do Cliente</h3>

            <form method="POST" class="sugestao-form" action="salvar_sugestao.php">
                <label>Tarefa Selecionada:</label><br>
                <input type="text" name="tarefa" id="tarefa_selecionada" required><br><br>

                <label>Mensagem:</label><br>
                <textarea name="mensagem" rows="4" cols="50" required></textarea><br><br>

                <label>Categoria:</label><br>
                <div class="radio-group">
                    <input type="radio" name="categoria" value="adicionar" required> Adicionar
                    <input type="radio" name="categoria" value="incrementar"> Incrementar
                    <input type="radio" name="categoria" value="remover"> Remover
                </div>

                <button type="submit" name="enviar_sugestao">Enviar</button>
            </form>

            <hr>

            <div class="sugestoes-lista">

                <div class="sugestoes-coluna">
                    <h4>Sugestões em Análise</h4>
                    <div id="lista_em_analise">
                        <?php foreach ($lista_sugestoes_em_analise as $sugestao) { ?>
                            <div id="sugestao">
                                <p>Tarefa: <?= $sugestao['tarefa'] ?></p>
                                <p>Categoria: <?= $sugestao['categoria'] ?></p>
                                <p>Mensagem: <?= $sugestao['mensagem'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

                <div class="sugestoes-coluna">
                    <h4>Sugestões com Feedback</h4>
                    <div id="lista_com_feedback">
                        <?php foreach ($lista_sugestoes_com_feedback as $sugestao) { ?>
                            <div id="sugestao">
                                <p>Tarefa: <?= $sugestao['tarefa'] ?></p>
                                <p>Mensagem: <?= $sugestao['mensagem'] ?></p>
                                <p>Resposta: <?= $sugestao['resposta_funcionario'] ?></p>
                                <p>Feedback: <?= $sugestao['feedback'] ?></p>
                            </div>
                        <?php } ?>
                    </div>
                </div>

            </div>
        </div>

    </main>

    <script src="js/prjct_manager.js"></script>

</body>

<<<<<<< HEAD

</html>
=======
</html>

