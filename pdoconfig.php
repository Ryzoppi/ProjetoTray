<?php

$tipo_banco = "mysql";
$servidor   = "localhost";
$porta      = 3306;
$banco      = "ProjetoTray";
$usuario    = "root";
$senha      = "";

$dsn = "$tipo_banco:host=$servidor;dbname=$banco;port=$porta;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de conexão: " . $e->getMessage());
}
