<?php
session_start();
include "conexao.php";

$dados = json_decode(file_get_contents('php://input'), true);
$idTarefa = $dados['idTarefa'];
$idColuna = $dados['idColuna'];

if ($idTarefa) {
    $sql = "DELETE FROM projeto_has_coluna_has_tarefa
            WHERE tarefa_idTarefa = ? 
            AND coluna_idCol = ? 
            AND projeto_idProj = " . $_SESSION['idProj'];

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$idTarefa, $idColuna]);

    $sqlCheckOtherProjects = "SELECT COUNT(*) FROM projeto_has_coluna_has_tarefa WHERE tarefa_idTarefa = ?";
    $stmt = $pdo->prepare($sqlCheckOtherProjects);
    $stmt->execute([$idTarefa]);
    $remainingRelations = $stmt->fetchColumn();

    if ($remainingRelations == 0) {
        $sqlGetTask = "SELECT nomeTarefa FROM tarefa WHERE idTarefa = ?";
        $stmt = $pdo->prepare($sqlGetTask);
        $stmt->execute([$idTarefa]);
        $taskName = $stmt->fetchColumn();

        $sqlHist = "INSERT INTO historico_tarefas (projeto_id, tarefa_id, acao, nome_tarefa) VALUES (?, ?, 'Removida', ?)";
        $stmtHist = $pdo->prepare($sqlHist);
        $stmtHist->execute([$_SESSION['idProj'], $idTarefa, $taskName]);
        
        $sqlDeleteTask = "DELETE FROM tarefa WHERE idTarefa = ?";
        $stmt = $pdo->prepare($sqlDeleteTask);
        $stmt->execute([$idTarefa]);
    }

    echo json_encode(['success' => true]);
}
