<?php
require '../conexao.php';

$id_obra = $_GET['id'];
session_start();
$idUsuario = $_SESSION['idUsuario'];

$querySelect = mysqli_query($connection, "SELECT * FROM `favoritos` WHERE usuario_id_usuario = $idUsuario and obra_id_obra=$id_obra");
if (mysqli_num_rows($querySelect) > 0) {
    while ($fetchSelect = mysqli_fetch_array($querySelect)) {
        $queryDelete = mysqli_query($connection, "DELETE FROM `favoritos` WHERE usuario_id_usuario=$idUsuario and obra_id_obra=$id_obra");
        if ($queryDelete) {
            echo "removido";
        } else {
            echo "naoremovido";
        }
        break;
    }
} else {
    $queryInsert = mysqli_query($connection, "INSERT INTO `favoritos`(`usuario_id_usuario`, `obra_id_obra`) VALUES ('$idUsuario','$id_obra')");
    if ($queryInsert) {
        echo "1";
    } else {
        echo "0";
    }
}
