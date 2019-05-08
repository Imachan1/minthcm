<?php

//require_once('include/tcpdf/config/lang/eng.php');
require_once('include/tcpdf/tcpdf.php');

class MintPDF extends TCPDF {

   var $footer;
   var $header;

   function setHeaderBody($h) {
      $this->header = $h;
   }

   function setFooterBody($f) {
      $this->footer = $f;
   }

   function Header() {
      /* if($this->getPage()==$this->numpages){
        $this->writeHTML("<div>jakis footer</div>");
        }else */
      if ( $this->header != null ) {
         $this->writeHTMLCell(0, '', '', '', $this->header, 0, 1);
         //   $this->Ln('',ture);
      } else {
         parent::Header();
      }
   }

   function Footer() {
      global $current_user, $timedate;
      //  $userCurrentTime = time();
      //  $offset = $timedate->getUserTimeZone($current_user);
      $userCurrentDateforamat = $timedate->get_date_time_format($current_user);

      //$user_time = gmdate('Y-m-d H:i', $userCurrentTime); 
      // $userCurrentTime += $offset['gmtOffset']*60+$offset['dstOffset'] ;
      $curentDate = Date($userCurrentDateforamat);

      $pagenumtxt = $curentDate;
      $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      if ( $this->footer != null ) {
         $this->writeHTML($this->footer);
      } else {
         parent::Footer();
      }
      //$pagenumtxt = $this->l['w_page'].' '.$this->getAliasNumPage().' of '.$this->getAliasNbPages();
      //$this->writeHTML("<div>jakis footer  ".$pagenumtxt."</div>");
      //parent::Footer();
   }

   function printPageNO() {

      $pagenumtxt = $this->getAliasNbPages();
      //$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      $this->writeHTML($pagenumtxt, false);
   }

   function printPageCO() {

      $pagenumtxt = $this->getAliasNumPage();
      //$this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      $this->writeHTML($pagenumtxt, false);
   }

   function printPage($format) {

      $pagenumtxt = $this->l['w_page'] . ' ' . $this->getAliasNumPage() . " " . $format . "a " . $this->getAliasNbPages();
      $this->Cell(0, 0, $pagenumtxt, 'T', 0, 'L');

      //$this->writeHTML($p."  {pnb} / {nb}");
   }

}
