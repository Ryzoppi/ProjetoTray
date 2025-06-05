<?php 
session_start();
include "conexao.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
}

$sql = "SELECT * FROM cliente ORDER BY idCli DESC";
$comando = $pdo->query($sql);
$clientes = $comando->fetchAll();

$sql = "SELECT * FROM funcionario ORDER BY idFunc DESC";
$comando = $pdo->query($sql);
$funcionarios = $comando->fetchAll();
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADM page</title>
    <link rel="stylesheet" href="styles/styleADM.css">
    <link rel="stylesheet" href="styles/listagemADM.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script>
        function mostrarAba(abaId) {
            document.querySelectorAll(".aba-conteudo").forEach(div => div.style.display = "none");
            document.getElementById(abaId).style.display = "block";
        }
        window.onload = function() {
            mostrarAba('home'); // Aba padrão
        }
        function filtrar(valor) {
            valor = valor.toLowerCase();
            const listas = document.querySelectorAll(".aba-conteudo ul li");
            listas.forEach(li => {
                const nome = li.querySelector(".nome")?.textContent.toLowerCase() || "";
                li.style.display = nome.includes(valor) ? "" : "none";
            });
        }
    </script>
</head>
<body>
    <img src="assets/logotray.png" class="logo" alt="Logo">
    <nav class="menu-lateral">
        <div class="btn-expandir">
            <i class="bi bi-list" id="bnt-exp"></i>
        </div>
        <script src="js/expandirMenuADM.js"></script>
        <ul>
            <li class="item-menu"><a href="#" onclick="mostrarAba('home')"><i class="bi bi-house"></i><span class="txt-link">Home</span></a></li>
            <li class="item-menu"><a href="#" onclick="mostrarAba('funcionarios')"><i class="bi bi-person-badge"></i><span class="txt-link">Funcionário</span></a></li>
            <li class="item-menu"><a href="#" onclick="mostrarAba('clientes')"><i class="bi bi-person-fill"></i><span class="txt-link">Cliente</span></a></li>
        </ul>
    </nav>
    <main class="main-conteudo">
        <div id="home" class="aba-conteudo"> <h2><strong>Bem Vindo à Área do Administrador</strong><br> <br>
        📋Aqui, você tem acesso às informações essenciais dos funcionários e clientes cadastrados.<br>
        De forma prática, é possível consultar e editar dados como: <br>
        Visualizar os dados individuais de cada funcionário; <br>
        Editar informações cadastrais como nome, email e senha; <br>
        Gerenciar os projetos vinculados a cada colaborador.</h2> </div>

        <div id="funcionarios" class="aba-conteudo" style="display:none;">
            <?php include 'list_funcionarios.php'; ?>
        </div>

        <div id="clientes" class="aba-conteudo" style="display:none;">
            <?php include 'list_clientes.php'; ?>
        </div>
    </main>
</body>
</html>
