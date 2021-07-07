<?php
namespace App\Libraries;

use TCPDF;

class MyTCPDF2 extends TCPDF
{

    //Page header
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 16);
        // Title
        $this->Cell(0, 15, 'Üres termek', 0, false, 'C', 0, '', 0, false, 'T', 'C');
    }

    // Page footer
    public function Footer()
    {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
//        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        $this->Cell(90, 10, date("Y.m.d"), 0, 0, 'L', false, '', 0, false);
        $this->Cell(0, 10, $this->getAliasNumPage().' / '.$this->getAliasNbPages(), 0, 0, 'R', false, '', 0, false);
    }
}

class EmptyroomsExportManager
{
    
    public function toPdf($termek, $urestermek, $napok, $idopontok)
    {
        $pdf = new MyTCPDF2(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Csernai Zsolt');
        $pdf->SetTitle('Terembeosztás: üres termek');

        // set default header data
//        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->SetHeaderData('', 0, 'Üres termek', '', array(0, 0, 0), array(0, 0, 0));
        $pdf->setFooterData(array(0, 0, 0), array(0, 0, 0));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
//        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetMargins(PDF_MARGIN_LEFT, 20, PDF_MARGIN_RIGHT);
//        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 15);


        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
//        $pdf->SetFont('times', '', 10, '', true);
        $pdf->SetFont('freeserif', '', 12);

        // Add a page
        // This method has several options, check the source code documentation for more information.

        $pdf->SetLineWidth(0.08);

        $pdf->AddPage(); 
        
        $simavonal = array('width' => 0.08, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        
        $napid = 0;
        foreach($napok as $nap)
        {
            $pdf->SetFont('freeserif', 'B', 12);
            $pdf->Cell(100, 0, $nap);
            $pdf->Ln();
            
            $pdf->SetFont('freeserif', '', 12);
            foreach($idopontok as $idopont)
            {
                $pdf->Cell(20, 0, $idopont . ". óra", array('T' => $simavonal));
                $s = "";
                $elso = true;
                foreach($urestermek[$napid][$idopont] as $teremid)
                {
                    if ( !$elso) $s .= ", ";
                    $elso = false;
                    $s .= $termek[$teremid]['nev'];
                }
                //    MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
                $pdf->MultiCell(150, 0, $s, array('T' => $simavonal), 'L', 0, 1, '', '', true);
            }

            $pdf->Ln();
            $napid += 1;
        }
        
        return $pdf;
    }

}