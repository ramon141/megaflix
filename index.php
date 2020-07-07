<?php

session_start();
require 'conexao.php';

$filmes = mysqli_query($connection, "select * from obra where tipo = 'F' limit 10");
$series = mysqli_query($connection, "select * from obra where tipo = 'S' limit 10");


if ((!isset($_SESSION['nomeUsuario']) || $_SESSION['login'] != 1) && !isset($_COOKIE['hashIdUser'])) {
    $idUsuario = -1;
} else {
    $idUsuario = $_SESSION['idUsuario'];
}

$queryAssistindo = mysqli_query($connection, "select * from assistindo inner join video on (assistindo.video_id_video = video.id_video ) inner join obra on (video.obra_id_obra = obra.id_obra) where assistindo.usuario_id_usuario = $idUsuario order by date desc");

function verificar($id_obra){
    require 'conexao.php';
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
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="sortcut icon" href="/logo.png" type="image/png" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="stilo.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

    <script src="https://unpkg.com/swiper/swiper-bundle.js"></script>
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
    <link rel="stylesheet" href="stilo.css">
    <link href="//db.onlinewebfonts.com/c/3a9f81b70e68b2aa90dd3e9398593a0b?family=DSariW01-SemiBold" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <title>MegaFlix </title>
</head>

<body style="background-image: url('login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold;">

    <?php require 'header.php' ?>


    <!-- Swiper -->
    <div class="swiper-container hoverTitle" style="height: 350px;">
        <div class="swiper-wrapper">

            <?php 
            $querySlider = mysqli_query($connection, "select * from obra where sinopse !='' and tipo='S' and poster is not null ORDER by nota_imdb desc limit 4;");

            while($fetchSlider = mysqli_fetch_array($querySlider)){
                echo '
                <div class="swiper-slide">
                    <div style="background-image: url('.$fetchSlider['poster'].');width: 100%; height: 100%; background-repeat: no-repeat; background-size: 100%; padding-left: 9%;padding-top: 30px;">
                        <div class="showHoverTitle" style="background-color: rgba(0,0,0,0.8); border-radius: 20px;color: white; width: 90%;">
                            <br>
                            <h1>'.$fetchSlider['titulo'].'</h1>
                            <h6>'.substr($fetchSlider['sinopse'],0,187).'...</h6>
                            <br>
                            <button class="btn btn-rounded btn-danger" style="border-radius: 22px;" onclick="window.location.href=\'enter/serie.php?id='.$fetchSlider['id_obra'].'\'" >Assistir<i class="fas fas fa-video pl-1"></i></button>
                            <br><br>
                        </div>
                    </div>
                </div>

            ';
            }

             ?>

        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation -->
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>


    <!-- Include plugin after Swiper -->
    <script>
        /* ========
        Debugger plugin, simple demo plugin to console.log some of callbacks
        ======== */
        var myPlugin = {
            name: 'debugger',
            params: {
                debugger: false,
            },
            on: {
                init: function() {
                    if (!this.params.debugger) return;
                    console.log('init');
                },
                click: function(e) {
                    if (!this.params.debugger) return;
                    console.log('click');
                },
                tap: function(e) {
                    if (!this.params.debugger) return;
                    console.log('tap');
                },
                doubleTap: function(e) {
                    if (!this.params.debugger) return;
                    console.log('doubleTap');
                },
                sliderMove: function(e) {
                    if (!this.params.debugger) return;
                    console.log('sliderMove');
                },
                slideChange: function() {
                    if (!this.params.debugger) return;
                    console.log('slideChange', this.previousIndex, '->', this.activeIndex);
                },
                slideChangeTransitionStart: function() {
                    if (!this.params.debugger) return;
                    console.log('slideChangeTransitionStart');
                },
                slideChangeTransitionEnd: function() {
                    if (!this.params.debugger) return;
                    console.log('slideChangeTransitionEnd');
                },
                transitionStart: function() {
                    if (!this.params.debugger) return;
                    console.log('transitionStart');
                },
                transitionEnd: function() {
                    if (!this.params.debugger) return;
                    console.log('transitionEnd');
                },
                fromEdge: function() {
                    if (!this.params.debugger) return;
                    console.log('fromEdge');
                },
                reachBeginning: function() {
                    if (!this.params.debugger) return;
                    console.log('reachBeginning');
                },
                reachEnd: function() {
                    if (!this.params.debugger) return;
                    console.log('reachEnd');
                },
            },
        };
    </script>

    <script>
        // Install Plugin To Swiper
        Swiper.use(myPlugin);

        // Init Swiper
        var swiper = new Swiper('.swiper-container', {
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            // Enable debugger
            debugger: true,
        });
    </script>
    <br><br>

    <style>
        .item {
            text-align: justify;
            height: 200px;
            background-size: 100%;
            border-radius: 10px;
            cursor: pointer;
        }

        .showInfo {
            display: none;
            border-radius: 10px;
        }

        .item:hover .showInfo {
            display: block;
        }

        .item:hover {
            height: 300px;
            transition: height .4s linear;
        }

        .item:not(:hover) {
            height: 200px;
            transition: height .4s linear;
        }
    </style>

    <div style="padding-left: 20px;padding-right: 20px;">
        <h1 style="color: white;"><?php if(mysqli_num_rows($queryAssistindo)){echo "Assistindo";} ?></h1>
        <div class="owl-carousel owl-theme">
            <?php
            $obra = "";
            while ($fetchAssistindo = mysqli_fetch_array($queryAssistindo)) {
                if(strpos($obra, $fetchAssistindo['obra_id_obra']."aa") === false){
                    $obra = $obra . $fetchAssistindo['obra_id_obra']."aa";
                    echo '
                <div class="item"
            style="background-image: url(' . $fetchAssistindo['miniatura'] . ');">
            <div class="showInfo" style="position: absolute;bottom: 0px;">
                <div style="background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.9));">&nbsp;<br><br></div>
                <div style="background-color: rgba(0, 0,0,0.9);color: white; padding-left: 10px;padding-right: 10px;border-radius: 10px;">
                    <button title="Assistir" class=" btn-floating btn-primary" onclick="window.location.href = \'enter/';
                    if (strcmp($fetchAssistindo['tipo'], "F") == 0) {
                        echo "filme";
                    } else {
                        echo 'serie';
                    }
                    echo '.php?id=' . $fetchAssistindo['id_obra'] . '\';"
                        style="border-radius: 13px;border-width: 0px; width: 35px;height: 35px;">
                        <i class="fas fas fa-play-circle"></i></button> <span>
                        &#160;&#160;&#160;' . $fetchAssistindo['titulo'] . '</span><br><br>
                    <span style="font-size: 13px;line-height: 0">' . substr($fetchAssistindo['sinopse'],0,105) . '...</span><br>
                        
                        ';
                        if(verificar($fetchAssistindo['id_obra'])){
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchAssistindo['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart-broken"></i></button>';
                        } else {
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchAssistindo['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart"></i></button>';
                        }
                        echo'

                    <span title="Nota IMDB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $fetchAssistindo['nota_imdb'] . '/10 <i class="fas fa-star"></i></span>
                    <br><br>
                </div>
            </div>
        </div>
                ';
                }
            }
            ?>
        </div>
    </div>
    <?php if(mysqli_num_rows($queryAssistindo)){echo "<br><br><br><br>";} ?>
    <!-- Div Assistindo Comeco -->
    <div style="padding-left: 20px;padding-right: 20px;">
        <h1 style="color: white;">Filmes</h1>
        <div class="owl-carousel owl-theme">
            <?php
            while ($fetchFilmes = mysqli_fetch_array($filmes)) {
                echo '
            <div class="item"
            style="background-image: url(' . $fetchFilmes['miniatura'] . ');">
            <div class="showInfo" style="position: absolute;bottom: 0px;">
                <div style="background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.9));">&nbsp;<br><br></div>
                <div style="background-color: rgba(0, 0,0,0.9);color: white; padding-left: 10px;padding-right: 10px;border-radius: 10px;">
                    <button title="Assistir" class=" btn-floating btn-primary" onclick="window.location.href = \'enter/filme.php?id=' . $fetchFilmes['id_obra'] . '\'"
                        style="border-radius: 13px;border-width: 0px; width: 35px;height: 35px;">
                        <i class="fas fas fa-play-circle"></i></button> <span>
                        &#160;&#160;&#160;' . $fetchFilmes['titulo'] . '</span><br><br>
                    <span style="font-size: 13px;line-height: 0">' . substr($fetchFilmes['sinopse'],0,105) . '...</span><br>
                        
                    ';

                    if(verificar($fetchFilmes['id_obra'])){
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchFilmes['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart-broken"></i></button>';
                        } else {
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchFilmes['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart"></i></button>';
                        }


                    echo'

                    <span title="Nota IMDB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $fetchFilmes['nota_imdb'] . '/10 <i class="fas fa-star"></i></span>
                    <br><br>
                </div>
            </div>
        </div>
            ';
            }
            ?>
            <!-- Item slider comeco -->
            <!-- <div class="item" style="background-image: url(https://image.tmdb.org/t/p/w600_and_h900_bestv2/rOYDI5EqtKrnr0IX271V7daRdaH.jpg);">
                <div class="showInfo" style="position: absolute;bottom: 0px;">
                    <div style="background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.9));">&nbsp;<br><br></div>
                    <div style="background-color: rgba(0, 0,0,0.9);color: white; padding-left: 10px;padding-right: 10px;">
                        <button title="Assistir" class=" btn-floating btn-primary" style="border-radius: 13px;border-width: 0px; width: 35px;height: 35px;">
                            <i class="fas fas fa-play-circle"></i></button> <span>
                            &#160;&#160;&#160;Titulo</span><br>
                        <span style="font-size: 13px;line-height: 0">Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
                            veniam, quis</span><br>
                        <button title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart"></i></button>
                        <span title="Nota IMDB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10/10 <i class="fas fa-star"></i></span>
                        <br><br>
                    </div>
                </div>
            </div> -->
            <!-- Item slider fim -->
        </div>
    </div>
    <!-- Div Assistindo Fim -->
    <br><br>
    <div style="padding-left: 20px;padding-right: 20px;">
        <h1 style="color: white;">Series</h1>
        <div class="owl-carousel owl-theme">
            <?php
            while ($fetchSeries = mysqli_fetch_array($series)) {
                echo '
                <div class="item"
            style="background-image: url(' . $fetchSeries['miniatura'] . ');">
            <div class="showInfo" style="position: absolute;bottom: 0px;">
                <div style="background-image: linear-gradient(rgba(0,0,0,0), rgba(0,0,0,0.9));">&nbsp;<br><br></div>
                <div style="background-color: rgba(0, 0,0,0.9);color: white; padding-left: 10px;padding-right: 10px;border-radius: 10px;">
                    <button title="Assistir" class=" btn-floating btn-primary" onclick="window.location.href = \'enter/serie.php?id=' . $fetchSeries['id_obra'] . '\'"
                        style="border-radius: 13px;border-width: 0px; width: 35px;height: 35px;">
                        <i class="fas fas fa-play-circle"></i></button> <span>
                        &#160;&#160;&#160;' . $fetchSeries['titulo'] . '</span><br><br>
                    <span style="font-size: 13px;line-height: 0">' . substr($fetchSeries['sinopse'],0,105) . '...</span><br>
                    
                        ';


                        if(verificar($fetchSeries['id_obra'])){
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchSeries['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart-broken"></i></button>';
                        } else {
                            echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchSeries['id_obra'].',this);" style="border-radius: 22px; border-width: 0px; cursor: pointer; width: 30px; height: 30px;"><i class="fas fa-heart"></i></button>';
                        }

                        
                    echo'

                    <span title="Nota IMDB">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $fetchSeries['nota_imdb'] . '/10 <i class="fas fa-star"></i></span>
                    <br><br>
                </div>
            </div>
        </div>
                ';
            }
            ?>
        </div>
    </div>


    <script>
        $('.owl-carousel').owlCarousel({
            margin: 10,
            nav: true,
            responsive: {
                0: {
                    items: 1
                },
                600: {
                    items: 3
                },
                1000: {
                    items: 5
                }
            }
        })

        function addFavoritos(obra,elem) {
                    $.ajax({
                        dataType: 'html',
                        url: 'enter/addFavoritos.php?id='+obra,
                        success: function(resposta) {
                            if (resposta == "removido") {
                                elem.innerHTML = '<i class="fas fa-heart"></i>';
                                elem.title = 'Adicionar aos favoritos';
                                alert('Removido da lista de favoritos');
                            }
                            if (resposta == 'naoremovido') {
                                alert('Erro ao remover da lista de favoritos tente novamente mais tarde');
                            }
                            if (resposta == 1) {
                                elem.innerHTML = `<i class='fas fa-heart-broken'></i>`;
                                elem.title = 'Remover dos favoritos';
                                alert('Adicionado a lista de favoritos');
                            }
                            if (resposta == 0) {
                                alert('Erro ao inserir na lista de favoritos tente novamente mais tarde');
                            }
                        },
                        error: function(xhr, status, error) {
                            alert(xhr.responseText);
                        }

                    });
                }

    </script>

    <br><br><br><br><br><br><br>

</body>

</html>