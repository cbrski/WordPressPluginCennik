<?php

/*
 * widoczne sa wszystkie zmienne z metody
 * PriceTablePlugin::generate_price_table()
 */

if(!defined('ABSPATH'))
   exit;

$opt = '';
$opt.='<div class="package-box-wrap col-lg-3 col-md-6 col-sm-6 col-xs-12">';

if( !empty($m['subtitle']) ): 
   $opt.='<div class="best-value">';
endif;

$opt.='<div class="package">'.
      '<div class="package-header" style="background: '.$m['color'].';">'.
      '<h4>'.$m['title'];

if( !empty($m['subtitle']) ):
   $opt.='<div class="meta-text">'.$m['subtitle'].'</div>';
endif;

$opt.='</div>'.
      '<div class="price dark-bg">'.
         '<div class="price-container">'.
               '<h4><span class="dollar-sign">PLN </span>'.$m['price'].'</h4>'.
               '<span class="price-meta">/'.$m['price_meta'].'</span>'.
         '</div>'.
      '</div>'.
   '<ul>';

$i = 1;
while( isset($m['row_'.$i]) && !empty($m['row_'.$i]) ) {
   $opt.='<li>'.$m['row_'.$i++].'</li>';
}

$opt.='</ul>';

if( !empty($m['button_link']) && !empty($m['button_label'])):
      $opt.='<a target="_self" href="'.$m['button_link'].'"
      class="btn btn-primary custom-button"
      style="background: '.$m['color'].'">'.$m['button_label'].'</a>';
endif;

if( !empty($m['subtitle']) ):
   $opt.='</div>';
endif;

$opt.='</div>'.
   '</div>';

?>
