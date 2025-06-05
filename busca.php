<?php
session_start();
include "conexao.php";

$busca = isset($_GET['termo']) ? $_GET['termo'] : '';

if ($_SESSION["tipo"] == "funcionario") {
    $sql = "SELECT p.nomeProj FROM projeto p 
            INNER JOIN funcionario_has_projeto fp
                ON fp.projeto_idProj = p.idProj
            WHERE fp.funcionario_idFunc = " . $_SESSION["usuario_id"];
} else {
    $sql = "SELECT p.nomeProj FROM projeto p 
            INNER JOIN cliente_has_projeto cp
                ON cp.projeto_idProj = p.idProj
            WHERE cp.cliente_idCli = " . $_SESSION["usuario_id"];
}

if ($busca != '') {
    $sql .= " AND nomeProj LIKE :nome";
    $buscaParam = "%" . $busca . "%";
}

$comando = $pdo->prepare($sql);
if (isset($buscaParam)) {
    $comando->bindParam(":nome", $buscaParam);
}
$comando->execute();
$resultado = $comando->fetchAll(PDO::FETCH_ASSOC);

// Retornar os resultados como JSON
header('Content-Type: application/json');
echo json_encode($resultado);