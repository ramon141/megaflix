<?php 

session_start();
if($_SESSION && !isset($_SESSION['login'])){
	header("Location: /");
}


$isMobile = false;
$useragent = $_SERVER['HTTP_USER_AGENT'];
if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
	$isMobile = true;
}

if($_POST){
	if(!isset($_POST['audio'])){
		$audio = "L";
	} else {
		if(strcmp($_POST['audio'], "off") == 0){
			$audio = "L";
		} else {
			$audio = "D";
		}
	}
	if(!isset($_POST['pulo'])){
		$pulo = 0;
	} else {
		if(strcmp($_POST['pulo'], "on") == 0){
			$pulo = "1";
		} else {
			$pulo = "0";
		}
	}
	$sql = "UPDATE `usuario` SET `email`='".$_POST['email']."',`senha`='".md5($_POST['senha'])."',`nomeUsuario`='".$_POST['nome']."',`audioPreferencial`='".$audio."',`puloAuto`=".$pulo." WHERE id_usuario=".$_SESSION['idUsuario'];
	
	require '../conexao.php';
	if(mysqli_query($connection, $sql)){
		$success = true;
		$_SESSION['nomeUsuario'] = $_POST['nome'];
		$_SESSION['senha'] = $_POST['senha'];
		$_SESSION['email'] = $_POST['email'];
		$_SESSION['audio'] = $audio;
		$_SESSION['puloAuto'] = $pulo;
	} else {
		$success = false;
	}
	if($connection){mysqli_close($connection);}
	
}


?>

<!DOCTYPE html>
<html>
<head>
	<title>Configurações da Conta</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
	

	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	<link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
	<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
	<link rel="stylesheet" href="../stilo.css">
</head>
<body style="background-image: url('../login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold;padding-left: 10px;padding-right: 10px;">
	<?php require '../header.php'; ?>

	<script type="text/javascript">
		<?php 
			if(isset($success)){
				if($success == true){
					echo 'alert("As modificações foram alteradas corretamente!");';
				} else {
					echo 'alert("Infelizmente ocorreu um erro ao efetuar as alterações!\nPor favor, tente mais tarde");';
				}
			}

		 ?>

	</script>

	<br>
	<div style="background-color:rgb(39, 55, 71);border-radius: 10px; padding: 10px; color: white; <?php if($isMobile){echo 'width: 80%';}else{echo 'width: 50%';} ?>;margin: 0 auto;">
		<form class="form-horizontal" action="configAccount.php" method="post">
			<fieldset>

				<!-- Form Name -->
				
				<legend style="padding-left: 38%;">Configurações</legend>
				

				<!-- Text input-->
				<div class="form-group">
					<label class="col-md-12 control-label" for="nome">Nome</label>
					<div class="col-md-12">
						<input id="nome" name="nome" type="text" placeholder="Ramon" class="form-control input-md" required="" value="<?php echo $_SESSION['nomeUsuario']; ?>">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12 control-label" for="email">Email</label>
					<div class="col-md-12">
						<input id="email" name="email" type="email" placeholder="141barbosa@gmail.com" class="form-control input-md" required="" value="<?php echo $_SESSION['email']; ?>">
					</div>
				</div>

				<!-- Password input-->
				<div class="form-group">
					<label class="col-md-12 control-label" for="senha">Senha</label>
					<div class="col-md-12">
						<div class="input-group mb-2">
							<input type="password" class="form-control" id="showHideInput" placeholder="Senha" name="senha" required="" value="<?php echo $_SESSION['senha']; ?>">
							<div class="input-group-append">
								<button class="input-group-text" type="button" id="showHide"><i class="fas fa-eye"></i></button>
							</div>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12 control-label" for="audio">Audio Preferencial</label>
					<div class="col-md-12">
						<input type="checkbox" name="audio" id="audioToogle" checked data-toggle="toggle" data-on="Dublado" data-off="Legendado" data-onstyle="success" data-offstyle="danger">
					</div>
				</div>

				<div class="form-group">
					<label class="col-md-12 control-label" for="pulo">Pulo automático</label>  
					<div class="col-md-12">
						<input type="checkbox" name="pulo" id="puloToogle" checked data-toggle="toggle" data-on="Pular" data-off="Não pular" data-onstyle="danger" data-offstyle="success" >
					</div>
				</div>
				<br>
				<div class="form-group" style="width: 50%;margin: 0 auto;">
					<div class="col-md-12" style="width: 50%;margin: 0 auto;">
						<button type="submit" class="btn btn-primary" style="background-color: rgb(62, 87, 113); border-color: rgb(52, 58, 64);">Salvar</button>
					</div>
				</div>

			</fieldset>
		</form>
	</div>
	<br><br><br>


	<script type="text/javascript">
		$('#audioToogle').bootstrapToggle();
		$('#puloToogle').bootstrapToggle();

		<?php 
		if(strcmp($_SESSION['audio'], "L") == 0){
			echo "$('#audioToogle').bootstrapToggle('off');";
		}
		if($_SESSION['puloAuto'] == 0){
			echo "$('#puloToogle').bootstrapToggle('off');";
		}

		?>


		document.getElementById("showHide").addEventListener("click", function(event){
			event.preventDefault();
			if(document.getElementById('showHideInput').type == "text"){
				document.getElementById('showHideInput').type = 'password';
				document.getElementById('showHide').innerHTML = '<i class="fas fa-eye"></i>';
			} else {
				document.getElementById('showHideInput').type = 'text';
				document.getElementById('showHide').innerHTML = '<i class="fas fa-eye-slash"></i>';
			}
		});
	</script>
</body>
</html>