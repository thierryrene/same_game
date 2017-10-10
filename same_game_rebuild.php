<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    require_once '../composer/vendor/autoload.php';    
}

// vamos utilizar a sessão para gravar um jogo iniciado e não perder o mesmo após o reload da página
session_start();

// infos para criar a matriz, quantidade de linhas e colunas
$linhas = 8;
$colunas = 8;

// ações baseadas em parametros da URL
$acao = !empty($_REQUEST['acao']) ? $_REQUEST['acao'] : '';
$clickLinha = !empty($_REQUEST['linha']) ? $_REQUEST['linha'] : 0;
$clickColuna = !empty($_REQUEST['coluna']) ? $_REQUEST['coluna'] : 0;

// lista de cores que vamos utilizar nos blocos
$listaCores = array('red', 'blue', 'green', 'black');

// definimos o que deve ser executado caso o valor da variavel acao seja diferente de click
if ($acao != 'click') {

	// total de células
	$totalCelulas = $linhas * $colunas;

	// média de cores
	$mediaCores = ceil($totalCelulas / count($listaCores));

	// definimos o array que vai armazenar o vetor de counter_reset()
	$sortArray = array();

	// aqui atribuimos os valores de cores ao vetor sortArray
	foreach ($listaCores as $cores) {
		for ($a = 0; $a <= $mediaCores; $a++) {
			$sortArray[] = $cores;
		}
	}

	// com o vetor criado baseado na média de cores, utilizamos o shuffle para randomizar os valores de cores
	shuffle($sortArray);

	// checkArray vai ser utilizado para marcar 
	$checkArray = array();

	// contador para localizar cada céclula do checkArray dentro do for
	$contadorCheckArray = 0;

	// agora criamos a matriz com o vetor de cores misturadas
	// aqui atribuímos cada valor do vetor de cores, a um item da matriz
	for($l = 1; $l <= $linhas; $l++) {
		for ($c = 1; $c <= $colunas; $c++) {
			$checkArray[$l][$c] = $sortArray[$contadorCheckArray];
			$contadorCheckArray++;
		}
	}

	// gravamos a matriz na session do PHP
	$_SESSION['checkArray'] = $checkArray;

} else {

	// se a ação for igual a click, atribuímos os valores da matriz gravada na sessão, na variável $checkArray
	// a intenção é recuperar o mesmo cenário, caso o jogador inicie clicando em alguma cor
	$checkArray = $_SESSION['checkArray'];

	// criamos a variável $blockClick para saber em qual item foi clicado. Aqui utilizamos os valores das variáveis clickLinha e clickColuna, que são atribuidos com base nas informações da URL
	$blockClick = $checkArray[$clickLinha][$clickColuna];	

	// novo array para guardar os pontos marcados
	$markedArray = array();

	// aqui definimos todos os itens da matriz mascara como null
	foreach($checkArray as $linha => $valorLinha) {
		foreach ($valorLinha as $coluna => $valorColuna) {
			$markedArray[$linha][$coluna] = null;
		}
	}

	// se alguma posição for clicada, definimos a posição como 1 na matriz mascara
	$markedArray[$clickLinha][$clickColuna] = 1;

	// contador de marcações dos blocos
	$contadorCellMarked = 1;

	// // através do DO / WHILE verificamos todos os itens em volta do bloco clicado
	do {

		$cellMarked = false;

		// percorremos as linhas da matriz
		foreach ($markedArray as $linha => $valorLinha) {
			
			// percorremos as colunas da matriz
			// a intenção aqui é encontrar outras células = 1
			foreach ($valorLinha as $coluna => $valorCelula) {
				
				// se o valor da célula for igual a 1
				if ($valorCelula == 1) {
					
					// verificando bloco de cima, a parti do bloco clicado
					if (($linha - 1) >= 0) {
						if ($markedArray[$linha - 1][$coluna] != 1 ) {
							if ($blockClick == $checkArray[$linha - 1][$coluna]) {
								$checkArray[$linha - 1][$coluna]  = '';
								$markedArray[$linha - 1][$coluna] = 1;
								$cellMarked 					  = true;
								$contadorCellMarked++;
							}
						}
					}

					// verificamos bloco da frente
					if (($coluna + 1) >= 0) {
						if ($markedArray[$linha][$coluna + 1] != 1) {
							if ($blockClick == $checkArray[$linha][$coluna + 1]) {
								$checkArray[$linha][$coluna + 1]  = '';
								$markedArray[$linha][$coluna + 1] = 1;
								$cellMarked 					  = true;
								$contadorCellMarked++;							
							}
						}
					}

					// verificamos bloco de baixo
					if (($linha + 1) >= count($linha)) {
						if ($markedArray[$linha + 1][$coluna] != 1) {
							if ($blockClick == $checkArray[$linha + 1][$coluna]) {
								$checkArray[$linha + 1][$coluna]  = '';
								$markedArray[$linha + 1][$coluna] = 1;
								$cellMarked 					  = true;
								$contadorCellMarked++;
							}
						}
					}

					// verificamos o bloco de trás do bloco clicado
					if (($coluna - 1) >= 0 ) {
						if($markedArray[$linha][$coluna - 1] != 1) {
							if($blockClick == $checkArray[$linha][$coluna - 1]) {
								$checkArray[$linha][$coluna - 1]  = '';
								$markedArray[$linha][$coluna - 1] = 1;
								$cellMarked 					  = true;
								$contadorCellMarked++;
							}
						}
					}

				}
			}
		}

	} while ($marked == true);


	if ($contadorCellMarked > 1) {

		$checkArray[$clickLinha][$clickColuna] = '';

		for ($linhas)


	}
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
</head>
<body>
	
	<div style="margin:0px auto;">

		<table border="1">
			
			<a href="same_game_rebuild.php">new game</a>

			<hr>

			<?php

				// apresentamos a matriz para o usuário, com os parametros baseados em cada linha e coluna da matriz
				// note que ao cliclar em um dos elementos, o parametro acao é alterado para click, que faz com que a matriz apresentada seja a matriz que foi salva na sessão
				// utilizamos aqui os indices e valores para gerar a matriz
				foreach ($checkArray as $linha => $valorLinha) {

					echo "<tr>";

						// o valor de cada linha também é um array, com as células dessa linha por coluna
						// aqui utilizamos o array de cada linha, para distribuir as células da tabela que vão compor a matriz
						foreach($valorLinha as $coluna => $valorColuna) {
							echo "<td onclick='javascript: window.location=\"same_game_rebuild.php?acao=click&linha={$linha}&coluna={$coluna}\"' style='padding: 16px; background-color:;'>{$valorColuna}</td>";
						}

					echo "</tr>";
				}

			?>	

		</table>

		<hr>

		<div>
			<?php

				// exibimos uma mensagem informando qual linha e coluna foram clicadas
				echo "Clicked on line <b>" . $clickLinha . "</b> and column <b>" . $clickColuna . "</b><br><br>";

				// exibimos o valor do $blockClick
				echo $blockClick;

			?>
		</div>		

		<hr>

		<div>
			
			<table border="1">
				<?php

					// reproduzimos a mask 
					foreach ($markedArray as $linha) {
						echo "<tr>";
							foreach ($linha as $coluna) {
								echo "<td style='padding: 10px;'>{$coluna}</td>";
							}
						echo "</tr>";
					}

				?>
			</table>

		</div>

	</div>

	<?php
		r($GLOBALS);
	?>

</body>
</html>

