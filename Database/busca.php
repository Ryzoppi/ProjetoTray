<?php
include "conexao.php";

$busca = isset($_GET['termo']) ? $_GET['termo'] : '';

if ($busca != '') {
    $sql = "SELECT * FROM projetos WHERE nome LIKE ? ORDER BY dataCriacao";
    $buscaParam = "%" . $busca . "%";
} else {
    $sql = "SELECT * FROM projetos ORDER BY dataCriacao";
}

$comando = $pdo->prepare($sql);
if (isset($buscaParam)) {
    $comando->bind_param("s", $buscaParam);
}
$comando->execute();
$resultado = $comando->get_result();

$projetos = [];
while ($linha = $resultado->fetch_assoc()) {
    $projetos[] = $linha;
}
?>