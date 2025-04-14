<?php
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['emailCli'];
    $empresa = $_POST['empresa'];
    $senha = $_POST['senhaCli'];

        $sql = "INSERT INTO login (emailLoginCli, registroLoginCli, senhaLoginCli) VALUES (:email, :empresa, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':empresa', $empresa);
        $stmt->bindParam(':senha', $senha);

        $stmt->execute();
        
        try {
            $stmt->execute();
            echo "Cadastrado com sucesso!";
        } catch (PDOException $e) {
            echo "Erro no cadastro: " . $e->getMessage();
        }
        
        header("Location: login.html");
        exit;
        
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/cd_cliente.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <title>Cadastro Cliente</title>

</head>

<body>
 
    <div class="main">
        <input type="checkbox" id="chk" aria-hidden="true">
        <div class="Login">
            <form method="POST" action="login.php">
                <label for="chk" aria-hidden="true">Cadastrar Cliente</label>
                <input type="email" name="emailCli" placeholder="E-mail" required="">
                <input type="nome_empresa" name="empresa" placeholder="Empresa" required="">
                <input type="password" name="senhaCli" placeholder="Senha" required="">
                <button>Cadastrar</button>
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