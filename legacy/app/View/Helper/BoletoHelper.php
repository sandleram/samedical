<?php

App::uses('Helper', 'View', 'Html');

class BoletoHelper extends Helper {

    var $helpers = array('Html', 'Session');

    function fbarcode($valor) {

        $fino = 1;
        $largo = 3;
        $altura = 50;

        $barcodes[0] = "00110";
        $barcodes[1] = "10001";
        $barcodes[2] = "01001";
        $barcodes[3] = "11000";
        $barcodes[4] = "00101";
        $barcodes[5] = "10100";
        $barcodes[6] = "01100";
        $barcodes[7] = "00011";
        $barcodes[8] = "10010";
        $barcodes[9] = "01010";
        for ($f1 = 9; $f1 >= 0; $f1--) {
            for ($f2 = 9; $f2 >= 0; $f2--) {
                $f = ($f1 * 10) + $f2;
                $texto = "";
                for ($i = 1; $i < 6; $i++) {
                    $texto .= substr($barcodes[$f1], ($i - 1), 1) . substr($barcodes[$f2], ($i - 1), 1);
                }
                $barcodes[$f] = $texto;
            }
        }


        //Desenho da barra
        //Guarda inicial
//        echo '<img src=imagens/p.png width="' . $fino . '" height="' . $altura . '" border=0>';
//        echo '<img src=imagens/b.png width="' . $fino . '" height="' . $altura . '" border=0>';
//        echo '<img src=imagens/p.png width="' . $fino . '" height="' . $altura . '" border=0>';
//        echo '<img src=imagens/b.png width="' . $fino . '" height="' . $altura . '" border=0>';
        
        echo $this->Html->image('boleto/p.png', array('width'=>$fino, 'height'=> $altura, 'border'=> 0));
        echo $this->Html->image('boleto/b.png', array('width'=>$fino, 'height'=> $altura, 'border'=> 0));
        echo $this->Html->image('boleto/p.png', array('width'=>$fino, 'height'=> $altura, 'border'=> 0));
        echo $this->Html->image('boleto/b.png', array('width'=>$fino, 'height'=> $altura, 'border'=> 0));


        $texto = $valor;
        if ((strlen($texto) % 2) <> 0) {
            $texto = "0" . $texto;
        }

        // Draw dos dadoss
        while (strlen($texto) > 0) {
            $i = round($this->esquerda($texto, 2));
            $texto = $this->direita($texto, strlen($texto) - 2);
            $f = $barcodes[$i];
            for ($i = 1; $i < 11; $i+=2) {
                if (substr($f, ($i - 1), 1) == "0") {
                    $f1 = $fino;
                } else {
                    $f1 = $largo;
                }

//                echo '<img src=imagens/p.png width="' . $f1 . '" height="' . $altura . '" border=0>';
                echo $this->Html->image('boleto/p.png', array('width'=>$f1, 'height'=> $altura, 'border'=> 0));



                if (substr($f, $i, 1) == "0") {
                    $f2 = $fino;
                } else {
                    $f2 = $largo;
                }

//                echo '<img src=imagens/b.png width="' . $f2 . '" height="' . $altura . '" border=0>';
                echo $this->Html->image('boleto/b.png', array('width'=>$f2, 'height'=> $altura, 'border'=> 0));
            }
        }

        // Draw guarda final
//        echo '<img src=imagens/b.png width="' . $largo . '" height="' . $altura . '" border=0>';
//        echo '<img src=imagens/b.png width="' . $fino . '" height="' . $altura . '" border=0>';
//        echo '<img src=imagens/b.png width="1" height="' . $altura . '" border=0>';
        echo $this->Html->image('boleto/p.png', array('width'=>$largo, 'height'=> $altura, 'border'=> 0));
        echo $this->Html->image('boleto/b.png', array('width'=>$fino, 'height'=> $altura, 'border'=> 0));
        echo $this->Html->image('boleto/p.png', array('width'=>1, 'height'=> $altura, 'border'=> 0));
    }
    
    function esquerda($entra,$comp){
            return substr($entra,0,$comp);
    }
    
    function direita($entra,$comp){
            return substr($entra,strlen($entra)-$comp,$comp);
    }
    

}
