<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['idColuna'])) {
    $idCol = intval($_POST['idColuna']);
    $idProj = $_SESSION['idProj'];

    try {
        $pdo->beginTransaction();

        // 1. Selecionar IDs das tarefas associadas a essa coluna e projeto
        $stmt = $pdo->prepare("SELECT tarefa_idTarefa FROM projeto_has_coluna_has_tarefa WHERE projeto_idProj = ? AND coluna_idCol = ?");
        $stmt->execute([$idProj, $idCol]);
        $tarefas = $stmt->fetchAll(PDO::FETCH_COLUMN);

        if ($tarefas) {
            // 2. Apagar relações com a coluna e projeto
            $stmt = $pdo->prepare("DELETE FROM projeto_has_coluna_has_tarefa WHERE projeto_idProj = ? AND coluna_idCol = ?");
            $stmt->execute([$idProj, $idCol]);

            // 3. Apagar as tarefas (se não forem usadas em outras colunas, se for o caso)
            $placeholders = implode(',', array_fill(0, count($tarefas), '?'));
            $stmt = $pdo->prepare("DELETE FROM tarefa WHERE idTarefa IN ($placeholders)");
            $stmt->execute($tarefas);
        } else {
            // Mesmo que não tenha tarefa, precisa apagar o relacionamento
            $stmt = $pdo->prepare("DELETE FROM projeto_has_coluna_has_tarefa WHERE projeto_idProj = ? AND coluna_idCol = ?");
            $stmt->execute([$idProj, $idCol]);
        }

        // 4. Apagar a coluna
        $stmt = $pdo->prepare("DELETE FROM coluna WHERE idCol = ?");
        $stmt->execute([$idCol]);

        $pdo->commit();
        echo "Fase e tarefas associadas apagadas com sucesso.";

    } catch (Exception $e) {
        $pdo->rollBack();
        http_response_code(500);
        echo "Erro ao apagar fase: " . $e->getMessage();
    }
} else {
    http_response_code(400);
    echo "Requisição inválida.";
}
