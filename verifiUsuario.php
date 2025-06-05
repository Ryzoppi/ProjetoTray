<?php
session_start();
include "conexao.php";

$email = $_POST["email"];
$senha = $_POST["senha"];

$sql = "SELECT l.emailLogin, l.senhaLogin, f.nomeFunc, c.nomeCli, f.idFunc, c.idCli, t.nomeTipo 
            FROM login l 
            LEFT JOIN funcionario f ON l.idLogin = f.login_idLogin
            LEFT JOIN cliente c ON l.idLogin = c.login_idLogin
            INNER JOIN tipo t ON l.tipo_idTipo = t.idTipo 
            WHERE l.emailLogin = :email";

$comando = $pdo->prepare($sql);
$comando->bindParam(":email", $email);
$comando->execute();

$usuario = $comando->fetch();

if ($usuario && $senha === $usuario["senhaLogin"]) {
    $_SESSION["email"] = $usuario["emailLogin"];
    $_SESSION["tipo"] = $usuario["nomeTipo"];
    $_SESSION["nome"] = $usuario["nomeCli"] ?? $usuario["nomeFunc"];
    $_SESSION["usuario_id"] = $usuario["idCli"] ?? $usuario["idFunc"];

    header("Location: home.php");
    exit;
}

header("Location: login.php?erro=1");
exit;
