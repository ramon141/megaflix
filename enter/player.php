<?php 
require 'verificar.php';
require '../conexao.php';
$id = $_GET['id'];
$source = mysqli_query($connection, "select * from source where video_id_video = $id");
$queryVideos = mysqli_query($connection, "select * from video where id_video = $id");

$queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");

if (session_status() != PHP_SESSION_ACTIVE) {
    session_start();
}
$idUsuario = $_SESSION['idUsuario'];
$nomeEpisodio = "Episódio";

while($fetchVideos = mysqli_fetch_array($queryVideos)){
    $temporada = $fetchVideos['temporada'];
    $episodio = $fetchVideos['episodio'];
    $id_obra = $fetchVideos['obra_id_obra'];
    $nomeEpisodio = $fetchVideos['nomeEpisodio'];
}

?>

<html>
<head>
    <title>title</title>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.10/plyr.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

    <script type="text/javascript">
        //ir ate o ponto



        



        document.addEventListener('keydown', teclado);
        function teclado(e){


            if(e.keyCode == 65){
                alert(document.getElementById("player").currentTime);
            }
            if(e.keyCode == 70){
                fullscreenBtn();
            }

            //N == 78
            if(e.keyCode == 78){
                <?php 

                while($fetchPular = mysqli_fetch_array($queryPular)){
                    $comeco = $fetchPular['comeco'];
                    $fim = $fetchPular['fim'];


                    echo '
                    if(document.getElementById("player").currentTime > '.$comeco.' && document.getElementById("player").currentTime < '.$fim.'){
                        document.getElementById("player").currentTime = '.$fim.';
                    }


                    ';
                }



                ?>
            }

            var setenta = document.querySelector('#player').duration * 0.8;
            
            if(e.keyCode == 83 && setenta < document.getElementById("player").currentTime){
                pularEpisodio();
            }
            //document.getElementById("player").currentTime = 100;
        }


        function pularEpisodio(){
            <?php 
            $queryVideos = mysqli_query($connection, "select * from video where obra_id_obra = $id_obra order by temporada,episodio");
            $bool = false;
            while($fetchVideos = mysqli_fetch_array($queryVideos)){
                if($bool){
                    $proximoIdVideo = $fetchVideos['id_video'];
                    echo 'window.location.href = "player.php?full=1&id=' . $proximoIdVideo . '"';
                    break;
                }
                if($temporada == $fetchVideos['temporada'] && $episodio == $fetchVideos['episodio']){
                    $bool = true;
                }
            }

            if(!$bool){
                echo 'alert("Você está no último episódio");';
            }



            ?>
        }

    </script>

    <style type="text/css">
        .teste{
            position: absolute;  
            float: left;
            z-index: 1000 !important;
            margin-left: 10%;
            background: red;
        }
    </style>

</head>
<body>


    <div style="height: 70%" id="divVideo">
        <video id="player" playsinline controls <?php 
        if($_GET && isset($_GET['full']) && $_GET['full'] == 1){
            echo '
            autoplay=""
            ';
        }
        ?>>

        <?php 
        while ($fetchSource = mysqli_fetch_array($source)) {
            echo '<source src="';

            if(strpos($fetchSource['src'], "maxseries.tv") !== false){
                echo file_get_contents("http://megaflix.freevar.com/teste/embed.php?url=" . $fetchSource['src']);
            } else {
                echo $fetchSource['src'];
            }
            echo '" type="video/mp4" size="'.$fetchSource['resolucao'].'"/>';
        }
        ?>    
    </video>
</div>
<script type="text/javascript">


    <?php 
    
    require 'progresso.php';
    echo 'document.getElementById("player").currentTime = '. whatTime($id). ';';

    ?>
        //fim do ir ate o ponto


    </script>

    <br>
    <button onclick="pularEpisodio()">Próximo episódio</button>
    
    <form id="formulario" method="post" action="atualizaTempo.php">
        <input type="hidden" name="tempo"     id="tempo" value="0">
        <input type="hidden" name="idvideo"   id="idvideo" <?php echo 'value="'.$id.'"' ?>>
        <input type="hidden" name="usuario_id_usuario"   id="usuario_id_usuario" <?php echo 'value="'.$idUsuario.'"' ?>>
    </form>



    <script src="https://cdn.plyr.io/3.5.10/plyr.js"></script>
    <script>



        function fullscreenBtn(){
            var elem = document.getElementById("player");
            if (elem.requestFullscreen) {
              elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) {
              elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) {
              elem.webkitRequestFullscreen();
          }        
      }


      document.getElementById("divVideo").addEventListener("click", function(event) { 
        over();
        out();
    });

      const controls = `

      <div style="position: absolute;z-index:1000; padding-top: 30px;width:100%; background-image: linear-gradient(black, rgba(0,0,0,0));height:70px; opacity:0;" onmouseover="over();" onmouseleave="out();" id="teste">
      <div style="position: absolute;right:10px;">
      <button id="pularBtn" onclick="pulara();" style="opacity:0">Pular Abertura</button>
      <button onclick="pularEpisodio()">Pular Episódio</button>
      </div>
      <div style="text-align: center;color:white;font-weight:bold; top:10px">
      <?php echo $nomeEpisodio; ?>
      </div>
      </div>


      <div class="plyr__controls">


      <button class="plyr__controls__item plyr__control" type="button" data-plyr="play" aria-label="Play">
      <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-pause"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-play"></use></svg>
      <span class="label--pressed plyr__sr-only">Pause</span><span class="label--not-pressed plyr__sr-only">Play</span>
      </button>
      <button type="button" class="plyr__control" data-plyr="fast-forward">
      <svg role="presentation"><use xlink:href="#plyr-fast-forward"></use></svg>
      <span class="plyr__tooltip" role="tooltip">Forward {seektime} secs</span>
      </button>
      <div class="plyr__controls__item plyr__progress__container">
      <div class="plyr__progress">
      <input
      data-plyr="seek"
      type="range"
      min="0"
      max="100"
      step="0.01"
      value="0"
      autocomplete="off"
      role="slider"
      aria-label="Seek"
      aria-valuemin="0"
      aria-valuemax="1449.991"
      aria-valuenow="2.803466"
      id="plyr-seek-3386"
      aria-valuetext="00:02 of 24:09"
      style="--value: 0.19%; user-select: none; touch-action: manipulation;"
      />
      <progress class="plyr__progress__buffer" min="0" max="100" value="4.33513035598152" role="progressbar" aria-hidden="true">% buffered </progress><span class="plyr__tooltip">00:00</span>
      </div>
      </div>
      <div class="plyr__controls__item plyr__time--current plyr__time" aria-label="Current time">-24:07</div>
      <div class="plyr__controls__item plyr__volume">
      <button type="button" class="plyr__control" data-plyr="mute">
      <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-muted"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-volume"></use></svg>
      <span class="label--pressed plyr__sr-only">Unmute</span><span class="label--not-pressed plyr__sr-only">Mute</span>
      </button>
      <input
      data-plyr="volume"
      type="range"
      min="0"
      max="1"
      step="0.05"
      value="1"
      autocomplete="off"
      role="slider"
      aria-label="Volume"
      aria-valuemin="0"
      aria-valuemax="100"
      aria-valuenow="85"
      id="plyr-volume-3386"
      aria-valuetext="85.0%"
      style="--value: 85%; user-select: none; touch-action: manipulation;"
      />
      </div>
      <button class="plyr__controls__item plyr__control" type="button" data-plyr="captions">
      <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-captions-on"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-captions-off"></use></svg>
      <span class="label--pressed plyr__sr-only">Disable captions</span><span class="label--not-pressed plyr__sr-only">Enable captions</span>
      </button>
      <div class="plyr__controls__item plyr__menu">
      <button aria-haspopup="true" aria-controls="plyr-settings-3386" aria-expanded="false" type="button" class="plyr__control" data-plyr="settings">
      <svg aria-hidden="true" focusable="false"><use xlink:href="#plyr-settings"></use></svg><span class="plyr__sr-only">Settings</span>
      </button>
      <div class="plyr__menu__container" id="plyr-settings-3386" hidden="">
      <div>
      <div id="plyr-settings-3386-home">
      <div role="menu">
      <button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true" hidden="">
      <span>Captions<span class="plyr__menu__value">Disabled </span></span>
      </button>
      <button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true" hidden="">
      <span>Quality<span class="plyr__menu__value">undefined</span></span>
      </button>
      <button data-plyr="settings" type="button" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true">
      <span>Speed<span class="plyr__menu__value">Normal</span></span>
      </button>
      </div>
      </div>
      <div id="plyr-settings-3386-captions" hidden="">
      <button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Captions</span><span class="plyr__sr-only">Go back to previous menu</span></button>
      <div role="menu"></div>
      </div>
      <div id="plyr-settings-3386-quality" hidden="">
      <button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Quality</span><span class="plyr__sr-only">Go back to previous menu</span></button>
      <div role="menu"></div>
      </div>
      <div id="plyr-settings-3386-speed" hidden="">
      <button type="button" class="plyr__control plyr__control--back"><span aria-hidden="true">Speed</span><span class="plyr__sr-only">Go back to previous menu</span></button>
      <div role="menu">
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="0.5"><span>0.5×</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="0.75"><span>0.75×</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="true" value="1"><span>Normal</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.25"><span>1.25×</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.5"><span>1.5×</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="1.75"><span>1.75×</span></button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="2">
      <span>2×</span>
      </button>
      <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control" aria-checked="false" value="4"><span>4×</span></button>
      </div>
      </div>
      </div>
      </div>
      </div>
      <button class="plyr__controls__item plyr__control" type="button" data-plyr="pip">
      <svg aria-hidden="true" focusable="false"><use xlink:href="#plyr-pip"></use></svg><span class="plyr__sr-only">PIP</span>
      </button>
      <button class="plyr__controls__item plyr__control" type="button" data-plyr="fullscreen">
      <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-exit-fullscreen"></use></svg>
      <svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-enter-fullscreen"></use></svg><span class="label--pressed plyr__sr-only">Exit fullscreen</span>
      <span class="label--not-pressed plyr__sr-only">Enter fullscreen</span>
      </button>
      </div>


      `;



      function over(){
        var s = document.getElementById('teste').style;
        s.opacity = '1';
        s.transition = 'all 0.4s linear';
    }
    function out(){
        setTimeout( function(){
            var s = document.getElementById('teste').style;
            s.opacity = '0';
            s.transition = 'all 2s linear';
        },  2000);
    }

    function out10(){
        setTimeout( function(){
            var s = document.getElementById('teste').style;
            s.opacity = '0';
            s.transition = 'all 5s linear';
        },  2000);
    }

    function pulara(){
        <?php 
        $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
        while($fetchPular = mysqli_fetch_array($queryPular)){
            $comeco = $fetchPular['comeco'];
            $fim = $fetchPular['fim'];


            echo '
            if(document.getElementById("player").currentTime > '.$comeco.' && document.getElementById("player").currentTime < '.$fim.'){
                document.getElementById("player").currentTime = '.$fim.';
            }
            ';
        }



        ?>
    }

        // Setup the player
        const player = new Plyr('#player', { controls });

        setInterval(function(){
            <?php 
            $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
            while($fetchPular = mysqli_fetch_array($queryPular)){
                $comeco = $fetchPular['comeco'];
                $fim = $fetchPular['fim'];


                echo '
                if(player.currentTime > '.$comeco.' && player.currentTime < '.$fim.'){
                    document.getElementById("pularBtn").style.opacity="1";
                    over();
                    out10();
                } else {
                    document.getElementById("pularBtn").style.opacity="0";
                }

                ';
            }



            ?>

        }, 1000);

        //comeco ajax
        function update(){
            $.ajax({
               dataType:'html',
               url:"atualizaTempo.php",
               type:"POST",
               data:({tempo:$("input[name='tempo']").val(),usuario_id_usuario:$("input[name='usuario_id_usuario']").val(),idvideo:$("input[name='idvideo']").val()}),

               success: function(resposta) {
                    //alert(resposta);
                },
                error: function(xhr, status, error) {
                    alert(xhr.responseText);
                }

            });
        }
        //fim ajax


        setInterval(function() { if(document.getElementById("player").currentTime == document.getElementById("player").duration) {pularEpisodio();}document.getElementById("tempo").value = document.getElementById("player").currentTime; update(); }, 5000);
    </script>
</body>
</html>
<?php
if($connection){
    mysqli_close($connection);
}
?>