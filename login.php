<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sqlFunc = "SELECT * FROM funcionarios WHERE email = :email";
    $stmtFunc = $pdo->prepare($sqlFunc);
    $stmtFunc->bindParam(':email', $email);
    $stmtFunc->execute();

    $funcionario = $stmtFunc->fetch();

    if ($funcionario) {
        if ($senha === $funcionario['senha']) {
            $_SESSION['usuario_id'] = $funcionario['id'];
            $_SESSION['email'] = $funcionario['email'];
            $_SESSION['tipo'] = 'funcionario';
            header("Location: home.php");
            exit;
        }
    } else {
        $sqlCli = "SELECT * FROM clientes WHERE email = :email";
        $stmtCli = $pdo->prepare($sqlCli);
        $stmtCli->bindParam(':email', $email);
        $stmtCli->execute();

        $cliente = $stmtCli->fetch();

        if ($cliente && $senha === $cliente['senha']) {
            $_SESSION['usuario_id'] = $cliente['id'];
            $_SESSION['email'] = $cliente['email'];
            $_SESSION['tipo'] = 'cliente';
            header("Location: home.php");
            exit;
        }
    }

    echo "Login inválido!";
}
?>