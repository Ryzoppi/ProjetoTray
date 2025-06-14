<?php
session_start(); # Iniciando sessão para poder usar a $_SESSION

include "conexao.php"; # Incluindo conexão com o banco

# Variáveis
$idProj = $_SESSION["idProj"];
$id_cliente = $_SESSION["usuario_id"];
$tarefa = $_POST['tarefa'];
$mensagem = $_POST['mensagem'];
$categoria = $_POST['categoria'];

# Inserção da sugestão no banco
$comando = $pdo->prepare("INSERT INTO sugestoes (id_Cli, tarefa, categoria, mensagem) VALUES (?, ?, ?, ?)");
$comando->execute([$id_cliente, $tarefa, $categoria, $mensagem]);

# Criação da notificação da sugestão
$comando = $pdo->prepare("INSERT INTO notificacao (tarefaNot, conteudoNot, remetenteNot ,projeto_idProj) VALUES (?, ?, ?, ?)");
$comando->execute([$tarefa, $mensagem, $id_cliente, $idProj]);

$idNot = $pdo->lastInsertId(); # Obtendo o id da notificação que foi criada agora

# Obtendo o id do login dos funcionários que estão no projeto
$comando = $pdo->prepare(
    "SELECT l.idLogin FROM login l 
    INNER JOIN funcionario f 
        ON f.login_idLogin = l.idLogin 
    INNER JOIN funcionario_has_projeto fp
        ON fp.funcionario_idFunc = f.idFunc
    WHERE fp.projeto_idProj = ?");
$comando->execute([$idProj]);
$funcionarios = $comando->fetchAll();

# Preparação para a inserção dos destinatários da notificação
$insert = $pdo->prepare("INSERT INTO destinatario (notificacao_idNot, projeto_idProj, login_idLogin) VALUES (?, ?, ?)");

# Loop para executar o comando até todos os funcionários do projeto terem recebido
foreach ($funcionarios as $funcionario) {
    $insert->execute([$idNot, $idProj, $funcionario['idLogin']]);
}

# Redireciona de volta para a página de gerenciamento de projeto
header("Location: prjct_manager.php");
exit();
session_start();
$_SESSION['tipo'] = 'cliente';
print_r($_SESSION);
include "conexao.php"; 

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["enviar_sugestao"])) {
    $tarefa = $_POST["tarefa"];
    $mensagem = $_POST["mensagem"];
    $categoria = $_POST["categoria"];
    $idCliente = $_SESSION['usuario_id'] ?? null; 

    $sql = $sql = "INSERT INTO sugestoes (tarefa, mensagem, categoria, status, id_Cli) 
        VALUES (:tarefa, :mensagem, :categoria, 'em_analise', :idCli)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tarefa', $tarefa);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->bindParam(':categoria', $categoria);
    $stmt->bindParam(':idCli', $idCliente, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: prjct_manager.php"); 
        exit();
    } else {
        echo "Erro ao salvar sugestão.";
    }
} else {
    echo "Requisição inválida.";
}
