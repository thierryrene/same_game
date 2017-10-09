<?php

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    require_once '../composer/vendor/autoload.php';    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>

    <div align="center" style="margin-top:80px;">

        <h1>same game</h1>

        <p><a href="same_game.php">new game</a></p>

        <br>

        <table border="1">

            <?php

                session_start();

                // número de linhas
                $linhas = 8;

                // número de colunas
                $colunas = 8;   

                // número de células
                $tdCount = 0;                

                // verificação se existe o parametro acao na url
                $acao = !empty($_REQUEST['acao']) ? $_REQUEST['acao'] : '';

                // verificamos se existe o parametro L na url
                $clickL = !empty($_REQUEST['l']) ? $_REQUEST['l'] : 0;

                // verificamos se existe o parametro C na url
                $clickC = !empty($_REQUEST['c']) ? $_REQUEST['c'] : 0;

                // cores dos blocos
                $coresArray = array(
                    "crimson",
                    "cornflowerblue",
                    "gold",
                    "purple",
                );

                // array
                $finalArray = array();

                // se a ação for diferente de click, geramos um novo jogo (matriz)
                if ($acao != 'click') {

                    // número de células
                    $tds = $linhas * $colunas;

                    // media de cores | vamos utilizar a média de cores para sortear as cores que serão preenchidas na matriz
                    $coresMedia = ceil($tds / count($coresArray));

                    // definimos um array
                    $sortArray = array();

                    // aqui populamos o $sortArray com a média de cores
                    foreach ($coresArray as $cores) {
                        for ($a = 0; $a < $coresMedia; $a++) {
                            $sortArray[] = $cores;
                        }
                    }                    

                    // randomizamos a matriz, mantendo o índice
                    shuffle($sortArray);

                    r($sortArray);

                    $tot = 0;

                    for ($l = 0; $l < $linhas; $l++) {
                        for ($c = 0; $c < $colunas; $c++) {
                            $finalArray[$l][$c] = $sortArray[$tot];
                            $tot++;
                        }
                    }

                    unset($sortArray);

                    // colocamos o array na sessão
                    $_SESSION['finalArray'] = $finalArray;

                    // contador de pontos
                    $_SESSION['points'] = 0;

                } else {

                    // colocamos o array da session na variável $finalarray
                    $finalArray = $_SESSION['finalArray'];

                    //
                    echo "Clicked on line <b>" . $clickL . "</b> and column <b>" . $clickC . "</b><br><br>";

                    // identificamos a posição e cor do click
                    $colorClick = $finalArray[$clickL][$clickC];

                    // printamos a cor clicada
                    echo "Color: " . $colorClick;

                    // definimos um novo array para registrar os pontos marcados
                    $markedArray = array();

                    //
                    foreach ($finalArray as $l => $linha) {
                        foreach ($linha as $c => $coluna) {
                            $markedArray[$l][$c] = null;
                        }
                    }

                    // marca posição clicada
                    $markedArray[$clickL][$clickC] = 1;

                    $totFound = 1;

                    do {

                        $marked = false;

                        // percorre linhas da matriz
                        foreach ($markedArray as $l => $linha) {

                            // percorre colunas
                            foreach ($linha as $c => $coluna) {

                                if ($coluna == 1) {

                                    // verificando item de cima
                                    if (($l - 1) >= 0) {

                                        // se celula está marcada
                                        if ($markedArray[$l - 1][$c] != 1) {

                                            // verificando que celula é da mesma cor
                                            if ($colorClick == $finalArray[$l - 1][$c]) {
                                                $finalArray[$l - 1][$c]  = 'transparent';
                                                $markedArray[$l - 1][$c] = 1;
                                                $marked                  = true;
                                                $totFound++;
                                            }

                                        }
                                    }

                                    // verificando item da frente
                                    if (($c + 1) < count($linha)) {

                                        // verifica se celula é da mesma cor
                                        if ($markedArray[$l][$c + 1] != 1) {

                                            if ($colorClick == $finalArray[$l][$c + 1]) {
                                                $finalArray[$l][$c + 1]  = 'transparent';
                                                $markedArray[$l][$c + 1] = 1;
                                                $marked                  = true;
                                                $totFound++;
                                            }
                                        }

                                    }

                                    // verificando item de baixo
                                    if (($l + 1) < count($markedArray)) {

                                        // verificando célula marcada
                                        if ($markedArray[$l + 1][$c] != 1) {

                                            // verificando se celula é da mesma cor
                                            if ($colorClick == $finalArray[$l + 1][$c]) {
                                                $finalArray[$l + 1][$c]  = 'transparent';
                                                $markedArray[$l + 1][$c] = 1;
                                                $marked                  = true;
                                                $totFound++;
                                            }
                                        }
                                    }

                                    // verificando item de tras
                                    if (($c - 1) >= 0) {

                                        // verificando celula marcada
                                        if ($markedArray[$l][$c - 1] != 1) {

                                            // verificando se a cor da celula é a mesma
                                            if ($colorClick == $finalArray[$l][$c - 1]) {
                                                $finalArray[$l][$c - 1]  = 'transparent';
                                                $markedArray[$l][$c - 1] = 1;
                                                $marked                  = true;
                                                $totFound++;
                                            }

                                        }

                                    }

                                }

                            }

                        }

                    } while ($marked == true);

                    // verificamos se itens também estão marcados caso a cor encontrada seja maior que 1
                    if ($totFound > 1) {

                        $_SESSION['points'] += pow($totFound - 1, 2);

                        $finalArray[$clickL][$clickC] = 'transparent';

                        // marcar células marcadas como transparente
                        for ($l = $linhas - 1; $l > 0; $l--) {

                            foreach ($finalArray[$l] as $c => $corAtual) {

                                // verificando se célula abaixo é transparent ou está marcado
                                if ($corAtual == 'transparent') {
                                    $achou = false;
                                    $vL    = $l;
                                    do {

                                        $vL--;

                                        if ($finalArray[$vL][$c] != 'transparent') {
                                            // setamos a celula atual como cor da celula acima
                                            $finalArray[$l][$c]  = $finalArray[$vL][$c];
                                            $finalArray[$vL][$c] = 'transparent';
                                            $achou               = true;
                                        }

                                        if ($vL == 0) {
                                            $achou = true;
                                        }

                                    } while (!$achou);

                                }

                            }

                        }

                        echo "<hr>";

                        // mover coluna transparent para esquerda
                        // for é executado até o $l ficar menor que 0
                        
                        // percorro ultima linha da matriz
                        foreach ($finalArray[$linhas - 1] as $k => $v) {

                            // se algum valor na ultima linha for transparent
                            echo $v . "|";

                            // 
                            if ($finalArray[$linhas - 1][$k] == 'transparent' && ($k + 1) < $colunas ) {

                                // perconrrendo todas as linhas da matriz
                                foreach ($finalArray as $l => $linha) {
                                    $achou = false;
                                    $vC = $k;

                                    do {
                                        $vC++;

                                        if ($finalArray[$linhas - 1][$vC] != 'transparent') {
                                            // atribui valor da proxima coluna na coluna atual
                                            $finalArray[$l][$k] = $finalArray[$l][$vC];

                                            // define valor da proxima coluna como transparent
                                            $finalArray[$l][$vC] = 'transparent';
                                            $achou = true;
                                        }

                                        if($vC == $linhas - 1) {
                                            $achou = true;
                                        }

                                    } while (!$achou);

                                    
                                }

                            }

                        }

                    }

                    $_SESSION['finalArray'] = $finalArray;

                }

                echo "<hr>";

                echo $totFound;

                // ##############################################################################################################
                // ##############################################################################################################
                // ##############################################################################################################

                foreach ($finalArray as $linha => $myLinha) {
                    echo "<tr>";

                    foreach ($myLinha as $coluna => $myColuna) {
                        echo "<td onclick='javascript: window.location=\"same_game.php?acao=click&l={$linha}&c={$coluna}\"' style='padding:20px;background-color: " . $myColuna . ";'>";

                        echo "</td>";
                    }

                    echo "</tr>";

                }

                ?>

        </table>

        <!-- <hr> 

        <table border="1">

            <?php
                var_dump($finalArray);
                foreach ($finalArray as $linha) {
                    echo "<tr>";
                    foreach ($linha as $coluna) {
                        echo "<td style='padding: 10px;'>{$coluna}</td>";
                    }
                    echo "</tr>";
                }

            ?>

        </table> -->


    </div>

    <div align="center">

        <br>

        <?php

            echo "Pontos marcados: " . $_SESSION['points'];

        ?>

    </div>

</body>
</html>