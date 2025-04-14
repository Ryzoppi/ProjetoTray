<?php
    $tipo_banco = "mysql";
    $servidor   = "localhost";
    $porta      = 3306;
    $banco      = "ProjetoTray";
    $usuario    = "root";
    $senha      = "0805";

    $dsn        = "$tipo_banco:host=$servidor;dbname=$banco;port=$porta;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $usuario, $senha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro na conexão: " . $e->getMessage());
    }
?>
