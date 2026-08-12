<?php
//    header('Content-Type: text/html; charset=utf-8');
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-type:   application/x-msexcel; charset=utf-8");
    header("Content-Disposition: attachment; filename=sacademico_lista_".$this->params['controller']."_".date('d.m.Y_H.i').".xls"); 
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
    echo $this->fetch('content');
?>
