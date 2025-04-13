<?php
require "conexao.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="estilos/cd_cliente.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <title>Cadastro Cliente</title>

</head>

<body>
 
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="Login">
            <form>
                <label for="chk" aria-hidden="true">Cadastrar Cliente</label>
                <input type="email" name="email_cliente" placeholder="E-mail" required="">
                <input type="nome_empresa" name="nome_empresa" placeholder="Nome da empresa" required="">
                <input type="password" name="pswd_cliente" placeholder="Senha" required="">
                <button>Login</button>
            </form>

        </div>

    </div>
    <!-- particles.js container -->
    <div id="particles-js"></div> <!-- stats - count particles -->
    <!-- particles.js lib - https://github.com/VincentGarreau/particles.js -->
    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script> <!-- stats.js lib -->
    <script src="http://threejs.org/examples/js/libs/stats.min.js"></script>
    <script type="text/javascript" src="../js/login.js"></script>
</body>

</html>