<?php
session_start();
include 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    if ($email === 'administradormaster@gmail.com' && $senha === '6969') {
        $_SESSION['email'] = $email;
        $_SESSION['tipo'] = 'Administrador Master';
        $_SESSION['usuario_id'] = 'master';

        header("Location: admPage.php");
        exit;
    }

    $sql = "SELECT l.emailLogin, l.senhaLogin, f.idFunc, c.idCli, t.nomeTipo 
            FROM login l 
            LEFT JOIN funcionario f ON l.idLogin = f.login_idLogin
            LEFT JOIN cliente c ON l.idLogin = c.login_idLogin
            INNER JOIN tipo t ON l.tipo_idTipo = t.idTipo 
            WHERE l.emailLogin = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $usuario = $stmt->fetch();

    if ($usuario && $senha === $usuario['senhaLogin']) { 
        $_SESSION['email'] = $usuario['emailLogin'];
        $_SESSION['tipo'] = $usuario['nomeTipo'];
        $_SESSION['usuario_id'] = $usuario['idCli'] ?? $usuario['idFunc'];

        header("Location: home.php");
        exit;
    }

    echo "Login inválido!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="styles/Logincss.css" />
    <link
      href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap"
      rel="stylesheet"
    />
    <title>Login</title>
  </head>

  <body>
    <div class="main">
      <input type="checkbox" id="chk" aria-hidden="true" />
      <div class="Login">
        <form method="post" action="">
          <label for="chk" aria-hidden="true">Cliente</label>
          <input type="email" name="email" placeholder="E-mail" required="" />
          <input type="password" name="senha" placeholder="Senha" required="" />
          <button>Login</button>
        </form>
      </div>
      <div class="Administrador">
        <form method="post" action="">
          <label for="chk" aria-hidden="true">Administrador</label>
          <input type="email" name="email" placeholder="E-mail" required="" />
          <input type="password" name="senha" placeholder="Senha" required="" />
          <button>Login</button>
        </form>
      </div>
    </div>
    <!-- particles.js container -->
    <div id="particles-js"></div>
    <!-- stats - count particles -->
    <!-- particles.js lib - https://github.com/VincentGarreau/particles.js -->
    <script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <!-- stats.js lib -->
    <script src="http://threejs.org/examples/js/libs/stats.min.js"></script>
    <script type="text/javascript" src="js/login.js"></script>
  </body>
</html>