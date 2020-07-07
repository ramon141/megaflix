<?php 
$connection = mysqli_connect('localhost', 'root', '','megaflix') or die('Não foi possível conectar');
$connection->set_charset("utf8");	




if(!isset($_COOKIE['hashIdUser']) && (!isset($_SESSION['login']) || $_SESSION['login'] != 1)){
	$value = md5(str_shuffle(str_shuffle(date('Y-h-m').time())));
	setcookie("hashIdUser", $value, time()+3600*24*30*12);
	
  $hash = $value;

  

  if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
  }
  mysqli_query($connection, "INSERT INTO `usuario`(`id_usuario`, `email`, `senha`, `nomeUsuario`, `hash`) VALUES (NULL, 'semEmail', '123', 'Megaflix', '$hash')");
  $_SESSION['idUsuario'] = $connection->insert_id;
  
} else if(!isset($_SESSION['login']) || $_SESSION['login'] != 1){
  $hash = $_COOKIE['hashIdUser'];
  $querySelect = mysqli_query($connection, "select * from usuario where hash='$hash';");
  if(mysqli_num_rows($querySelect) > 0){
    while($fetch = mysqli_fetch_array($querySelect)){
      $_SESSION['idUsuario'] = $fetch['id_usuario'];
    }
  }

}
//echo $_SESSION['idUsuario']."ramon";
