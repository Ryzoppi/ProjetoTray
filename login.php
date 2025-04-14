<?php
include "conexao.php";

session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM login WHERE emailLoginFunc = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch();

/*
    if ($usuario == false){
        $sql = "SELECT * FROM login WHERE emailLoginFunc = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
    
        $usuario = $stmt->fetch();
    }
*/

    if ($senha === $usuario['senhaLoginFunc']) {
        $_SESSION['usuario_id'] = $usuario['idLoginCli'];
        $_SESSION['email'] = $usuario['emailLoginCli'];
        header("Location: home.php");
        exit;
    } else {
        echo "Login inválido!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/Logincss.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <title>Login</title>
</head>

<body>
  

    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="Login">
            <form method="POST" action="cadastro_cli.php">
                <label for="chk" aria-hidden="true">Cliente</label>
                <input type="email" name="email" placeholder="E-mail" required="">
                <input type="password" name="senha" placeholder="Senha" required="">
                <button>Login</button>
            </form>

        </div>
        <div class="Administrador">
        <form method="POST" action="cadastro_func.php">
                <label for="chk" aria-hidden="true">Administrador</label>
                <input type="email" name="email" placeholder="E-mail" required="">
                <input type="password" name="senha" placeholder="Senha" required="">
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