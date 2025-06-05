<?php
include "conexao.php";
session_start();

$nome = $_POST["nome"];
$descricao = $_POST["descricao"];

$sql = "INSERT INTO projeto (nomeProj, descProj) VALUES (?, ?)";
$comando = $pdo->prepare($sql);
$comando->execute([$nome, $descricao]);

$idProj = $pdo->lastInsertId();

$sql2 = "INSERT INTO funcionario_has_projeto (projeto_idProj, funcionario_idFunc) VALUES (?, ?)";
$comando = $pdo->prepare($sql2);
$comando->execute([$idProj, $_SESSION["usuario_id"]]);

header("Location: home.php");
exit();