<?php
session_start();
include 'conexao.php';
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
        <form method="post" action="verifiUsuario.php">
          <label for="chk" aria-hidden="true">Cliente</label>
          <?php if (isset($_GET['erro']) && $_GET['erro'] == 1): ?>
              <div style="color: red; text-align: center; margin-bottom: 15px;">
                E-mail ou senha inválidos!
              </div>
          <?php endif; ?>
          <input type="email" name="email" placeholder="E-mail" required="" />
          <input type="password" name="senha" placeholder="Senha" required="" />
          <button>Login</button>
        </form>
      </div>
      <div class="Administrador">
        <form method="post" action="loginADM.php">
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