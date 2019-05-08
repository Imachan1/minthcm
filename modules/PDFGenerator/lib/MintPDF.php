<?php

if ( file_exists('custom/include/tcpdf/config/lang/eng.php') ) {
   require_once('custom/include/tcpdf/config/lang/eng.php');
} else {
   require_once('include/tcpdf/config/lang/eng.php');
}
if ( file_exists('custom/include/tcpdf/tcpdf.php') ) {
   require_once('custom/include/tcpdf/tcpdf.php');
} else {
   require_once('include/tcpdf/tcpdf.php');
}

class MintPDF extends TCPDF {

   protected $footer;
   protected $header;
   protected $pdf_font ='dejavusans';

   function setHeaderBody($h) {
      $this->header = $h;
   }

   function setFooterBody($f) {
      $this->footer = $f;
   }

   function Header() {
      if ( $this->header != null ) {
         $this->writeHTMLCell(0, '', '', '', $this->header, 0, 1);
      } else {
         parent::Header();
      }
   }

   function Footer() {
      global $current_user, $timedate;
      $userCurrentDateforamat = $timedate->get_date_time_format($current_user);
      $curentDate = Date($userCurrentDateforamat);
      $pagenumtxt = $curentDate;
     // $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
      if ( $this->footer != null ) {
        // $this->writeHTML($this->footer);
          $this->writeHTMLCell(0, '', '', '', $this->footer, 0, 1);
      } else {
         parent::Footer();
      }
   }

   function printPageNO() {
      $pagenumtxt = $this->getAliasNbPages();
      $this->writeHTML($pagenumtxt, false);
   }

   function printPageCO() {
      $pagenumtxt = $this->getAliasNumPage();
      $this->writeHTML($pagenumtxt, false);
   }

   function printPage($format) {
      $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . " " . $format . "a " . $this->getAliasNbPages();
      $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');
   }

   function setPdfParamsOutput($arr) {
      $this->SetCreator(PDF_CREATOR);

      $this->setHeaderFont(Array( $this->pdf_font, '', PDF_FONT_SIZE_MAIN ));
      $this->setFooterFont(Array( $this->pdf_font, '', PDF_FONT_SIZE_DATA ));

      $this->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

      $this->setImageScale(PDF_IMAGE_SCALE_RATIO);

      $this->setLanguageArray($l);//TODO COTO!
      $this->SetFont($this->pdf_font, '', 8);
      $bottom = $this->setPdfMarginsOutput($arr);
      $this->SetAutoPageBreak(TRUE, $bottom);
   }

   function setPdfMarginsOutput($arr) {
      if ( isset($_REQUEST['AMS_PDF_SET_NO_MARGINS']) && $_REQUEST['AMS_PDF_SET_NO_MARGINS'] == 1 ) {
         $this->SetHeaderMargin(0);
         $this->SetFooterMargin(0);
         $this->SetMargins(1, 1, 1, 1);
         $this->setCellPaddings(0, 0, 0, 0);
         $bottom = 0;
      } else {
         $this->SetHeaderMargin(PDF_MARGIN_HEADER);
         $this->SetFooterMargin(empty($arr['footer_h']) ? PDF_MARGIN_FOOTER : $arr['footer_h']);
         $bottom = empty($arr['footer_h']) ? PDF_MARGIN_BOTTOM : $arr['footer_h'] + 15;
         $top = empty($arr['header_h']) ? PDF_MARGIN_TOP : $arr['header_h'] + 15;
         $this->SetMargins(PDF_MARGIN_LEFT, $top, PDF_MARGIN_RIGHT);
      }
      return $bottom;
   }

}
