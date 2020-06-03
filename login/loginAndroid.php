<?php
$useragent=$_SERVER['HTTP_USER_AGENT'];
if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
header('Location: index.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login V3</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--===============================================================================================-->	
	
	<!--===============================================================================================-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<!--===============================================================================================-->
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css" />

	<link rel="stylesheet" type="text/css" href="main.css">
	
</head>
<body style="overflow-x: hidden;">
	
	<div id="boxLogin">
		<div class="container-login100" style="background-image: url('fundo.png');">
			<div class="wrap-login100">
				<form class="login100-form validate-form" action="cadastrar.php" method="post" id="formulario">
					
					<span class="login100-form-title p-b-34 p-t-27" id="entrarLabel">
						Entrar
					</span>

					<input type="hidden" value="login" id="m" name="m">

					<div class="wrap-input100 validate-input" data-validate = "Digite seu nome" id="nomeDiv" style="display: none;visibility: hidden;">
						<input class="input100" type="text" name="nomeUsuario" placeholder="Nome" value="m" id="nomeUsuario">
						<span class="focus-input100" data-placeholder="&#xf207;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Digite seu nome">
						<input class="input100" type="text" name="email" placeholder="E-mail">
						<span class="focus-input100" data-placeholder="&#xf15a;"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Digite uma senha qualquer">
						<input class="input100" type="password" name="senha" placeholder="Senha">
						<span class="focus-input100" data-placeholder="&#xf191;"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" id="loginButton">
							Login
						</button>
					</div>

					<div class="text-center p-t-90" id="possuiOuNaoC">
						<span style="color: white;">Ainda não possui cadastro?</span> <a href="" style="color: #2ecc71;" id="buttonLoginToCadastro"
          class="buttonLoginToCadastro">Cadastrar</a>
					</div>
					<div class="text-center p-t-90" id="possuiOuNaoL" style="display: none;">
						<span style="color: white;">Já possui cadastro?</span> <a href="" style="color: #2ecc71;" id="buttonCadastroToLogin" class="buttonCadastroToLogin">Login</a>
					</div>

				</form>
			</div>
		</div>
	</div>


	

	
	
	<!--===============================================================================================-->
	
	<!--===============================================================================================-->
	
	<!--===============================================================================================-->
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>


	<script src="main.js"></script>
	<script>
		<?php
      if($_GET && isset($_GET['register']) && $_GET['register'] == 1){
        echo "document.getElementById('entrarLabel').innerHTML = 'Cadastrar';
		document.getElementById('possuiOuNaoC').style.display = 'none';
		document.getElementById('possuiOuNaoL').style.display = 'block';
		document.getElementById('loginButton').innerHTML = 'Cadastrar';
                document.getElementById('m').value = 'cadastrar';
                document.getElementById('nomeUsuario').value = '';
		var div = document.getElementById('nomeDiv');
		div.style.display = 'block';
		div.style.visibility = 'visible';";
      }
    ?>


    document.getElementById("buttonLoginToCadastro").addEventListener("click", function (event) {
      event.preventDefault();
      document.getElementById('entrarLabel').innerHTML = "Cadastrar";
      document.getElementById('possuiOuNaoC').style.display = 'none';
      document.getElementById('possuiOuNaoL').style.display = 'block';
	  document.getElementById('nomeUsuario').value = "";
	  document.getElementById('m').value = "cadastrar";
      document.getElementById('loginButton').innerHTML = "Cadastrar";
      var div = document.getElementById('nomeDiv');
	  div.style.display = 'block';
	  div.style.visibility = 'visible';

    });

    document.getElementById("buttonCadastroToLogin").addEventListener("click", function (event) {
      event.preventDefault();
	  document.getElementById('nomeUsuario').value = "m";
	  document.getElementById('m').value = "login";
      document.getElementById('entrarLabel').innerHTML = "Login";
      document.getElementById('possuiOuNaoL').style.display = 'none';
      document.getElementById('possuiOuNaoC').style.display = 'block';
      document.getElementById('loginButton').innerHTML = "Login";
      var div = document.getElementById('nomeDiv');
	  div.style.display = 'none';
	  div.style.visibility = 'hidden';

    });

	<?php
    if ($_GET && isset($_GET['erro']) && $_GET['erro'] == 1) {
      echo 'alert("Faça login antes de acessar um filme ou série");';
    }
    if ($_GET && isset($_GET['erro']) && $_GET['erro'] == 2) {
      echo 'alert("Usuário e/ou senha inválido(s)");';
    }

    ?>

  </script>
</body>
</html>