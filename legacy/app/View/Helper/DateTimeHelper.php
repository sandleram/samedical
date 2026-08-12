<?php

App::uses('Helper', 'View');

class DateTimeHelper extends Helper {

    public function birthday($data) {
        if ($data != '') {
            // Declara a data! :P
//        $data = $date;//29/08/2008
            // Separa em dia, mês e ano
            list($ano, $mes, $dia) = explode('-', $data);

            // Descobre que dia é hoje e retorna a unix timestamp
            $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
            // Descobre a unix timestamp da data de nascimento do fulano
            $nascimento = mktime(0, 0, 0, $mes, $dia, $ano);

            // Depois apenas fazemos o cálculo já citado :)
            $idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

            return $idade;
        } else {
            return '';
        }
    }

    public function dbToView($dateTime) {
        if ($dateTime != '') {
            $dateT = explode(" ", $dateTime);
            $dateArr = explode("-", $dateT[0]);
            $date = $dateArr[2] . '/' . $dateArr[1] . '/' . $dateArr[0];
            if($dateArr[0] == '0000'){
                return '';
            }else{
                return (isset($dateT[1])) ? $date . ' às ' . $dateT[1] : $date;
            }
        } else {
            return '';
        }
    }

    public function getNumberForText($dateTime) {
        if ($dateTime != '') {
            $dateT = explode("-", $dateTime);
            $dia_hora = explode(" ", $dateT[2]);
            $dia_horaArray = explode(":", $dia_hora[1]);

            $dia = $dia_hora[0];
            $mes = $dateT[1];
            $ano = $dateT[0];
            $hora = $dia_hora[1];

            if ($mes == 1)
                $mes = "Janeiro";
            if ($mes == 2)
                $mes = "Fevereiro";
            if ($mes == 3)
                $mes = "Março";
            if ($mes == 4)
                $mes = "Abril";
            if ($mes == 5)
                $mes = "Maio";
            if ($mes == 6)
                $mes = "Junho";
            if ($mes == 7)
                $mes = "Julho";
            if ($mes == 8)
                $mes = "Agosto";
            if ($mes == 9)
                $mes = "Setembro";
            if ($mes == 10)
                $mes = "Outubro";
            if ($mes == 11)
                $mes = "Novembro";
            if ($mes == 12)
                $mes = "Dezembro";
            return $dia . " de " . $mes . " de " . $ano . " às " . $dia_horaArray[0] . "h" . $dia_horaArray[1] . "m" . $dia_horaArray[2] . "s";
        } else {
            return '';
        }
    }

}
