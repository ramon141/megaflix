<?php
require 'verificar.php';
require '../conexao.php';
$id = $_GET['id'];
$pularAuto = false;

if (session_status() != PHP_SESSION_ACTIVE) {
  session_start();
}
if(isset($_SESSION['puloAuto']) && strcmp($_SESSION['puloAuto'], "1") == 0){
  $pularAuto = true;
}
if(isset($_SESSION['audio']) && strcmp($_SESSION['audio'], "L") == 0){
  $source = mysqli_query($connection, "select * from source where video_id_video = $id order by audio desc");  
} else {
  $source = mysqli_query($connection, "select * from source where video_id_video = $id order by audio");
}

$queryVideos = mysqli_query($connection, "select * from video where id_video = $id");
//$linkRelativoAgora = "player.php?id=".$_GET['id'];

$queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");


$idUsuario = $_SESSION['idUsuario'];
$nomeEpisodio = "Episódio";

while ($fetchVideos = mysqli_fetch_array($queryVideos)) {
  $temporada = $fetchVideos['temporada'];
  $episodio = $fetchVideos['episodio'];
  $id_obra = $fetchVideos['obra_id_obra'];
  $nomeEpisodio = $fetchVideos['nomeEpisodio'];
}

$srcArray = "";

function getSrc($link)
{
  $data = file_get_contents($link);
  $data = preg_replace('/<\s*style.+?<\s*\/\s*style.*?>/si', ' ', $data);
  $data = strip_tags($data);
  $data = str_replace("var VIDEO_CONFIG = ", "", $data);
  $data = json_decode($data, true);

  $src = $data['streams'][0]['play_url'];


  for ($j = 0; $j < count($data['streams']); $j++) {
    $GLOBALS['srcArray'] = $GLOBALS['srcArray'] . $data['streams'][$j]['play_url'] . " || " . $j . " || ";
  }



  unset($data);
  return $src;
}

function onedrive($url) {
  $ch = curl_init();
  $timeout = 10;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_AUTOREFERER, 1); 
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $htmlf = curl_exec($ch);
  curl_close($ch);
  $onedrivecurlx = $htmlf;
  $pattern='/<\s*meta\s+property="og:([^"]+)"\s+content="([^"]*)/i';
  if(preg_match_all($pattern, $onedrivecurlx, $out)) {
    $dfikkrr = array_combine($out[1], $out[2]);
  } else {
    $dfikkrr = array();
  }
  $posterigddd = $dfikkrr['image'];
  $vurlkf = html_entity_decode(str_ireplace('com/redir', 'com/download', $dfikkrr['url']));
  $si = [];
  $si['url'] = $vurlkf;
  $si['img'] = $posterigddd;
  return $si['url'];
}

?>

<html>



<body id="body">
  <link rel="stylesheet" href="https://cdn.plyr.io/3.5.10/plyr.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>

  <style type="text/css">
    body, html{
      margin: 0; padding: 0; height: 100%; overflow: hidden;
    }
  </style>


  <script type="text/javascript">
    var overTitulo = false;
    var parar = false;

        //ir ate o ponto

        document.addEventListener('keydown', teclado);

        function teclado(e) {


          if (e.keyCode == 65) {
            alert(document.getElementById("player").currentTime);
          }
          if (e.keyCode == 70) {
            fullscreenBtn();
          }

            //N == 78
            if (e.keyCode == 78) {
              <?php
              while ($fetchPular = mysqli_fetch_array($queryPular)) {
                $comeco = $fetchPular['comeco'];
                $fim = $fetchPular['fim'];
                

                echo '
                if(document.getElementById("player").currentTime >= ' . $comeco . ' && document.getElementById("player").currentTime < ' . $fim . '){
                  document.getElementById("player").currentTime = ' . $fim . ';
                }


                ';
              }
              ?>
            }

            var setenta = document.querySelector('#player').duration * 0.8;

            if (e.keyCode == 83 && setenta < document.getElementById("player").currentTime) {
              pularEpisodio();
            }
            //document.getElementById("player").currentTime = 100;
          }


          
        </script>

        <style type="text/css">
          .teste {
            position: absolute;
            float: left;
            z-index: 1000 !important;
            margin-left: 10%;
            background: red;
          }
        </style>



        <div id="pulando"  hidden="" style="width: 100%;height: 100%">
          Pulando...
        </div>

        <span id="spanIdPular" hidden=""></span>

        <div id="carregadoPlayer" style="width: 100%;height: 100%; background-color: white;">Carregando Player...</div>

        <div style="width: 100%;height: 100%" id="divVideo" hidden="" onclick="overControl=true;over(); out();">
          <video onseeking="pularEpUmaVez = true;" id="player" oncanplay="document.getElementById('carregadoPlayer').setAttribute('hidden', '');$('#divVideo').removeAttr('hidden');" playsinline controls <?php
          if ($_GET && isset($_GET['full']) && $_GET['full'] == 1) {
            echo '
            autoplay=""
            ';
          }
          ?>>
        </video>
      </div>
      <script type="text/javascript">
        document.getElementById('player').playbackRate = 1;
        <?php
        echo "


        document.getElementById('spanIdPular').innerHTML = '';
        ";
        $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
        while ($fetchPular = mysqli_fetch_array($queryPular)) {
          echo "
          document.getElementById('spanIdPular').innerHTML = document.getElementById('spanIdPular').innerHTML + '".$fetchPular['id_pular']."||';
          ";


        }
        ?>



        <?php
        require 'progresso.php';
        echo 'document.getElementById("player").currentTime = ' . whatTime($id) . ';';
        ?>
        //fim do ir ate o ponto
      </script>

      <br>

      <?php
      $queryVideos = mysqli_query($connection, "select * from video where obra_id_obra = $id_obra order by temporada,episodio");
      $bool = false;
      $entrou = false;
      while ($fetchVideos = mysqli_fetch_array($queryVideos)) {
        if ($bool) {
          $proximoIdVideo = $fetchVideos['id_video'];
          echo '<span id="proximoVideo" hidden="">' . $proximoIdVideo . '</span>';
          echo '<span id="thumbProximoVideo" hidden="">' . $fetchVideos['poster'] . '</span>';
          echo '<span id="nomeProximoVideo" hidden="">' . $fetchVideos['nomeEpisodio'] . '</span>';
          $entrou = true;
          break;
        }
        if ($temporada == $fetchVideos['temporada'] && $episodio == $fetchVideos['episodio']) {
          $bool = true;
        }
      }
      ?>

      <form id="formulario" method="post" action="atualizaTempo.php">
        <input type="hidden" name="tempo" id="tempo" value="0">
        <input type="hidden" name="idvideo" id="idvideo" <?php echo 'value="' . $id . '"' ?>>
        <input type="hidden" name="usuario_id_usuario" id="usuario_id_usuario" <?php echo 'value="' . $idUsuario . '"' ?>>
      </form>


      <style type="text/css">
        .item_plyr:hover{
          border: 2px solid rgb(0,179,255);
        }
      </style>

      <script src="https://cdn.plyr.io/3.5.10/plyr.js"></script>
      <script>
        function fullscreenBtn() {
          var elem = document.getElementById("player");
          if (elem.requestFullscreen) {
            elem.requestFullscreen();
          } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
          } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
          }
        }

        


        const controls = `

        <div onclick="if(document.getElementById('pularEpisodioBtn').style.opacity== 1){pularEpisodio();}" style="color:white;text-align:center; position: absolute;right:40px;bottom:11%;z-index:9999;border-radius:5px;width:216px;height:165px;cursor:pointer;" id="progress" <?php if(!$pularAuto){echo 'hidden=""';} ?>>
        <br>
        <br>
        <span id="spanPularEp" style="opacity:0;font-weight: bold; font-size:35px;">5</span>
        </div>


        <div style="position: absolute;right:40px;bottom:11%;" id="teste2">
        <button id="pularEpisodioBtn" onclick="if(this.style.opacity== 1){pularEpisodio();}" style="opacity:0;border: 2px solid rgba(255,255,255,0.7);background-color:rgba(0,0,0,5);color:white;border-radius:5px;cursor:pointer;"></button>
        <button id="pularBtn" onclick="pulara();" style="position: absolute;right:10px;bottom:18%;width:130px;opacity:1;border: 2px solid rgba(255,255,255,0.7);background-color:rgba(0,0,0,5);color:white;border-radius:5px;cursor:pointer;">Pular Abertura</button>
        </div>

        <div id="divControlsPersonalizado" style="position: absolute;z-index:1000; padding-top: 30px;width:100%; opacity:1;bottom:45%; color:white; text-align:center;">

        <button type="button" class="plyr__control item_plyr" data-plyr="rewind" style="background-image: url('back.png');background-size:100% 100%; width:40px;height:40px; background-repeat: no-repeat;top:-10px;background-color:rgba(0,0,0,0);">

        <span class="plyr__tooltip" role="tooltip">Voltar {seektime} secs</span>
        </button>

        <button class="plyr__controls__item plyr__control" type="button" data-plyr="play" aria-label="Play" style="width:60px;height:70px;">
        <svg class="icon--pressed" aria-hidden="true" focusable="false" style="width:50px;height:50px;">
        <use style="width:50px;height:50px;" xlink:href="#plyr-pause"></use>
        </svg>
        <svg style="width:50px;height:50px;" class="icon--not-pressed" aria-hidden="true" focusable="false">
        <use style="width:50px;height:50px;" xlink:href="#plyr-play">
        </use>
        </svg>
        <span style="width:50px;height:50px;" class="label--pressed plyr__sr-only">Pause</span><span class="label--not-pressed plyr__sr-only">Play</span>
        </button>

        <button type="button" class="plyr__control item_plyr" data-plyr="fast-forward" style="background-image: url('forward.png');background-size:100% 100%; width:40px;height:40px; background-repeat: no-repeat;top:-10px;background-color:rgba(0,0,0,0);">
        <span class="plyr__tooltip" role="tooltip">Avançar {seektime} secs</span>
        </button>

        </div>

        <div style="position: absolute;z-index:1000; padding-top: 30px;width:100%; background-image: linear-gradient(black, rgba(0,0,0,0));height:70px; opacity:0;" onmouseover="over();overTitulo=true;" onmouseleave="out();overTitulo=false;" id="teste">
        <div style="position: absolute;left:10px;"><button onclick="pularEpisodio()" <?php if (!$entrou) {echo 'hidden=""';} ?>  >Próx. Ep.</button></div>
        <div style="text-align: center;color:white;font-weight:bold; top:10px">

        <?php

        if(empty($temporada) && empty($episodio) && empty($nomeEpisodio)){
          echo "Assistindo...";
        } else {
          echo "T".$temporada."E".$episodio." - ".$nomeEpisodio;
        }
        ?>
        </div>
        </div>


        <div class="plyr__controls">


        <button class="plyr__controls__item plyr__control" type="button" data-plyr="play" aria-label="Play">
        <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-pause"></use></svg><svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-play"></use></svg>
        <span class="label--pressed plyr__sr-only">Pause</span><span class="label--not-pressed plyr__sr-only">Play</span>
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
        <button onclick="if(document.getElementById('configuracoes').getAttribute('aria-expanded') == 'true'){document.getElementById('configuracoes').setAttribute('aria-expanded', 'false');} else {document.getElementById('configuracoes').setAttribute('aria-expanded', 'true');}if(document.getElementById('plyr-settings-3386').hasAttribute('hidden')){$('#plyr-settings-3386').removeAttr('hidden');} else {document.getElementById('plyr-settings-3386').setAttribute('hidden', '');}" aria-haspopup="true" id="configuracoes" aria-controls="plyr-settings-3386" aria-expanded="false" type="button" class="plyr__control" data-plyr="settings">
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
        <button data-plyr="settings" type="button" onclick="$('#plyr-settings-3386-speed').removeAttr('hidden');document.getElementById('plyr-settings-3386-home').setAttribute('hidden', '');" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true">
        <span>Velocidade<span class="plyr__menu__value"></span></span>
        </button>

        <button data-plyr="settings" type="button" onclick="$('#plyr-settings-3386-qualidade').removeAttr('hidden');document.getElementById('plyr-settings-3386-home').setAttribute('hidden', '');" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true">
        <span>Qualidade<span class="plyr__menu__value"></span></span>
        </button>

        <button data-plyr="settings" type="button" onclick="$('#plyr-settings-3386-idioma').removeAttr('hidden');document.getElementById('plyr-settings-3386-home').setAttribute('hidden', '');" class="plyr__control plyr__control--forward" role="menuitem" aria-haspopup="true">
        <span>Idioma<span class="plyr__menu__value"></span></span>
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
        <button type="button" class="plyr__control plyr__control--back" onclick="document.getElementById('plyr-settings-3386-speed').setAttribute('hidden', '');$('#plyr-settings-3386-home').removeAttr('hidden');"><span aria-hidden="true">Velocidade</span><span class="plyr__sr-only">Go back to previous menu</span></button>
        <div role="menu">

        <button data-plyr="speed" type="button" role="menuitemradio" id="v1" class="plyr__control" aria-checked="false" value="0.5" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true'); document.getElementById('player').playbackRate = 0.5;"><span>0.5×</span></button>
        <button data-plyr="speed" type="button" role="menuitemradio" id="v2" class="plyr__control" aria-checked="false" value="0.75" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true');document.getElementById('player').playbackRate = 0.75;"><span>0.75×</span></button>
        <button data-plyr="speed" type="button" role="menuitemradio" id="v3" class="plyr__control" aria-checked="true" value="1" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true');document.getElementById('player').playbackRate = 1;"><span>Normal</span></button>
        <button data-plyr="speed" type="button" role="menuitemradio" id="v4" class="plyr__control" aria-checked="false" value="1.5" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true');document.getElementById('player').playbackRate = 1.5;"><span>1.5×</span></button>
        <button data-plyr="speed" type="button" role="menuitemradio" id="v5" class="plyr__control" aria-checked="false" value="2" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true');document.getElementById('player').playbackRate = 2;"><span>2×</span></button>

        </div>
        </div>

        <div id="plyr-settings-3386-qualidade" hidden="">
        <button type="button" class="plyr__control plyr__control--back" onclick="document.getElementById('plyr-settings-3386-qualidade').setAttribute('hidden', '');$('#plyr-settings-3386-home').removeAttr('hidden');"><span aria-hidden="true">Qualidade</span><span class="plyr__sr-only">Go back to previous menu</span></button>
        <div role="menu" id="qualidadeMenu">

        <button data-plyr="speed" type="button" role="menuitemradio" id="v1" class="plyr__control" aria-checked="false" value="0.5" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true'); document.getElementById('player').playbackRate = 0.5;"><span>360p</span></button>
        <button data-plyr="speed" type="button" role="menuitemradio" id="v2" class="plyr__control" aria-checked="false" value="0.75" onclick="document.getElementById('v1').setAttribute('aria-checked','false');document.getElementById('v2').setAttribute('aria-checked','false');document.getElementById('v3').setAttribute('aria-checked','false');document.getElementById('v4').setAttribute('aria-checked','false');document.getElementById('v5').setAttribute('aria-checked','false');this.setAttribute('aria-checked','true');document.getElementById('player').playbackRate = 0.75;"><span>720p</span></button>

        </div>
        </div>

        <div id="plyr-settings-3386-idioma" hidden="">
        <button type="button" class="plyr__control plyr__control--back" onclick="document.getElementById('plyr-settings-3386-idioma').setAttribute('hidden', '');$('#plyr-settings-3386-home').removeAttr('hidden');"><span aria-hidden="true">Idioma</span><span class="plyr__sr-only">Go back to previous menu</span></button>
        <div role="menu">


        <?php
        $srcDublado = "sem";
        $loop = 0;
        while ($fetchSource = mysqli_fetch_array($source)) {
          $loop++;

          if (strpos($fetchSource['src'], "https://1drv.ms") !== false) {
            $srcAtual = onedrive($fetchSource['src']);
          } else if (strpos($fetchSource['src'], "https://www.blogger.com/") !== false) {
            $srcAtual = getSrc($fetchSource['src']);
          } else {
            $srcAtual = $fetchSource['src'];
          }



          if ($loop == 1) {
            $srcDublado = $srcAtual;
            $tor = "true";
          } else {
            $tor = "false";
          }



          echo '<button data-plyr="speed" type="button" role="menuitemradio" id="l' . $loop . '" class="plyr__control" aria-checked="' . $tor . '" value="0.5" onclick="      var t = document.getElementById(\'player\').currentTime; document.getElementById(\'player\').src=\'';


          if (strpos($fetchSource['src'], "maxseries.tv") !== false) {
            echo $srcAtual;
          } else if (strpos($fetchSource['src'], "https://www.blogger.com/") !== false) {
            echo $srcAtual;
          } else {
            echo $srcAtual;
          }


          echo '\'; changeQuality(); document.getElementById(\'l1\').setAttribute(\'aria-checked\',\'false\');';

          if (mysqli_num_rows($source) == 2) {
            echo "document.getElementById(\'l2\').setAttribute(\'aria-checked\',\'false\');";
          }

          echo 'this.setAttribute(\'aria-checked\',\'true\');document.getElementById(\'player\').play(); document.getElementById(\'player\').currentTime=t;document.getElementById(\'plyr-settings-3386\').setAttribute(\'hidden\', \'\');"><span>';

          if ($fetchSource['audio'] == "D") {
            echo "Dublado";
          } else {
            echo "Legendado";
          }

          echo '</span></button>';
        }
        ?>



        </div>
        </div>


        </div>
        </div>
        </div>
        <button class="plyr__controls__item plyr__control" type="button" data-plyr="pip">
        <svg aria-hidden="true" focusable="false"><use xlink:href="#plyr-pip"></use></svg><span class="plyr__sr-only">PIP</span>
        </button>
        <button class="plyr__controls__item plyr__control" type="button" onclick="changeOrientation(event);">
        <svg class="icon--pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-exit-fullscreen"></use></svg>
        <svg class="icon--not-pressed" aria-hidden="true" focusable="false"><use xlink:href="#plyr-enter-fullscreen"></use></svg><span class="label--pressed plyr__sr-only">Exit fullscreen</span>
        <span class="label--not-pressed plyr__sr-only">Enter fullscreen</span>
        </button>
        </div>




        `;




        function over() {


          var s = document.getElementById('teste').style;
          var ss = document.getElementById('teste2').style;
          s.opacity = '1';
          ss.opacity = '1';
          s.transition = 'all 0.4s linear';
          ss.transition = 'all 0.4s linear';
        }

        function out() {
          setTimeout(function() {
            var s = document.getElementById('teste').style;
            s.opacity = '0';
            s.transition = 'all 2s linear';

            var ss = document.getElementById('teste2').style;
            ss.opacity = '0';
            ss.transition = 'all 2s linear';
          }, 2000);
        }

        function out10() {
          setTimeout(function() {
            var s = document.getElementById('teste').style;
            s.opacity = '0';
            s.transition = 'all 3s linear';

            var ss = document.getElementById('teste2').style;
            ss.opacity = '0';
            ss.transition = 'all 3s linear';
          }, 4000);
        }

        function pulara() {
          document.getElementById('teste2').opacity = '0';
          <?php
          $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
          while ($fetchPular = mysqli_fetch_array($queryPular)) {
            $comeco = $fetchPular['comeco'];
            $fim = $fetchPular['fim'];


            echo '
            if(document.getElementById("player").currentTime >= ' . $comeco . ' && document.getElementById("player").currentTime < ' . $fim . '){
              document.getElementById("player").currentTime = ' . $fim . ';
            }
            ';
          }
          ?>
        }

        function getNomePular() {


          <?php
          $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
          while ($fetchPular = mysqli_fetch_array($queryPular)) {
            $comeco = $fetchPular['comeco'];
            $fim = $fetchPular['fim'];
            echo '
            if(document.getElementById("player").currentTime >= ' . $comeco . ' && document.getElementById("player").currentTime < ' . $fim . '){
              return "'.$fetchPular['titulo'].'";
            }
            ';
          }
          ?>
        }

        function des(){
          var q = document.getElementsByClassName('qualidadeClass');
          for(var m = 0; m < q.length; m++){
            q[m].setAttribute('aria-checked','false');
          }
        }

        function changeOrientation(event){
          event.preventDefault();
          $('html,body').scrollTop(0);
          if(player.fullscreen.active){
            player.fullscreen.exit();
          } else {
            player.fullscreen.enter();
            screen.orientation.lock("landscape")
            .then(function() {
              if(!player.playing){
                player.play();
              }
            })
            .catch(function(error) {

            });  
          }

          
        }

        function getRandomInt(min, max) {
          min = Math.ceil(min);
          max = Math.floor(max);
          return Math.floor(Math.random() * (max - min)) + min;
        }

        function changeQuality() {
            var srcAll = `<?php echo $srcArray ?>`; //pegando o texto da variavel srcArray, que armazena os src de cada video
            var arraySrc = srcAll.split(" || "); //separando os src 
            var entrou = false;

            document.getElementById('qualidadeMenu').innerHTML = '';

            var sourcePlayer = document.getElementById('player').src;
            for (var i = 0; i < arraySrc.length - 1; i = i + 2) {
              if (sourcePlayer == arraySrc[i]) {
                entrou = true;
                j = 1;
                k = 0;
                l = 0;
                var tof;
                while (j != 0) {
                  k++;
                  if(k == 1){
                    tof = "true";
                  } else {
                    tof = "false";
                  }
                  document.getElementById('qualidadeMenu').innerHTML = document.getElementById('qualidadeMenu').innerHTML + `
                  <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control qualidadeClass" aria-checked="`+tof+`" onclick="var ct = document.getElementById('player').currentTime; des(); this.setAttribute('aria-checked','true'); document.getElementById('player').src = '`+(arraySrc[i+l])+`'; document.getElementById('player').currentTime = ct; document.getElementById('player').play();"><span>`+(k*360)+`p</span></button>
                  `;
                  l = l + 2;
                  if ((i + 1 + l) < arraySrc.length) {
                    j = arraySrc[i + 1 + l];
                  } else {
                    break;
                  }
                }
              }
            }
            if(!entrou){
              document.getElementById('qualidadeMenu').innerHTML = document.getElementById('qualidadeMenu').innerHTML + `
              <button data-plyr="speed" type="button" role="menuitemradio" class="plyr__control qualidadeClass" aria-checked="true"><span>360p</span></button>
              `;
            }

          }

        // Setup the player
        player = new Plyr('#player', {
          controls
        });

        function pularEpisodio() {
          parar = true;
          clearInterval(intervalo);
          document.getElementById("pularBtn").setAttribute('hidden', '');
          
          if(player.fullscreen.active){
            player.fullscreen.exit();
          }
          if(player.playing){
            player.pause();
          }
          document.getElementById('divVideo').setAttribute('hidden', '');
          $('#pulando').removeAttr('hidden');

          window.location.href = 'player.php?full=1&id=' + document.getElementById('proximoVideo').innerHTML;
        }

        setInterval(function() {
          try {
            document.querySelector('.plyr--hide-controls').style;
            document.getElementById('divControlsPersonalizado').setAttribute('hidden', '');


            if(document.getElementById('teste2').style.opacity == 0){
              document.getElementById('teste2').style.opacity = "0";
              document.getElementById('teste2').style.transition = "all 0s";
            }
            //out();
          }
          catch(err) {
            $('#divControlsPersonalizado').removeAttr('hidden');
            document.getElementById('teste2').style.opacity = "1";
            document.getElementById('teste2').style.transition = "all 0s";

          }
        }, 100);

        var pularEpUmaVez = true;
        var fundo = 0;

        function pularEpisodioDiv(){

          document.getElementById('pularEpisodioBtn').innerHTML = "Pular Episódio"+`<br><img style="border-radius:4px;" src="`+document.getElementById('thumbProximoVideo').innerHTML+`" width="200px"/><br><span style="font-size: 12px; font-weight: normal;">`+document.getElementById('nomeProximoVideo').innerHTML+`</span><br>`;
          document.getElementById('pularEpisodioBtn').style.opacity = '1';

          


          <?php if($pularAuto){ ?>
          //alert("removido");

          document.getElementById('spanPularEp').style.opacity = '1';
          if(pularEpUmaVez){
            document.getElementById('spanPularEp').innerHTML = '5';

            setTimeout(function(){
              document.getElementById('spanPularEp').innerHTML = '4';
            }, 1000);

            setTimeout(function(){
              document.getElementById('spanPularEp').innerHTML = '3';
            }, 2000);

            setTimeout(function(){
              document.getElementById('spanPularEp').innerHTML = '2';
            }, 3000);

            setTimeout(function(){
              document.getElementById('spanPularEp').innerHTML = '1';
            }, 4000);

            setTimeout(function(){
              if(document.getElementById('spanPularEp').style.opacity == '1' && document.getElementById('spanPularEp').innerHTML == '1'){
                pulara();
              }
            }, 5000);
          }
        <?php } ?>

          pularEpUmaVez = false;

        }

        var intervalo = setInterval(function() {
          var entrouPular = false;
          <?php
          $queryPular = mysqli_query($connection, "select * from pular where video_id_video = $id");
          while ($fetchPular = mysqli_fetch_array($queryPular)) {
            $comeco = $fetchPular['comeco'];
            $fim = $fetchPular['fim'];


            echo '
            
            if(player.currentTime >= ' . $comeco . ' && player.currentTime < ' . $fim . '){
              document.getElementById("pularBtn").innerHTML = "Pular " + getNomePular();
              entrouPular = true;
              
              if(getNomePular() == "Episódio"){
                document.getElementById("pularBtn").style.opacity="0";
                pularEpisodioDiv();
              } else {
                document.getElementById("pularEpisodioBtn").style.opacity = "0";
                document.getElementById("pularBtn").style.opacity="1";
              }
              over();
              if(player.playing){
                out10();  
              }
              ';

              if($pularAuto){
                echo "if(getNomePular() != 'Episódio'){pulara();}";
              }

              echo'
            }
            ';
          }
          ?>

          
          if(!entrouPular){
            document.getElementById("pularBtn").style.opacity="0";
            document.getElementById("pularEpisodioBtn").style.opacity = "0";
            document.getElementById('spanPularEp').style.opacity = '0';
          }
          

          if (document.getElementById("player").currentTime == document.getElementById("player").duration) {
            pularEpisodio();
          }

        }, 1000);

        //comeco ajax
        function update() {
          $.ajax({
            dataType: 'html',
            url: "atualizaTempo.php",
            type: "POST",
            data: ({
              tempo: $("input[name='tempo']").val(),
              usuario_id_usuario: $("input[name='usuario_id_usuario']").val(),
              idvideo: $("input[name='idvideo']").val()
            }),

            success: function(resposta) {
                    //alert(resposta);
                  },
                  error: function(xhr, status, error) {
                    //alert(xhr.responseText);
                  }

                });
        }
        //fim ajax
        
        <?php echo 'document.getElementById("player").src = "' . $srcDublado . '";'; ?>
        changeQuality();


        setInterval(function() {
          document.getElementById("tempo").value = document.getElementById("player").currentTime;
          update();
        }, 5000);
      </script>
    </body>

    </html>
    <?php
    if ($connection) {
      mysqli_close($connection);
    }
    ?>