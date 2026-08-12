<?php

App::uses('Helper', 'View');

class MapsHelper extends Helper {
    /*http://jf.eti.br/calculando-distancia-entre-dois-pontos-com-php/
     * //Em milhas
      echo distancia(32.9697, -96.80322, 29.46786, -98.53506, "m") . " milhas<br />";
      //Em quilômetros
      echo distancia(32.9697, -96.80322, 29.46786, -98.53506, "k") . " Km<br />";
      //Milhas Nauticas
      echo distancia(32.9697, -96.80322, 29.46786, -98.53506, "n") . " Milhas Nauticas<br />";
     * 
     * EXAMPLE  (SANTA MARCELA, 98) PARA (DESENBARGADOR, 46)
     * echo $this->Maps->distancia(-23.5472079, -46.8001604, -23.570953, -46.6892997, "k") . " Km<br />";
     */

    function distancia($lat1, $lon1, $lat2 = '-23.570953', $lon2='-46.6892997', $unit = 'K') {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return round(($miles * 1.609344),3)." Km <br />";
        } else if ($unit == "N") {
            return ($miles * 0.8684)."Milhas Nauticas <br />";
        } else {
            return $miles."Milhas <br />";
        }
    }
    
    #BUSCA LONGITUDE E LATITUDE ATRAVÉS DO ENDEREÇO
    //http://battisti.etc.br/2010/03/13/como-localizar-latitude-e-longitude-de-um-endereco-api-google-maps/
    //https://gist.github.com/anselmobattisti/330990#file-geocode-php
    //http://battisti.etc.br/scripts/latitude/index.php
    
    


}
