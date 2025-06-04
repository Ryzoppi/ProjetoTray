<?php
session_start();
include "conexao.php";

$inputJSON = file_get_contents('php://input');
$dados = json_decode($inputJSON, true);
$idTarefa = $dados['idTarefa'];
$idColuna = $dados['idColuna'];
$idProj = $_SESSION["idProj"];

$sql = "DELETE FROM projeto_has_coluna_has_tarefa
            WHERE tarefa_idTarefa = ? 
              AND coluna_idCol = ? 
              AND projeto_idProj = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idTarefa, $idColuna, $idProj]);

$sqlCheck = "SELECT COUNT(*) FROM projeto_has_coluna_has_tarefa WHERE tarefa_idTarefa = ?";
$stmt = $pdo->prepare($sqlCheck);
$stmt->execute([$idTarefa]);
$remainingRelations = $stmt->fetchColumn();

if ($remainingRelations == 0) {
    $sqlGetTask = "SELECT nomeTarefa FROM tarefa WHERE idTarefa = ?";
    $stmt = $pdo->prepare($sqlGetTask);
    $stmt->execute([$idTarefa]);
    $taskName = $stmt->fetchColumn();

    $sqlHist = "INSERT INTO historico_tarefas (projeto_id, acao, nome_tarefa)
                    VALUES (?, 'Removida', ?)";
    $stmtHist = $pdo->prepare($sqlHist);
    $stmtHist->execute([$idProj, $taskName]);

    $sqlDelete = "DELETE FROM tarefa WHERE idTarefa = ?";
    $stmt = $pdo->prepare($sqlDelete);
    $stmt->execute([$idTarefa]);
}

echo json_encode(['success' => true]);
exit();