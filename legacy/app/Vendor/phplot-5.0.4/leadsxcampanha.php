<?php 
header('Content-type:image/png');
include 'phplot.php';
 $plot = new PHPlot(800,600);
//SetFileFormat("png");
$data = array(
        array('Jan', 20, 2, 4), array('Fev', 30, 3, 4), array('Mar', 20, 4, 14),
        array('Abr', 30, 5, 4), array('Mai', 13, 6, 4), array('Jun', 37, 7, 24),
        array('Jul', 10, 8, 4), array('Ago', 15, 9, 4), array('Set', 20, 5, 12),
        array('Out', 28, 4, 14), array('Nov', 16, 7, 14), array('Dez', 24, 3, 15),
        );

    $plot->SetImageBorderType('plain');
    $plot->SetPlotType('bars');
    $plot->SetDataType('text-data');
    $plot->SetDataValues($data);
    $plot->SetTitle('Cadastro de Usuários no Site');
    $plot->SetLegend(array('Estudantes','Colunistas', 'Desenvolvedores'));
    $plot->SetXTickLabelPos('none');
    $plot->SetXTickPos('none');
    $plot->DrawGraph();