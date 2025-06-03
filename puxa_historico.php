<?php
session_start();
include "conexao.php";

$sql = "SELECT 
            DATE_FORMAT(data_hora, '%d/%m/%Y %H:%i') as date,
            acao as action,
            nome_tarefa as task
        FROM historico_tarefas
        WHERE projeto_id = ?
        ORDER BY data_hora DESC
        LIMIT 50";

$stmt = $pdo->prepare($sql);
$stmt->execute([$_SESSION['idProj']]);
$history = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($history);
