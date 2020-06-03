<?php 
require '../conexao.php';

session_start();
$idUsuario = $_SESSION['idUsuario'];

$id_obra = $_GET['id'];

$queryAssistindo = mysqli_query($connection, "select temporada,episodio from assistindo inner join video on (assistindo.video_id_video = video.id_video ) inner join obra on (video.obra_id_obra = obra.id_obra) where assistindo.usuario_id_usuario = $idUsuario and obra_id_obra=$id_obra;");
if(mysqli_num_rows($queryAssistindo) == 0){
    $queryListaSerie = mysqli_query($connection, "SELECT * FROM `video` WHERE obra_id_obra = $id_obra order by temporada,episodio;");
    
    while($fetchListaSerie = mysqli_fetch_array($queryListaSerie)){
        echo $fetchListaSerie['id_video'];
        break;
	}
	
    die; 
}

while($fetchAssistindo = mysqli_fetch_array($queryAssistindo)){

	$temporada = $fetchAssistindo['temporada'];
	$episodio = $fetchAssistindo['episodio'];
	$sql = "select * from video where temporada='$temporada' and episodio='$episodio' and obra_id_obra='$id_obra'";
	$queryVideo = mysqli_query($connection, $sql);
	while($fetchVideo = mysqli_fetch_array($queryVideo)){
		echo $fetchVideo['id_video'];
	}
	break;
}

if($connection){
    mysqli_close($connection);
}

?>