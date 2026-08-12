<?php

App::import('Vendor', 'fpdf/fpdf');
 
class PDF extends FPDF {
 
    public $titulo;
    public $subtitulo;
 
    function Header() {
//        $this->Image('../webroot/img/logo.jpg', 10, 10, 40);
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(40);
        $this->Cell(30, 7, ("CABECALHO"));
        $this->Ln();
        $this->Cell(40);
        $this->Cell(30, 4, ($this->titulo));
        $this->Ln(10);
        $this->SetFont('Arial', '', 7);
        $this->Cell(50, 4, ($this->subtitulo), 0, "C");
        $this->Ln(5);
    }
 
    function Footer() {
        $this->SetY(-15);
        $this->Cell(0, 10, ( "RUA, NUMERO - BAIRRO"));
        $this->Ln(2.5);
        $this->Cell(0, 10, "CEP, ESTADO-CIDADE");
        $this->Ln(2.5);
        $this->Cell(0, 10, "CONTATO");
         
        $this->SetFont('Arial', 'I', 8);
        $this->AliasNbPages();
        $this->Cell(0, 10, ( 'Página ' ) . $this->PageNo() . ' de {nb}', 0, 0, 'R');
        $this->Ln(2.5);
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 10, 'Data: '. date('d-m-Y'));
    }
    
}