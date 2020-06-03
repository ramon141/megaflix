<?php
require 'verificar.php';
if (!($_GET && isset($_GET['id']))) {
    header("Location: /");
}

require '../conexao.php';

$isMobile = false;
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
    $isMobile = true;
}

$idFilme = $_GET['id'];
$queryFilme = mysqli_query($connection, "select * from obra where id_obra = $idFilme");
while ($fetchFilme = mysqli_fetch_array($queryFilme)) {
    $nomeFilme = $fetchFilme['titulo'];
    $miniaturaFilme = $fetchFilme['miniatura'];
    $notaFilme = $fetchFilme['nota_imdb'];
    $sinopseFilme = $fetchFilme['sinopse'];
}

$queryFilmeVideo = mysqli_query($connection, "SELECT * FROM `video` WHERE obra_id_obra = $idFilme");
$srcFilme = 'player.php?id=';
while ($fetchFilmeVideo = mysqli_fetch_array($queryFilmeVideo)) {
    $srcFilme = $srcFilme . $fetchFilmeVideo['id_video'];
    $anoFilme = $fetchFilmeVideo['data'];
    $posterFilme = $fetchFilmeVideo['poster'];
    break;
}

function verificar($id_obra){
    require '../conexao.php';
    $idUsuario = $_SESSION['idUsuario'];
    $queryInsert = mysqli_query($connection, "SELECT `usuario_id_usuario`, `obra_id_obra` FROM `favoritos` WHERE usuario_id_usuario = '$idUsuario' and obra_id_obra='$id_obra'");
    if (mysqli_num_rows($queryInsert) > 0) {
        return true;
    } else {
        return false;
    }
    if ($connection) {
        mysqli_close($connection);
    }
}


?>



<!DOCTYPE html>
<html lang="pt">

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

<body style="background-image: url('/login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold; padding-left: 3%;">

    <?php require '../header.php'; ?>

    <div style="background-image: url(<?php echo $posterFilme; ?>); background-repeat: no-repeat; background-position: right; color: white; background-size: auto 100%;">
        <div style="background-image: url(../fundoFilme.png);background-size: 100%;  border-radius: 10px;">
            <div id="divSinopse" style="width: 40%;padding-left: 2%; padding-top: 2%; padding-right: 15px; text-align: justify;">
                <h2><button title="Assistir" class=" btn-floating btn-primary" style="border-radius: 16px;border-width: 0px;cursor: pointer;"><i class="fas fa-play-circle"></i></button>&nbsp; <?php echo $nomeFilme; ?> <span style="font-size: 13px;"><?php echo $anoFilme; ?></span> </h2><br>
                
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar" role="progressbar" aria-valuenow="0" style="width: 25%" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span style="font-size: 13px;"><?php echo $sinopseFilme ?></span><br>

                <br>
                <div class="container">
                    <div class="row">


                        <!-- informacoes     -->
                        <span style="font-size: 12px;">

                            <table>

                                <tbody>
                                    <tr>
                                        <td>Classificação indicativa</td>
                                        <td rowspan="4" style="padding-left: 10px;">
                                            <?php
                                            if (verificar($idFilme)) {
                                                echo '<button onclick="addFavoritos();" title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 40px;height: 40px;" id="heart"><i class="fas fa-heart-broken"></i></button>';
                                            } else {
                                                echo '<button onclick="addFavoritos();" title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 40px;height: 40px;" id="heart" ><i class="fas fa-heart"></i></button>';
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>IMDB: <?php echo $notaFilme; ?>&nbsp;<i class="fas fa-star"></i></td>
                                    </tr>
                                </tbody>
                            </table>

                        </span>


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


    <!-- Importacao dos scripts Comeco -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="//wurfl.io/wurfl.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Importacao dos scripts Fim -->
    <script>
        if (WURFL.is_mobile === true && WURFL.form_factor === "Smartphone") {
            document.getElementById('divSinopse').style.width = '100%';
        }

        function addFavoritos() {
            $.ajax({
                dataType: 'html',
                url: "addFavoritos.php?id=<?php echo $idFilme ?>",
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
if($connection){
    mysqli_close($connection);
}
?>