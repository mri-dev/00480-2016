<?php
  $termid = $wp_query->query_vars['utazas_id'];
  $ajanlat 	= new ViasaleAjanlat($termid);

  if(!$ajanlat->getTravelID()) {
      wp_redirect(get_option('siteurl', '/'), 301);
      exit;
  }

  // PDF Library
  define('FPDF_FONTPATH',"font/");
  require_once "includes/class/FPDF/tfpdf.php";

  class DownloadPDF extends tFPDF
  {
    function Header()
    {
      global $ajanlat;
        // Logo
        $this->Image(IMAGES.'/viasale-travel-logo-128x65.png',10,6,30);
        $this->SetFont('Arial','B', 18);
        $this->SetTextColor(247, 148, 29);
        $this->Cell(40);
        $this->Cell(0, 2, $this->t($ajanlat->getHotelName()).str_repeat('*', $ajanlat->getStar()), 0, 0, 'L');
        // Line break
        $this->Ln(5);

        $zones = $ajanlat->getHotelZones();
        if($zones):
          $ztext = '';
          foreach ($zones as $zid => $zona) {
            $ztext .= $zona.' / ';
          }
          $ztext = rtrim($ztext, ' / ');

          $this->SetFont('Arial','', 14);
          $this->SetTextColor(160);
          $this->Cell(40);
          $this->Cell(0, 8, $this->t($ztext), 0, 0, 'L');
        endif;
    }

    function t( $t = '' )
    {
      return iconv('UTF-8', 'windows-1252', $t);
    }
  }
  ///////////////////////////////////////////////////

  // Init
  $pdf = new DownloadPDF('P', 'mm', 'A4');
  $pdf->AliasNbPages();

  // Content
  $pdf->AddPage();
  // Add Unicode fonts (.ttf files)
  $fontName = 'Helvetica';

  $pdf->Ln(20);
  $pdf->SetFont($fontName,'',10);

  $desc = $ajanlat->getDescriptions();

  if($desc)
  foreach ($desc as $did => $de):
    $des = $de['description'];
    //$pdf->MultiCell( 160, 5, $des);

  endforeach;


  $pdf->Ln(10);

  $pdf->Output();

?>
