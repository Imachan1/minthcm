<?php

// View Tools #36858 START
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.detail.php');

class AOS_ProductsViewDetail extends ViewDetail {

   public function display() {
      $this->ss->assign('PRODUCT_IMAGE_SRC', $this->getProductImageSrc());
      parent::display();
   }

   protected function getProductImageSrc() {
      global $sugar_config;
      $file = '.' . str_replace($sugar_config['site_url'], "", $this->bean->product_image);
      if ( file_exists($file) ) {
         $size = getimagesize($file);
         $fp = fopen($file, 'rb');
         if ( $size && $fp ) {
            ob_start();
            fpassthru($fp);
            $image_data = ob_get_contents();
            ob_end_clean();
         }
      }
      return 'data: ' . $size['mime'] . ';base64,' . base64_encode($image_data);
   }

}

// View Tools #36858 END