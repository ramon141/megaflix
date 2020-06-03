<?php
require '../conexao.php';

if ($_POST && isset($_POST['m']) && strcmp($_POST['m'], "cadastrar") == 0) {

    $nomeUsuario = $_POST['nomeUsuario'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    mysqli_query($connection, "INSERT INTO `usuario`(`id_usuario`, `email`, `senha`, `nomeUsuario`) VALUES (NULL,'$email','" . md5($senha) . "','$nomeUsuario');");

    session_start();

    $_SESSION['login'] = 1;
    $_SESSION['nomeUsuario'] = $nomeUsuario;
    $_SESSION['idUsuario'] = $connection->insert_id;
    $_SESSION['senha'] = $senha;
    $_SESSION['email'] = $email;

    header("Location: /");

} else if ($_POST && isset($_POST['m']) && strcmp($_POST['m'], "login") == 0) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $queryLogin = mysqli_query($connection, "select * from usuario where email='$email' and senha='".md5($senha)."'");

    if (mysqli_num_rows($queryLogin)) {
        session_start();
        while ($fetchLogin = mysqli_fetch_array($queryLogin)) {
            $_SESSION['login'] = 1;
            $_SESSION['nomeUsuario'] = $fetchLogin['nomeUsuario'];
            $_SESSION['idUsuario'] = $fetchLogin['id_usuario'];
            $_SESSION['senha'] = $senha;
            $_SESSION['email'] = $email;
        }
        header("Location: /");

    } else {
        header('Location: /login/?erro=2');
    }
} else {
    header("Location: /");
}

if($connection){
    mysqli_close($connection);
}