<?php
session_start();
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idProj = $_SESSION["idProj"];
    $id_funcionario = $_SESSION["usuario_id"];
    $id = $_POST["id"];
    $resposta = trim($_POST["resposta_funcionario"]);
    $acao = $_POST["acao"];
    $tarefa = $_POST["nomeTarefa"];

    if ($acao === "aprovar") {
        $status = "com_feedback";
        $feedback = "Positivo";
    } elseif ($acao === "rejeitar") {
        $status = "com_feedback";
        $feedback = "Negativo";
    } else {
        die("Ação inválida.");
    }

    $sql = "UPDATE sugestoes 
            SET resposta_funcionario = :resposta, status = :status, feedback = :feedback 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $ok = $stmt->execute([
        ":resposta" => $resposta,
        ":status" => $status,
        ":feedback" => $feedback,
        ":id" => $id
    ]);
    if (!$ok) {
        print_r($stmt->errorInfo());
        exit();
    }
    
    $comando = $pdo->prepare("INSERT INTO notificacao (tarefaNot, conteudoNot, remetenteNot ,projeto_idProj) VALUES (?, ?, ?, ?)");
    $comando->execute([$tarefa, $resposta, $id_funcionario, $idProj]);

    $idNot = $pdo->lastInsertId();

    $comando = $pdo->prepare(
        "SELECT l.idLogin FROM login l 
        INNER JOIN cliente c 
            ON c.login_idLogin = l.idLogin 
        INNER JOIN cliente_has_projeto cp
            ON cp.cliente_idCli = c.idCli
        WHERE cp.projeto_idProj = ?");
    $comando->execute([$idProj]);
    $cliente = $comando->fetch();

    $comando = $pdo->prepare("INSERT INTO destinatario (notificacao_idNot, projeto_idProj, login_idLogin) VALUES (?, ?, ?)");
    $comando->execute([$idNot, $idProj, $cliente["idLogin"]]);

    header("Location: prjct_manager.php");
    exit();
}
?>