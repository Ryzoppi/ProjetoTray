<?php
include 'conexao.php';

$idCli = $_POST['idCli'];
$sql = "SELECT login_idLogin FROM cliente WHERE idCli = ?";
$comando = $pdo->prepare($sql);
$comando->execute([$idCli]);
$login_cliente = $comando->fetch(PDO::FETCH_ASSOC);

$idLogin = $login_cliente['login_idLogin'];

$sql = "DELETE FROM cliente WHERE idCli = ?";
$comando = $pdo->prepare($sql);
$comando->execute([$idCli]);

$sql = "DELETE FROM login WHERE idLogin = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idLogin]);

header("Location: admPage.php");
exit();
