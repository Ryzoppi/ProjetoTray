<?php
include 'conexao.php';

$idFunc = $_POST['idFunc'];
$sql = "SELECT login_idLogin FROM funcionario WHERE idFunc = ?";
$comando = $pdo->prepare($sql);
$comando->execute([$idFunc]);
$login_funcionario = $comando->fetch(PDO::FETCH_ASSOC);

$idLogin = $login_funcionario['login_idLogin'];

$sql = "DELETE FROM funcionario WHERE idFunc = ?";
$comando = $pdo->prepare($sql);
$comando->execute([$idFunc]);

$sql = "DELETE FROM login WHERE idLogin = ?";
$comando = $pdo->prepare($sql);
$comando->execute([$idLogin]);

header("Location: admPage.php");
exit();
