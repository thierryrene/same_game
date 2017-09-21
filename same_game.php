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

                $linhas = 5;
                $colunas = 5;
                $tdCount = 0;

                $acao = !empty($_REQUEST['acao']) ? $_REQUEST['acao'] : '' ;

                $clickL = !empty($_REQUEST['l']) ? $_REQUEST['l'] : 0 ;
                $clickC = !empty($_REQUEST['c']) ? $_REQUEST['c'] : 0 ;

                $coresArray = array(
                    "crimson",
                    "cornflowerblue",
                    "gold",
                    "purple"
                );

            if ($acao != 'click') {

                $tds = $linhas * $colunas;

                $coresMedia = ceil($tds / count($coresArray));

                $sortArray = array();            

                foreach ($coresArray as $cores) {
                    for ($a = 0; $a < $coresMedia; $a++) {
                        $sortArray[] = $cores;
                    }
                }

                shuffle($sortArray);

                $_SESSION['sortArray'] = $sortArray;

            } else {
            
                $sortArray  = $_SESSION['sortArray'];

                $finalArray = $_SESSION['finalArray'];

                echo "Click on line <b>" . $clickL . "</b> and column <b>" . $clickC . "</b><br><br>";               
                
                $colorClick = $finalArray[$clickL][$clickC];

                echo "<pre>";

                echo "Color: " . $colorClick;

                $markedArray = array();

                foreach($finalArray as $l => $linha) {
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

                            if($coluna == 1) {
                                
                                // verificando item de cima
                                if (($l - 1) >= 0) {

                                    // se celula está marcada
                                   if($markedArray[$l - 1][$c] != 1) {

                                        // verificando que celula é da mesma cor
                                        if ($colorClick == $finalArray[$l - 1][$c]) {
                                            $markedArray[$l -1][$c] = 1;
                                            $marked = true;
                                            $totFound++;
                                        }

                                   }                               
                                }

                                // verificando item da frente
                                if (($c + 1) < count($linha)) {

                                    // verifica se celula é da mesma cor
                                    if ($markedArray[$l][$c + 1] != 1) {

                                        if($colorClick == $finalArray[$l][$c + 1]) {
                                            $markedArray[$l][$c + 1] = 1;
                                            $marked = true;
                                            $totFound++;
                                        }
                                    }

                                }

                                // verificando item de baixo
                                if (($l + 1) < count($markedArray)) {

                                    // verificando célula marcada
                                    if ($markedArray[$l + 1][$c] != 1) {

                                        // verificando se celula é da mesma cor
                                        if($colorClick == $finalArray[$l + 1][$c]) {
                                            $markedArray[$l + 1][$c] = 1;
                                            $marked = true;
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
                                            $markedArray[$l][$c - 1] = 1;
                                            $marked = true;
                                            $totFound++;
                                        }
                                        
                                    }

                                }


                            }

                        }

                    }

                } while ($marked == true);              

            }

            echo "<hr>";

            echo $totFound;            

            $finalArray = array();


            // ##############################################################################################################
            // ##############################################################################################################
            // ##############################################################################################################


            for ($linha = 0; $linha < $linhas; $linha++) {

                echo "<tr>";

                    for ($coluna = 0; $coluna < $colunas; $coluna++) {

                        $finalArray[$linha][$coluna] = $sortArray[$tdCount];


                        echo "<td onclick='javascript: window.location=\"same_game.php?acao=click&l={$linha}&c={$coluna}\"' style='padding:20px;background-color: " . $sortArray[$tdCount] . ";'>";
                            echo $linha . ' | ' . $coluna;
                        echo "</td>";
                        $tdCount++;
                    }

                echo "</tr>";

            }

            $_SESSION['finalArray'] = $finalArray;

            ?>
            


        </table>

        <hr>

        <table border="1">

            <?php

                var_dump($markedArray);
                            
                foreach ($markedArray as $linha) {
                    
                    echo "<tr>";

                    foreach ($linha as $coluna) {
                    
                        echo "<td style='padding: 10px;'>{$coluna}</td>";                                       
                    
                    }

                    echo "</tr>";
                }




                // foreach ($markedArray[0] as $coluna){
                    
                // }
                // foreach ($markedArray[1] as $coluna){
                    
                // }
                // foreach ($markedArray[2] as $coluna){
                    
                // }
                // foreach ($markedArray[3] as $coluna){
                    
                // }
                // foreach ($markedArray[4] as $coluna){
                    
                // }

                // $coluna = $markedArray[4][0];
                // $coluna = $markedArray[4][1];
                // $coluna = $markedArray[4][2];
                // $coluna = $markedArray[4][3];
                // $coluna = $markedArray[4][4];

            ?>

        </table>

    </div>



    <div>
        


        <?php



        ?>
    </div>



</body>
</html>