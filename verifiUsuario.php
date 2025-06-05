<?php
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

    header("Location: login.php?erro=1");
    exit;
}
?> 