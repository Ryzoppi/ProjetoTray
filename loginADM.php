<?php
session_start();

$email = $_POST['email'];
$senha = $_POST['senha'];

if ($email === 'administradormaster@gmail.com' && $senha === '6969') {
    $_SESSION['email'] = $email;
    $_SESSION['tipo'] = 'Administrador Master';
    $_SESSION['usuario_id'] = 'master';

    header("Location: admPage.php");
    exit;
}