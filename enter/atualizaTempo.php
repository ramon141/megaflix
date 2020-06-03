<?php 
require 'verificar.php';
require '../conexao.php';

$id_video = $_POST['idvideo'];
$tempo = $_POST['tempo'];
$usuario_id_usuario = $_POST['usuario_id_usuario'];


$sqlSelect = "SELECT * FROM `assistindo` WHERE usuario_id_usuario=$usuario_id_usuario and video_id_video=$id_video;";

$result = mysqli_query($connection, $sqlSelect);

//$sql = "select * from assistindo where temporada = $temporada and episodio = $episodio and usuario_id_usuario = $usuario_id_usuario and obra_id_obra = $id_obra";

//echo $sqlSelect;

if(mysqli_num_rows($result) > 0){

	while($fetchAssistindo = mysqli_fetch_array($result)){
		$idAssistindo = $fetchAssistindo['id_assisistindo'];
	}

	$query = mysqli_query($connection, "UPDATE assistindo SET tempo = $tempo WHERE id_assisistindo = $idAssistindo;");
	//$sql = $sql . "UPDATE assistindo SET tempo = $tempo WHERE id_assisistindo = $idAssistindo;";
} else {
	$query = mysqli_query($connection, "INSERT INTO `assistindo` (`id_assisistindo`, `tempo`, `usuario_id_usuario`, `video_id_video`) VALUES (NULL, '$tempo', '$usuario_id_usuario', '$id_video');");

	//$sql = $sql . "INSERT INTO `assistindo` (`id_assisistindo`, `temporada`, `episodio`, `tempo`, `usuario_id_usuario`, `obra_id_obra`) VALUES (NULL, $temporada, $episodio, '$tempo', '$usuario_id_usuario', '$id_obra');";
}

// if($query){
// 	echo $sql;
// } else {
// 	echo $sql;
// }


//print_r($_POST);


if($connection){
    mysqli_close($connection);
}
?>