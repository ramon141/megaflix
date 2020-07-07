<?php

require '../conexao.php';
if($_GET && isset($_GET['type'])){
	$tipo = substr($_GET['type'], 0, 1);



    //$dois = mysqli_query($connection, 'select * from obra where (titulo LIKE "%' . $_GET['q'] . '%" and sinopse LIKE "%' . $_GET['q'] . '%") and tipo="'.$tipo.'";'
    //$soTitulo = mysqli_query($connection, 'select * from obra where (titulo LIKE "%' . $_GET['q'] . '%" or sinopse LIKE "%' . $_GET['q'] . '%") and tipo="'.$tipo.'";'


	$queryResult = mysqli_query($connection, 'select * from genero_has_obra inner join obra on (obra.id_obra=genero_has_obra.obra_id_obra) inner join genero on (genero_has_obra.genero_id_genero=genero.id_genero) where genero_id_genero='.$_GET['id'].' and tipo="'.$tipo.'";');

} else {
	$queryResult = mysqli_query($connection, 'select * from genero_has_obra inner join obra on (obra.id_obra=genero_has_obra.obra_id_obra) inner join genero on (genero_has_obra.genero_id_genero=genero.id_genero) where genero_id_genero='.$_GET['id'].';');
}


$linkFormType = 'generos.php?id='.$_GET['id'].'&type=';

function verificar($id_obra){
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
<html>
<head>
	<title></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css" />
	<link rel="stylesheet" href="../stilo.css">
</head>
<?php require '../header.php'; ?>
<body style="background-image: url('../login/fundo.png'); background-color:rgba(39, 55, 71, 0.9); font-family: DSariW01-SemiBold;padding-left: 10px;">

	<div style="padding: 10px;">

		<table style="width:100%">
			<tr>
				<td>
					<span style="color: white;font-size: 20px;" id="spanNomeGenero"></span>
				</td>
				<td align="right">
					<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Filtrar
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
				width: 185px;
				background-size: 100%;
			}

			.item {

				background-color: rgba(39, 55, 71, 0.9);
				width: 185px;
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
				$soum = true;
				$quantReg = mysqli_num_rows($queryResult);
				
				while ($fetchResultado = mysqli_fetch_array($queryResult)) {
					if($soum){
						$soum = false;
						echo '<script type="text/javascript">document.getElementById("spanNomeGenero").innerHTML = "'.$quantReg.' Filmes e/ou Séries de '.$fetchResultado['nome'].'";</script>';
					}
					echo '<div class="col-sm-auto"><div class="item">
					<div class="showInfo" style="background-image: url(' . $fetchResultado['miniatura'] . ')">
					<div class="play">
					<a href="';


					if(strcmp($fetchResultado['tipo'], "F") == 0){
						echo 'filme';
					} else {
						echo 'serie';
					}

					echo '.php?id='.$fetchResultado['id_obra'].'"><img src="../play.png" alt="" width="75px" style="position: relative; top: 30%; left: 30%;"></a>
					</div>
					</div>
					<div style="padding: 10px; color: white;width:100%">
					<table style="width: 100%;padding-right: 10px;">
					<tr>
					<td>
					' . $fetchResultado['titulo'] . '
					</td>
					<td align="right">
					';

                    if (verificar($fetchResultado['id_obra'])) {//broken
                    	echo '<button title="Remover dos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchResultado['id_obra'].',this);" id="heart" style="border-radius: 22px; border-width: 0px;width: 30px;height: 30px;"><i class="fas fa-heart-broken"></i></button>';
                    } else {
                    	echo '<button title="Adicionar aos favoritos" class=" btn-floating btn-danger" onclick="addFavoritos('.$fetchResultado['id_obra'].',this);" id="heart" style="border-radius: 22px; border-width: 0px;width: 30px;height: 30px;"><i class="fas fa-heart"></i></button>';
                    }

                    
                    echo '
                    </td>
                    </tr>
                    </table>

                    </div>
                    </div><br></div>';
                }
                ?>


            </div>
        </div>


        <script type="text/javascript">
        	function addFavoritos(obra,elem) {
        		$.ajax({
        			dataType: 'html',
        			url: 'addFavoritos.php?id='+obra,
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

    </body>

    </html>