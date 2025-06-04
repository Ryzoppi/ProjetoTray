<?php
session_start();
require "conexao.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $resposta = trim($_POST['resposta_funcionario']);
    $acao = $_POST['acao'];

    if ($acao === 'aprovar') {
        $status = 'com_feedback';
        $feedback = 'Positivo';
    } elseif ($acao === 'rejeitar') {
        $status = 'com_feedback';
        $feedback = 'Negativo';
    } else {
        die("Ação inválida.");
    }

    $sql = "UPDATE sugestoes 
            SET resposta_funcionario = :resposta, status = :status, feedback = :feedback 
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $ok = $stmt->execute([
        ':resposta' => $resposta,
        ':status' => $status,
        ':feedback' => $feedback,
        ':id' => $id
    ]);
    if (!$ok) {
    print_r($stmt->errorInfo());
    exit;
} 

    header("Location: prjct_manager.php");
    exit();
}
?>