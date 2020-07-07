<?php
require 'verificar.php';
if (!($_GET && isset($_GET['id']))) {
    header("Location: /");
    die;
}

require '../conexao.php';
require 'progresso.php';

$isMobile = false;
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
    $isMobile = true;
}

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];


$idObra = $_GET['id'];
if (isset($_GET['temp'])) {
    $tempGet = $_GET['temp'];
} else {
    $tempGet = 1;
}

$linkTemp = "serie.php?id=" . $idObra . "&temp=";

$queryTemporadas = mysqli_query($connection, "select DISTINCT temporada from video where obra_id_obra = $idObra order by temporada");

$queryFilme = mysqli_query($connection, "select * from obra where id_obra = $idObra");

if (mysqli_num_rows($queryFilme) == 0) {
    header("Location: /");
    die;
}

$querySelectGeneros = mysqli_query($connection, "select genero.* from genero_has_obra inner join genero on (genero_has_obra.genero_id_genero = genero.id_genero) where genero_has_obra.obra_id_obra = $idObra;");

$tempoVideo = 0;
while ($fetchSerie = mysqli_fetch_array($queryFilme)) {
    $nomeSerie = $fetchSerie['titulo'];
    $posterSerie = $fetchSerie['poster'];
    $notaSerie = $fetchSerie['nota_imdb'];
    $sinopseSerie = $fetchSerie['sinopse'];
    $tempoVideo = $fetchSerie['tempoVideo'];
}

$tempoVideo = $tempoVideo / 60;
$tempoVideo = (int) $tempoVideo;
$queryListaSerie = mysqli_query($connection, "SELECT * FROM `video` WHERE obra_id_obra = $idObra and temporada=$tempGet order by temporada,episodio;");


function verificar($id_obra)
{
    require '../conexao.php';
    $idUsuario = $_SESSION['idUsuario'];
    $queryInsert = mysqli_query($connection, "SELECT `usuario_id_usuario`, `obra_id_obra` FROM `favoritos` WHERE usuario_id_usuario = '$idUsuario' and obra_id_obra='$id_obra'");
    if (mysqli_num_rows($queryInsert) > 0) {
        if ($connection) {
            mysqli_close($connection);
        }
        return true;
    } else {
        if ($connection) {
            mysqli_close($connection);
        }
        return false;
    }
    
}

?>
<!DOCTYPE html>
<html lang="pt" id="html">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <base target="_self">

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">

    <link rel="stylesheet" href="../stilo.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.0-beta/css/bootstrap-select.min.css">

    <title>MegaFlix </title>
    
</head>

<body style="background-image: url('/login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold; padding-left: 3%;" id="body">

    <button class="btn btn-danger" hidden="" onclick="closeIframe();" id="btnClose" style="position: absolute;right: 0px; z-index: 1001 !important; top: 30px; height:33px"><i class="fas fa-times"></i></button>

    <div class="modal" id="watch" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <iframe id="FrameIdVideo" height="400px" width="100%" frameborder="0"></iframe>
                </div>
            </div>
        </div>
    </div>


    <iframe style="position: absolute; top: 0px; left:0px;z-index: 1000 !important;" frameBorder="0" width="100%" height="100%" id="frameSrc" hidden=""></iframe>


    <?php require '../header.php'; ?>

    <div style="background-image: url(<?php echo $posterSerie; ?>); background-repeat: no-repeat; background-position: right; color: white; background-size: auto 100%;">
        <div style="background-image: url(../fundoFilme.png);background-size: 100%;  border-radius: 10px;">
            <div id="divSinopse" style="width: 40%;padding-left: 2%; padding-top: 2%; padding-right: 15px; text-align: justify;">
                <h1><button title="Assistir" onclick="continuarAss();" data-toggle="modal" data-target="#watch" class=" btn-floating btn-primary" style="border-radius: 16px;border-width: 0px;cursor: pointer;"><i class="fas fa-play-circle"></i></button>&nbsp; <?php echo $nomeSerie; ?></h1>
                
                <style type="text/css">
                    .tdClass{
                        border: 1px solid;
                        border-color: white;
                        background-color: rgba(0,0,0,0);
                        padding-left: 20px;
                        padding-right: 20px;
                        border-radius: 10px;
                        cursor: pointer;
                        font-size: 12px  
                    }
                    .tdClass:hover{
                        background-color: rgba(255,255,255,0.3);
                        transition: all 0.4s;
                    }

                </style>

                <?php 
                if(mysqli_num_rows($querySelectGeneros) > 0){
                    echo '
                        <div>
                            <table style="border-collapse: separate; border-spacing: 10px;">
                                <tbody>
                                    <tr>
                    ';
                    while($fetchGeneros = mysqli_fetch_array($querySelectGeneros)){
                        echo '<td class="tdClass" onclick="window.location.href=\'generos.php?id='.$fetchGeneros['id_genero'].'\';">'.$fetchGeneros['nome'].'</td>';
                    }
                    echo '
                                    </tr>
                              </tbody>
                          </table>
                        </div>
                    ';
                }

                ?>
                          



              <span style="font-size: 13px;"><?php echo $sinopseSerie; ?></span><br>
              <br>
              <div class="container">
                <div class="row">
                    <div class="col-6" style="padding-left: 0px;">
                        <div style="color: blue;">

                            <style>
                                .selectr {
                                    border-color: rgb(220, 53, 69);
                                    width: 100%;
                                    color: white;
                                    height: 40px;
                                    border-radius: 10px;
                                    padding-left: 10px;
                                    border-width: 3px;
                                    cursor: pointer;
                                    background-color: transparent;
                                }

                                .selectr option {
                                    color: rgb(220, 53, 69);
                                }
                            </style>


                            <?php

                            if (mysqli_num_rows($queryTemporadas) > 0) {
                                echo '<select class="selectr" onchange="location = this.value;">';
                                while ($fetchTemporadas = mysqli_fetch_array($queryTemporadas)) {
                                    echo '<option value="' . $linkTemp . $fetchTemporadas['temporada'] . '"';
                                    if ($tempGet == $fetchTemporadas['temporada']) {
                                        echo ' style="background-color: blue;" selected="selected"';
                                    }
                                    echo '>Temporada ' . $fetchTemporadas['temporada'] . '</option>';
                                }
                                echo '</select>';
                            }

                            ?>





                        </div>
                    </div>

                    <!-- informacoes     -->
                    <span style="font-size: 12px;">
                        2020<br>
                        IMDB: 10/10&nbsp;<i class="fas fa-star"></i>
                    &nbsp;&nbsp;</span>

                    <?php
                    if (verificar($idObra)) {
                        echo '<button onclick="addFavoritos();" id="heart" title="Remover dos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 40px;"><i class="fas fa-heart-broken"></i></button>';
                    } else {
                        echo '<button onclick="addFavoritos();" id="heart" title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 40px;"><i class="fas fa-heart"></i></button>';
                    }
                    ?>



                </div>
            </div>
            <br>
            <br>
            <!-- <a href=""><img src="../play.png" alt="" width="80px" height="80px"><span>&nbsp;&nbsp;&nbsp;&nbsp;Assistir</span></a> -->
        </div>
        <div style="background-image: linear-gradient(rgba(0,0,0,0), #273747);">&nbsp;</div>
    </div>

</div>
<br>
<h1 style="color: white;">Episódios(<?php echo mysqli_num_rows($queryListaSerie); ?>)</h1>
<!-- Comeco da listagem de episodios -->
<style>
    .episodio {
        padding: 1%;
        color: white;
        height: 13.17%;
        width: 21.96%;
        border-radius: 10px;
    }

    .imagem {
        padding: 10px;
        width: 25%;

    }

    .imagem img {
        width: 100%;
    }

    .sinopse {
        color: white;
        padding-right: 30px;
        width: 60%;
        text-align: justify;
    }
</style>

<div style="padding-right: 3%;color:white;">
    <div style="border-radius: 10px;background-color:rgb(39, 55, 71);">
        <?php
        while ($fetchListaSerie = mysqli_fetch_array($queryListaSerie)) {
            echo '
            <table>
            <tr>
            <td class="imagem" id="imagem" rowspan="3">
            <img style="border-radius: 10px;" src="' . $fetchListaSerie['poster'] . '" alt="">
            </td>
            <td class="sinopse" rowspan="3" style="padding: 10px;">

            <button onclick="openIframe('.$fetchListaSerie['id_video'].');" title="Assistir" class=" btn-floating btn-primary" style="border-radius: 16px;border-width: 0px;cursor: pointer;"><i class="fas fa-play-circle"></i>&nbsp;&nbsp;'.$fetchListaSerie['episodio'].' - '.$fetchListaSerie['nomeEpisodio'] . '</button>

            <br><br>';

            if (countTime($fetchListaSerie['id_video']) != 0) {

                echo '
                <div class="progress" style="width: 100%;height: 7px;">
                <div class="progress-bar" role="progressbar" style="width: ';
                echo countTime($fetchListaSerie['id_video']) . '%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                </div>';
            }
            echo '
            <span style="font-size: 13px;">
            ';

            if (empty($fetchListaSerie['sinopse'])) {
                echo "Este episódio ainda não possui sinopse";
            } else {
                echo $fetchListaSerie['sinopse'];
            }

            echo '
            </span>

            </td>

            ';
            if (!$isMobile) {
                echo '<td style="color: rgb(0, 112, 225); font-size: 13px;padding-top: 40px;">' . $fetchListaSerie['data'] . '</td>';
            }

            echo '
            </tr>
            ';

            if (!$isMobile) {
                echo '<tr>
                <td style="color: rgb(0, 112, 225); font-size: 13px;">' . $tempoVideo . 'min</td>
                </tr>
                <tr>
                <td style="color: rgb(0, 112, 225); font-size: 13px;padding-bottom: 40px;">Classificação do conteúdo</td>
                </tr>';
            }

            echo '</table>';
            if ($isMobile) {
                echo '<hr style="border: 1px solid red">';
            }
            echo '<br>';
        }

        ?>

        <!-- Episodio comeco -->
            <!-- <table>
                <tr>
                    <td class='imagem' id="imagem" rowspan="3">
                        <img style="border-radius: 10px;" src="https://image.tmdb.org/t/p/w533_and_h300_bestv2/4oE4vT4q0AD2cX3wcMBVzCsME8G.jpg" alt="">
                    </td>
                    <td class="sinopse" rowspan="3" style="padding: 10px;">
                        <a href="">
                            <button title="Adicionar a lista de favoritos" class=" btn-floating btn-primary" style="border-radius: 16px;border-width: 0px;cursor: pointer;"><i class="fas fa-play-circle"></i></button>

                            Episódio 1: O nome do episódio<br><br>
                            <div class="progress" style="width: 100%;height: 7px;">
                                <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </a>
                        <span style="font-size: 13px;">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim minim minim minim
                        </span>

                    </td>
                    <td style="color: rgb(0, 112, 225); font-size: 13px;padding-top: 40px;">29 de Maio de 2020</td>
                </tr>
                <tr>
                    <td style="color: rgb(0, 112, 225); font-size: 13px;">42min</td>
                </tr>
                <tr>
                    <td style="color: rgb(0, 112, 225); font-size: 13px;padding-bottom: 40px;">Classificação do conteúdo</td>
                </tr>
            </table> -->
            <!-- Episodio fim -->

        </div>
    </div>
    <br><br><br><br><br><br><br><br><br><br>
    <!-- Fim da listagem de episodios -->

    <!-- Importacao dos scripts Comeco -->
    
    
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.0-beta/js/bootstrap-select.min.js"></script>
    
    <script type="text/javascript" src="//wurfl.io/wurfl.js"></script>
    <!-- Importacao dos scripts Fim -->
    <script>
        $(document).ready(function() {
            $("#watch").on('hidden.bs.modal', function() {
                document.getElementById("FrameIdVideo").src = "";
            });
        });


        if (WURFL.is_mobile === true && WURFL.form_factor === "Smartphone") {
            document.getElementById('divSinopse').style.width = '100%';
        }

        function continuarAss() {
            $.ajax({
                dataType: 'html',
                url: "assistindoUpdate.php?id=<?php echo $idObra ?>",
                success: function(resposta) {
                    document.getElementById('FrameIdVideo').src = 'player.php?id=' + resposta;
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }

            });
        }

        function openIframe(id){
            $('html,body').scrollTop(0);
            document.getElementById('body').style = "overflow: hidden;background-image: url('/login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold; padding-left: 3%;";
            document.getElementById('frameSrc').src = "player.php?id="+id;
            $('#frameSrc').removeAttr('hidden');
            $('#btnClose').removeAttr('hidden');
            document.getElementById('frameSrc').focus();
        }

        function closeIframe(){
            document.getElementById('body').style = "overflow: show;background-image: url('/login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold; padding-left: 3%;";
            document.getElementById('frameSrc').setAttribute('hidden', '');
            document.getElementById('btnClose').setAttribute('hidden', '');
            document.getElementById('frameSrc').src = "";
        }

        function addFavoritos() {
            $.ajax({
                dataType: 'html',
                url: "addFavoritos.php?id=<?php echo $idObra ?>",
                success: function(resposta) {
                    if (resposta == "removido") {
                        document.getElementById('heart').innerHTML = '<i class="fas fa-heart"></i>';
                        document.getElementById('heart').title = 'Adicionar aos favoritos';
                        alert("Removido da lista de favoritos");
                    }
                    if (resposta == "naoremovido") {
                        alert("Erro ao remover da lista de favoritos tente novamente mais tarde");
                    }
                    if (resposta == 1) {
                        document.getElementById('heart').innerHTML = '<i class="fas fa-heart-broken"></i>';
                        document.getElementById('heart').title = 'Remover dos favoritos';
                        alert("Adicionado a lista de favoritos");
                    }
                    if (resposta == 0) {
                        alert("Erro ao inserir na lista de favoritos tente novamente mais tarde");
                    }
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }

            });
        }

        
    </script>

</body>

</html>
<?php
?>