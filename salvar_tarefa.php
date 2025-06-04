<?php
session_start();
include "conexao.php";

$idProj = $_SESSION["idProj"];
$idTarefa = $_POST['idTarefa'];
$nome = $_POST['nome'];
$desc = $_POST['desc'];
$idColuna = $_POST['coluna_idCol'];

if (empty($idTarefa)) {
    $sql = "INSERT INTO tarefa (nomeTarefa, descTarefa) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $desc]);
    $idTarefa = $pdo->lastInsertId();

    $sql = "INSERT INTO projeto_has_coluna_has_tarefa (projeto_idProj, coluna_idCol, tarefa_idTarefa, estado_tarefa) VALUES (?, ?, ?, 0)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_SESSION['idProj'], $idColuna, $idTarefa]);


    // Adicionando no histórico
    $sqlGetTask = "SELECT nomeTarefa FROM tarefa WHERE idTarefa = ?";
    $stmt = $pdo->prepare($sqlGetTask);
    $stmt->execute([$idTarefa]);
    $taskName = $stmt->fetchColumn();

    $sqlHist = "INSERT INTO historico_tarefas (projeto_id, acao, nome_tarefa)
                    VALUES (?, 'Adicionada', ?)";
    $stmtHist = $pdo->prepare($sqlHist);
    $stmtHist->execute([$idProj, $taskName]);
} else {
    $estado = $_POST['estado'];
    // Atualizar tarefa existente
    $sql = "UPDATE tarefa SET nomeTarefa = ?, descTarefa = ? WHERE idTarefa = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nome, $desc, $idTarefa]);

    $sql = "UPDATE projeto_has_coluna_has_tarefa SET estado_tarefa = ? WHERE tarefa_idTarefa = ? AND coluna_idCol = ? AND projeto_idProj = " . $_SESSION['idProj'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$estado, $idTarefa, $idColuna]);


    // Adicionando no histórico
    $sqlGetTask = "SELECT nomeTarefa FROM tarefa WHERE idTarefa = ?";
    $stmt = $pdo->prepare($sqlGetTask);
    $stmt->execute([$idTarefa]);
    $taskName = $stmt->fetchColumn();

    $sqlHist = "INSERT INTO historico_tarefas (projeto_id, acao, nome_tarefa)
                    VALUES (?, 'Modificada', ?)";
    $stmtHist = $pdo->prepare($sqlHist);
    $stmtHist->execute([$idProj, $taskName]);
}

header("Location: prjct_manager.php");
exit();
