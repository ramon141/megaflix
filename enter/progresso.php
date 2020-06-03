<?php



function countTime($idVideo){

    require '../conexao.php';

    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    $idUsuario = $_SESSION['idUsuario'];

    $queryTempo = mysqli_query($connection, "select tempoVideo,tempo from assistindo inner join video on (assistindo.video_id_video = video.id_video ) inner join obra on (video.obra_id_obra = obra.id_obra) where assistindo.usuario_id_usuario = $idUsuario and video.id_video=$idVideo;");

    $n1 = 1;
    $n2 = 0;

    while($fetchTempo = mysqli_fetch_array($queryTempo)){
        $n1 = $fetchTempo['tempoVideo'];
        $n2 = $fetchTempo['tempo'];
    }

    $re = ($n2*100)/$n1;

    if($connection){
        mysqli_close($connection);
    }
    return $re;
}

function whatTime($idVideo){

    require '../conexao.php';

    if (session_status() != PHP_SESSION_ACTIVE) {
        session_start();
    }
    $idUsuario = $_SESSION['idUsuario'];

    $queryTempo = mysqli_query($connection, "select tempo from assistindo inner join video on (assistindo.video_id_video = video.id_video ) inner join obra on (video.obra_id_obra = obra.id_obra) where assistindo.usuario_id_usuario = $idUsuario and video.id_video=$idVideo;");

    $n2 = 0;

    while($fetchTempo = mysqli_fetch_array($queryTempo)){
        $n2 = $fetchTempo['tempo'];
    }

    if($connection){
        mysqli_close($connection);
    }
    return $n2;
}




if($connection){
    mysqli_close($connection);
}

?>