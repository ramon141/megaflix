<?php

require 'conexao.php';
$queryResult = mysqli_query($connection, 'select * from obra where titulo LIKE "%' . $_GET['q'] . '%" or sinopse LIKE "%' . $_GET['q'] . '%";');

$linkFormType = 'search.php?q='.$_GET['q'].'&type=';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link href="stilo.css" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <link rel="stylesheet" href="stilo.css">


    <title>Document</title>
</head>

<body style="background-image: url('login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold;padding-left: 10px;">

    <?php require 'header.php'; ?>
    <div style="padding: 10px;">

        <table style="width:100%">
            <tr>
                <td>
                    <span style="color: white;font-size: 20px;"><?php echo mysqli_num_rows($queryResult) . ' resultado(s) para "' . $_GET['q'].'"' ?></span>
                </td>
                <td align="right">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Filtrar (Filmes)
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <button class="dropdown-item" name="type" onclick="window.location.href = <?php echo '\''.$linkFormType . 'filme\'';?>">Filme</button>
                                <button class="dropdown-item" name="type" onclick="window.location.href = <?php echo '\''.$linkFormType . 'serie\'';?>">Série</button>
                        </div>
                    </div>
                </td>
            </tr>
        </table>
        <hr style="width:100%;text-align:left;border: 1px solid red;margin-left:0">

        <style>
            .showInfo {
                background-color: yellowgreen;
                height: 200px;
                border-radius: 10px;
                width: 200px;
                background-size: 100%;
            }

            .item {

                background-color: rgba(39, 55, 71, 0.9);
                width: 200px;
                border-radius: 10px;
            }

            .play {
                opacity: 0;
                background-color: rgba(39, 55, 71, 0.9);
                height: 100%;
            }

            .showInfo:hover .play {
                animation-name: example;
                animation-duration: 0.5s;
                opacity: 1;
            }

            @keyframes example {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }
        </style>

        <div class="container-fluid">
            <div class="row">

                <?php
                while ($fetchResultado = mysqli_fetch_array($queryResult)) {
                    echo '<div class="col-lg-2"><div class="item">
                        <div class="showInfo" style="background-image: url(' . $fetchResultado['miniatura'] . ')">
                            <div class="play">
                                <a href="enter/';


                                if(strcmp($fetchResultado['tipo'], "F") == 0){
                                    echo 'filme';
                                } else {
                                    echo 'serie';
                                }

                                echo '.php?id='.$fetchResultado['id_obra'].'"><img src="play.png" alt="" width="75px" style="position: relative; top: 30%; left: 30%;"></a>
                            </div>
                        </div>
                        <div style="padding: 10px; color: white;width:100%">
                            <table style="width: 100%;padding-right: 10px;">
                                <tr>
                                    <td>
                                    ' . $fetchResultado['titulo'] . '
                                    </td>
                                    <td align="right">
                                        <button title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px;width: 30px;height: 30px;"><i class="fas fa-heart"></i></button>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div><br></div>';
                }
                ?>

                <!-- item comeco -->
                <!-- <div class="item">
                        <div class="showInfo">
                            <div class="play">
                                <a href=""><img src="play" alt="" width="75px" style="position: relative; top: 30%; left: 30%;"></a>
                            </div>
                        </div>
                        <div style="padding: 10px; color: white;width:100%">
                            <table style="width: 100%;padding-right: 10px;">
                                <tr>
                                    <td>
                                        Titulo fd dsfsfdsf df sd f df df ds fd fd f dsf dsf
                                    </td>
                                    <td align="right">
                                        <button title="Adicionar aos favoritos" class=" btn-floating btn-danger" style="border-radius: 22px; border-width: 0px;width: 30px;height: 30px;"><i class="fas fa-heart"></i></button>
                                    </td>
                                </tr>
                            </table>

                        </div>
                    </div> -->
                <!-- item fim -->


            </div>
        </div>
</body>

</html>