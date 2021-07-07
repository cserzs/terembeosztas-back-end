<?php
namespace App\Libraries;

use TCPDF;

class MyTCPDF extends TCPDF
{

    //Page header
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 16);
        // Title
        $this->Cell(0, 15, 'Terembeosztás', 0, false, 'C', 0, '', 0, false, 'T', 'C');
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

class RoombindingExportManager
{
    private $classPerPage = 6;
    
    public function toPdf($osztalyok, $termek, $beosztas, $napok, $idopontok)
    {
        $classesPerPage = $this->getClassesPerPage($osztalyok, $this->classPerPage);
        
        $pdf = new MyTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Csernai Zsolt');
        $pdf->SetTitle('Terembeosztas');

        // set default header data
//        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->SetHeaderData('', 0, 'Terembeosztás', '', array(0, 0, 0), array(0, 0, 0));
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
        $pdf->SetFont('freeserif', '', 10);

        // Add a page
        // This method has several options, check the source code documentation for more information.

        $pdf->SetLineWidth(0.08);

        $cellwidth = 27.5; //mm
        $simavonal = array('width' => 0.08, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $szagatottvonal = array('width' => 0.08, 'cap' => 'butt', 'join' => 'miter', 'dash' => 2, 'color' => array(0, 0, 0));
        
        foreach($classesPerPage as $page)
        {
            $pdf->AddPage(); 
            
            $napid = 0;
            foreach($napok as $nap)
            {
                //  nap fejlec: hetfo   9.a   9.b
                $height = 0;
                if ($napid > 0) $height = 8;
                $pdf->SetFont('freeserif', 'B', 10);
//                $pdf->Cell(15, $height, $napok[$napid], 1, 0, 'C');
                $pdf->Cell(15, $height, $napok[$napid], 1, 0, 'C', false, '', 0, false, 'T', 'B');
                
                foreach($page as $osztaly)
                {
                    $pdf->Cell($cellwidth, $height, $osztaly['nev'], 1, 0, 'C', false, '', 0, false, 'T', 'B');
                }
                $pdf->Ln();
                
                $pdf->SetFont('freeserif', '', 10);
                foreach($idopontok as $idopont)
                {
                    // echo x. ora
                    $pdf->Cell(15, 5, $idopont . ".", 1, 0, 'C');
                    
                    foreach($page as $osztaly)
                    {
                        $num = count($beosztas[$osztaly['id']][$napid][$idopont]);
                        if ($num == 0)
                        {
                            $pdf->Cell($cellwidth, 5, '', array('LTRB' => $simavonal), 0, 'C');
                        }
                        else if ($num == 1)
                        {
                            $teremid = $beosztas[$osztaly['id']][$napid][$idopont][0];
                            $pdf->Cell($cellwidth, 5, $termek[$teremid]['rovid_nev'], array('LTRB' => $simavonal), 0, 'C');
                        }
                        else
                        {
                            $subwidth = $cellwidth / $num;
                            $elso = true;
                            for($i = 0; $i < $num; $i++)
                            {
                                $teremid = $beosztas[$osztaly['id']][$napid][$idopont][$i];
                                
                                if ($i == 0)
                                {
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], array('LTB' => $simavonal), 0, 'C');
                                }
                                else if ($i == $num - 1)
                                {
                                    $border = array('L' => $szagatottvonal,
                                                    'TRB' => $simavonal);
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], $border, 0, 'C');
                                }
                                else
                                {
                                    $border = array('L' => $szagatottvonal,
                                                    'TB' => $simavonal);
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], $border, 0, 'C');
                                }
                            }
//                            foreach($beosztas[$osztaly['id']][$napid][$idopont] as $teremid)
//                            {
//                                if ($elso)
//                                {
//                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], array('LTB' => $simavonal), 0, 'C');
//                                }
//                                else 
//                                {
//                                    $border = array('L' => $szagatottvonal,
//                                                    'TB' => $simavonal);
//                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], $border, 0, 'C');
//                                }
//                                $elso = false;
//                            }
                            
                        }
                    }
                    
                    $pdf->Ln();
                }
                
                $napid += 1;
            }
        }
        
        return $pdf;
    }

    private function getClassesPerPage($osztalyok, $classesPerPage)
    {
        $pages = array();
        $currentPage = 0;
        $counter = 0;
        foreach($osztalyok as $osz)
        {
            if ($counter >= $classesPerPage)
            {
                $currentPage += 1;
                $counter = 0;
            }
            
            if ( !isset($pages[$currentPage])) $pages[$currentPage] = array();
            $pages[$currentPage][] = $osz;
            $counter += 1;
        }
        return $pages;
    }
    
    public function toA3($osztalyok, $termek, $beosztas, $napok, $idopontok)
    {
        //  szelesseg 29,7
        //  magassag 42
        
        $classesPerPage = $this->getClassesPerPage($osztalyok, 10);
        
        $pdf = new \App\MyTCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'A3', true, 'UTF-8', false);
        
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Csernai Zsolt');
        $pdf->SetTitle('Terembeosztas');

        // set default header data
//        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));
        $pdf->SetHeaderData('', 0, 'Terembeosztás', '', array(0, 0, 0), array(0, 0, 0));
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

        $elsoOszlopSzelesseg = 20;
        
        $cellwidth = 25;//  27.5;
        $simavonal = array('width' => 0.08, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $szagatottvonal = array('width' => 0.08, 'cap' => 'butt', 'join' => 'miter', 'dash' => 2, 'color' => array(0, 0, 0));
        
        foreach($classesPerPage as $page)
        {
            $pdf->AddPage(); 
            
            $napid = 0;
            foreach($napok as $nap)
            {
                //  nap fejlec: hetfo   9.a   9.b
                $height = 0;
                if ($napid > 0) $height = 8;
                
                $pdf->SetFont('freeserif', 'B', 11);
                $pdf->Cell($elsoOszlopSzelesseg, $height, $napok[$napid], 1, 0, 'C', false, '', 0, false, 'T', 'B');
                
                foreach($page as $osztaly)
                {
                    $pdf->Cell($cellwidth, $height, $osztaly['nev'], 1, 0, 'C', false, '', 0, false, 'T', 'B');
                }
                
                $pdf->Ln();
                
                $pdf->SetFont('freeserif', '', 10);
                foreach($idopontok as $idopont)
                {
                    // echo x. ora
                    $pdf->Cell($elsoOszlopSzelesseg, 5, $idopont . ".", 1, 0, 'C');
                    
                    foreach($page as $osztaly)
                    {
                        $num = count($beosztas[$osztaly['id']][$napid][$idopont]);
                        if ($num == 0)
                        {
                            $pdf->Cell($cellwidth, 5, '', array('LTRB' => $simavonal), 0, 'C');
                        }
                        else if ($num == 1)
                        {
                            $teremid = $beosztas[$osztaly['id']][$napid][$idopont][0];
                            $pdf->Cell($cellwidth, 5, $termek[$teremid]['rovid_nev'], array('LTRB' => $simavonal), 0, 'C');
                        }
                        else
                        {
                            $subwidth = $cellwidth / $num;
                            $elso = true;
                            for($i = 0; $i < $num; $i++)
                            {
                                $teremid = $beosztas[$osztaly['id']][$napid][$idopont][$i];
                                
                                if ($i == 0)
                                {
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], array('LTB' => $simavonal), 0, 'C');
                                }
                                else if ($i == $num - 1)
                                {
                                    $border = array('L' => $szagatottvonal,
                                                    'TRB' => $simavonal);
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], $border, 0, 'C');
                                }
                                else
                                {
                                    $border = array('L' => $szagatottvonal,
                                                    'TB' => $simavonal);
                                    $pdf->Cell($subwidth, 5, $termek[$teremid]['rovid_nev'], $border, 0, 'C');
                                }
                            }
                        }
                    }
                    
                    $pdf->Ln();
                }
                
                $napid += 1;
            }
        }
        
        return $pdf;
    }
}