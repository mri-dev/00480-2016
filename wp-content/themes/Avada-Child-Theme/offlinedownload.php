<?php
  $termid = $wp_query->query_vars['utazas_id'];
  $ajanlat 	= new ViasaleAjanlat($termid, array('api_version' =>'v3'));

  if(!$ajanlat->getTravelID()) {
      wp_redirect(get_option('siteurl', '/'), 301);
      exit;
  }

  $params = array();

  if (isset($_GET['offer'])) {
    parse_str(base64_decode($_GET['offer']), $params);
  }

  // PDF Library
  require_once "includes/class/FPDF/tfpdf.php";

  class DownloadPDF extends tFPDF
  {
    private $param = array();
    function Header()
    {
      global $ajanlat;
        // Logo
        $this->Image(IFROOT.'/images/viasale-travel-logo-128x65.png',10,6,30);
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
          $this->SetTextColor(180);
          $this->Cell(40);
          $this->Cell(0, 8, $this->t($ztext), 0, 0, 'L');
          $this->Ln(18);
        endif;
    }

    function paramTable($header)
    {

        global $ajanlat;

        $first_col_w = 25;
        $value_col_w = 40;
        $this->SetXY( 135, 32 );

        // Param
        $this->SetFont('DejaVuSerif','B', 12);
        $this->setFillColor(140);
        $this->setTextColor(255);
        $this->Cell(0, 10, "Utazás adatai", 0, 0, "C", true);
        $this->Ln(13);

        $this->setTextColor(0);

        // Ellátás
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($first_col_w, 5, "Ellátás");
        $this->SetFont('DejaVuSerif','B');
        $this->Cell($value_col_w, 5, $this->param['term']['board']);
        $this->Ln(8);

        // Indulás ideje
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($first_col_w, 5, "Indulás");
        $this->SetFont('DejaVuSerif','B');
        $this->Cell($value_col_w, 5, $this->param['term']['date_from']);
        $this->Ln(8);

        // Érkezés ideje
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($first_col_w, 5, "Érkezés");
        $this->SetFont('DejaVuSerif','B');
        $this->Cell($value_col_w, 5, $this->param['term']['date_to']);
        $this->Ln(8);

        // Érkezés ideje
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($first_col_w, 5, "Azonosító");
        $this->SetFont('DejaVuSerif','B');
        $this->Cell($value_col_w, 5, '#'.$ajanlat->getTravelID());
        $this->Ln(8);

        // Ajánlat
        $this->Ln(5);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','B', 12);
        $this->setFillColor(140);
        $this->setTextColor(255);
        $this->Cell(0, 10, "Kiválasztott ajánlat", 0, 0, "C", true);
        $this->Ln(12);


        $this->setTextColor(0);

        // Szoba
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($value_col_w+$first_col_w, 5, "Szoba", 0, 0, "C");
        $this->Ln(5);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','B', 12);
        $this->MultiCell($value_col_w+$first_col_w, 5, $this->param['room']['name'], 0, "C");
        $this->Ln(8);

        // Felnőttek
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($value_col_w+$first_col_w, 5, "Utasok", 0, 0, "C");
        $this->Ln(5);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','B', 12);
        $this->MultiCell($value_col_w+$first_col_w, 5, $this->param['room']['people'], 0, "C");
        $this->Ln(8);

        // Ár
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 10);
        $this->Cell($value_col_w+$first_col_w, 5, "Utazás ára*", 0, 0, "C");
        $this->Ln(5);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','B', 12);
        $this->MultiCell($value_col_w+$first_col_w, 5, $this->param['room']['price'], 0, "C");
        $this->Ln(8);

        // Gener
        $this->setTextColor(80);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 8);
        $this->Cell($value_col_w+$first_col_w, 5, "Generálva", 0, 0, "C");
        $this->Ln(5);
        $this->setX(135);
        $this->SetFont('DejaVuSerif','', 8);
        $this->MultiCell($value_col_w+$first_col_w, 5, date('Y-m-d H:i'), 0, "C");
        $this->Ln(8);

    }

    public function setParam($p)
    {
      $this->param = (array)$p;
    }

    function t( $t = '' )
    {
      return iconv('UTF-8', 'windows-1252', $t);
    }
  }
  ///////////////////////////////////////////////////

  // Init
  $pdf = new DownloadPDF('P', 'mm', 'A4');
  $pdf->SetCreator('ViaSale Travel');
  $pdf->SetTitle('ViaSale Travel - Utazási ajánlat #'.$ajanlat->getTravelID(), true);

  $pdf->setParam($params);
  $pdf->AliasNbPages();

  // Content
  $pdf->AddPage();
  $pdf->AddFont('DejaVuSerif','','DejaVuSerif.ttf',true);
  $pdf->AddFont('DejaVuSerif','B','DejaVuSerif-Bold.ttf',true);



  // Ellátás
  $pdf->paramTable('Utazás paraméterek');
  $pdf->SetLineWidth(1);
  $pdf->SetDrawColor(220);
  $pdf->Line(132, 32.3, 132, 287);

  $pdf->SetLineWidth(0.4);
  $pdf->SetDrawColor(190);
  $pdf->Line(135, $pdf->getY(), 200, $pdf->getY());
  $pdf->Ln(4);
  $pdf->setX(135);
  $pdf->SetFont('DejaVuSerif','', 6);
  $pdf->SetTextColor(140);
  $pdf->MultiCell( 0, 3, '* tájékoztató jellegű. Az ár változhat a repülőjegy árváltozása esetén, így érdemes hamar lefoglalni!', 0, "L");

  $pdf->Ln(10);
  $pdf->setX(135);
  $pdf->SetFont('DejaVuSerif','B', 14);
  $pdf->SetTextColor(0);
  $pdf->MultiCell( 0, 3, '+36 (1) 445-4455', 0, "C");

  $pdf->Ln(5);
  $pdf->setX(135);
  $pdf->SetFont('DejaVuSerif','B', 10);
  $pdf->SetTextColor(0);
  $pdf->MultiCell( 0, 3, 'info@viasaletravel.hu', 0, "C");

  $pdf->Ln(8);
  $pdf->setX(135);
  $pdf->SetFont('DejaVuSerif','B', 14);
  $pdf->SetTextColor(0);
  $pdf->MultiCell( 0, 3, 'www.viasaletravel.hu', 0, "C");


  $pdf->setY(32);

  $desc = $ajanlat->getDescription( 'utazas' );

  $pdf->SetFont('DejaVuSerif','B', 12);
  $pdf->Cell( 120, 4, 'AJÁNLAT LEÍRÁSA', 0, "L");
  $pdf->Ln(10);

  if($desc)
  foreach ($desc as $did => $de):
    $des = $de['description'];
    $pdf->SetFont('DejaVuSerif','B', 9);
    $pdf->Cell( 120, 4, $de['name'], 0, "L");
    $pdf->Ln(5);
    $pdf->SetFont('DejaVuSerif','', 8);
    $pdf->MultiCell( 120, 4, $des);
    $pdf->Ln(5);
  endforeach;

  $pdf->Ln(5);
  $pdf->SetFont('DejaVuSerif','', 8);
  $pdf->Cell( 120, 4, '(i) '.$ajanlat->getHotelName().' szálloda leírása a következő oldalon', 0, "C");

  $pdf->AddPage();

  $desc = $ajanlat->getDescription( 'hotel' );

  $pdf->SetFont('DejaVuSerif','B', 12);
  $pdf->Cell( 120, 4, $ajanlat->getHotelName(). ' SZÁLLODA LEÍRÁSA', 0, "L");
  $pdf->Ln(10);

  if($desc)
  foreach ($desc as $did => $de):
    $des = $de['description'];
    $pdf->SetFont('DejaVuSerif','B', 9);
    $pdf->Cell( 0, 4, $de['name'], 0, "L");
    $pdf->Ln(5);
    $pdf->SetFont('DejaVuSerif','', 8);
    $pdf->MultiCell( 0, 4, $des);
    $pdf->Ln(5);
  endforeach;

  $pdf->Output('ViaSale Travel - Utazási ajánlat #'.$ajanlat->getTravelID().'.pdf', 'D');
  //$pdf->Output();
?>
