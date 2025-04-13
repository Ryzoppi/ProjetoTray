<?php
require_once 'pdoconfig.php'; //    Conexão com o banco

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $email = $_POST['emailFunc'];
    $registro = $_POST['registroFunc'];
    $senha = $_POST['senhaFunc'];

    //  Criptografid da senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);


        $sql = "INSERT INTO login (emailLogin, registroLogin, senhaLogin) VALUES (:email, :registro, :senha)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':registro', $registro);
        $stmt->bindParam(':senha', $senha_hash);

        $stmt->execute();

        header("Location: login.html");
        exit;
        
}
?>