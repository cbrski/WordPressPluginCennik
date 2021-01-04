<?php

if(!defined('ABSPATH'))
   exit;

class PriceTablePlugin {

   //slug in url for this plugin
   const PT_SLUG = 'cennik';
   const PT_POST_TYPE = 'cezardev_price_table';

   private static $plugin_fields = array(
      'color',
      'title',
      'subtitle',
      'price',
      'price_meta',
      'row_1',
      'row_2',
      'row_3',
      'row_4',
      'row_5',
      'button_label',
      'button_link'
   );

   /**
    * metoda aktywacyjna plugin
    */
   public static function activation() {
      //add_post_meta(self::PT_POST_TYPE, 'cd-pt0-pm-color', 'red', true);
   }

   /**
    * uzywane w haku aktywacyjnym do dodania obslugi custom_post_type
    * gdzie bedza przechowywane wszystkie cenniki w formie specjalnych postów
    * TO DO: kopiowanie single-szablonu do folderu aktywnego wp-content/szablonu
    */
   private static function create_post_type() {
      register_post_type(self::PT_POST_TYPE,
         array(
            'labels' => array(
               'name' => __('Cenniki', 'cd-pt0'),
               'singular_name' => __('Cennik', 'cd-pt0'),
               'add_new' => __('Dodaj nowy cennik', 'cd-pt0'),
               'add_new_item' => __('Dodaj nowy cennik', 'cd-pt0'),
               'edit_item' => __('Edytuj cennik', 'cd-pt0'),
               'new_item' => __('Nowy cennik', 'cd-pt0'),
               'view_item' => __('Wyświetl cennik', 'cd-pt0'),
               'search_items' => __('Znajdź cennik', 'cd-pt0'),
               'all_items' => __('Wszystkie cenniki', 'cd-pt0'),
               'not_found' => __('Nie znaleziono cennika', 'cd-pt0'),
            ),
            'public' => true,
            'menu_icon' => 'dashicons-cart',
            'supports' => array(
               'title',
            ),
            'rewrite' => array(
               'slug' => self::PT_SLUG
            )
         )
      );
   }

   /**
    * ZASLEPKA do wrzucenia meta danych dla wlasnie edytowanego custom postu
    * w panelu administracyjnym
    */
   public static function create_post_meta() {
      $args = array(
         'color' => 'blue',
         'title' => 'Zdjęcia do dokumentów',
         'subtitle' => 'ddd',
         'price' => '20',
         'price_meta' => '4 sztuki',
         'row_1' => 'dobre jakościowo',
         'row_2' => 'pod konkretny dokument',
         'row_3' => 'na miejscu',
         'row_4' => '',
         'row_5' => '',
         'button_label' => 'Zobacz galerię',
         'button_link' => '#',
      );
      global $post;
      update_post_meta($post->ID, '_cd-pt0-pm-array-all', $args);
   }

   /**
    * metoda rejestrujaca meta boxy w panelu administracyjnym
    */
   public static function create_meta_box() {
      //self::create_post_meta();

      add_meta_box(
         'meta-box-cd-pt0-mb-looks',
         __('Ten cennik wyróżniający się kolorem ... dotyczy ...', 'cd-pt0'),
         'PriceTablePlugin::meta_box_fill',
         self::PT_POST_TYPE,
         'normal',
         'default',
         array('id' => 'looks')
      );

      add_meta_box(
         'meta-box-cd-pt0-mb-price',
         __('Klient widzi, że zapłaci ... złotych za ... (tyle sztuk/taki okresz czasu)', 'cd-pt0'),
         'PriceTablePlugin::meta_box_fill',
         self::PT_POST_TYPE,
         'normal',
         'default',
         array('id' => 'price')
      );

      add_meta_box(
         'meta-box-cd-pt0-mb-details',
         __('Co Klient otrzyma w zamian?', 'cd-pt0'),
         'PriceTablePlugin::meta_box_fill',
         self::PT_POST_TYPE,
         'normal',
         'default',
         array('id' => 'details')
      );

      add_meta_box(
         'meta-box-cd-pt0-mb-link',
         __('Jeżeli Klient chce poznać szczegóły ... (dotyczy przycisku)', 'cd-pt0'),
         'PriceTablePlugin::meta_box_fill',
         self::PT_POST_TYPE,
         'normal',
         'default',
         array('id' => 'link')
      );

      add_meta_box(
         'meta-box-cd-pt0-mb-view_shortcode',
         __('Shortcode', 'cd-pt0'),
         'PriceTablePlugin::current_shortcode_fill',
         self::PT_POST_TYPE,
         'side',
         'low'
      );

   }

   /**
    * metoda zwracajaca domyslne meta wartosci
    * w formie tablicy asocjacyjnej
    */
   private static function meta_box_defaults() {
      $meta = array();
      $meta['color']          = '';
      $meta['title']          = '';
      $meta['subtitle']       = '';
      $meta['price']          = '';
      $meta['price_meta']     = '';
      $meta['button_label']   = '';
      $meta['button_link']    = '';
      for($i=1;$i<6;$i++)
         $meta['row_'.$i]     = '';

      return $meta;
   }

   /**
    * metoda uzupelniajacy poszczegolne meta boxy w zaleznosci jak sie
    * przedstawia roznymi polami wejsciowym
    */
   public static function meta_box_fill($post, $args) {
      global $post;
      $meta = get_post_meta($post->ID, '_cd-pt0-pm-array-all', true);
      if(empty($meta)) {
         $meta = self::meta_box_defaults();
      }
      $output = '';
      switch($args['args']['id']) {


         case 'looks';

         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Kolor:</p>
            </div>'; 
         $output.= 
            '<input class="pt-color-field" type="text"
            name="cd-pt0-color" value="'.$meta['color'].'"/>
            </div>'; 


         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Tytuł:</p>
            </div>'; 
         $output.= '
            <input class="pt-input-field" type="text"
            name="cd-pt0-title" value="'.$meta['title'].'"/> 
            </div>'; 


         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Podtytuł (dla wyróżnienia):</p>
            </div>'; 
         $output.=
            '<input class="pt-input-field" type="text"
            name="cd-pt0-subtitle" value="'.$meta['subtitle'].'"/></p> 
            </div>'; 
         break;




         case 'price';

         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Cena:</p>
            </div>'; 
         $output.= '
            <input class="pt-input-field" type="text"
            name="cd-pt0-price" value="'.$meta['price'].'"/> 
            </div>'; 


         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Info za ceną:</p>
            </div>'; 
         $output.= '
            <input class="pt-input-field" type="text"
            name="cd-pt0-price_meta" value="'.$meta['price_meta'].'"/> 
            </div>'; 
         break;



         case 'details';

         for($i=1;$i<6;$i++) {

            $output.= 
               '<div class="full-container">
               <div class="pt-paragraph-container">
               <p class="pt-paragraph">'.$i.':</p>
               </div>'; 
            $output.= '
               <input class="pt-input-field" type="text"
               name="cd-pt0-row_'.$i.'" value="'.$meta['row_'.$i].'"/> 
               </div>';
         } 
         break;




         case 'link';

         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Zachęć go tekstem:</p>
            </div>'; 
         $output.= '
            <input class="pt-input-field" type="text"
            name="cd-pt0-button_label" value="'.$meta['button_label'].'"/> 
            </div>'; 

         $output.= 
            '<div class="full-container">
            <div class="pt-paragraph-container">
            <p class="pt-paragraph">Przekieruj go do:</p>
            </div>'; 
         $output.= '
            <input class="pt-input-field" type="text"
            name="cd-pt0-button_link" value="'.$meta['button_link'].'"/> 
            </div>'; 
         break;


      }
      echo '<div class="pt-flex-container">'.$output.'</div>';
      static $is_nonce_displayed = false;
      if(!$is_nonce_displayed) {
         wp_nonce_field(plugin_basename(__FILE__), 'cd-pt0-meta-box-nonce');
      }
      $is_nonce_displayed = true;
   }

   public static function current_shortcode_fill() {
      global $post;
      $id = $post->ID;
      echo '<p style="font-weight: bold; border: 1px solid red; padding: 4px;">['.self::PT_POST_TYPE.' id="'.$id.'"]</p>
         <p>Po opublikowaniu cennika skopiuj powyższy shortcode i wklej go w miejscu gdzie niniejszy cennik ma się pojawić.</p>';
   }

   /**
    * metoda dezaktywacyjna plugin
    */
   public static function deactivation() {

   }

   /**
    * metoda ktora czysci zasoby podczas usuwania
    * plugina z instalacji uzytkownika
    * TO DO: usuwanie single-szablonu z folderu aktywnego wp-content/szablonu
    */
   public static function uninstall() {

   }

   /**
    * metoda obslugujaca hak 'init'
    */
   public static function hook_init() {
      self::create_post_type();
      add_shortcode(self::PT_POST_TYPE, array('PriceTablePlugin', 'generate_price_table')); 
   }

   /*
    * generowanie pojedynczego cennika za pomoca
    * szablonu w folderze templates/
    */
   public static function generate_price_table(
      $atts = [], $content = null, $tag = '')
   {
      global $post;
      $currentID = false;
      if(!empty($atts) && array_key_exists('id', $atts)) {
         $currentID = $atts['id'];
      }
      elseif($post->post_type == self::PT_POST_TYPE) {
         $currentID = $post->ID;
      }
      else {
         return '';
      }

      $m = get_post_meta($currentID, '_cd-pt0-pm-array-all', true);

      require(__DIR__.'/templates/price_table.php');
      return $opt;
   }

   /*
    * dodanie styli oraz javascriptu do backendu
    * dodanie ColorPicker'a do wybierania koloru w backendzie
    */
   public static function add_admin_css_and_js() {
      wp_enqueue_style(
         'style'.self::PT_POST_TYPE,
         //__DIR__.'/pricing_tables_style.css'
         plugins_url('pricing_tables_style.css', __FILE__)
      );
      wp_enqueue_style('wp-color-picker');
      wp_enqueue_script(
         'script'.self::PT_POST_TYPE,
         plugins_url('pricing_tables_script.js', __FILE__),
         array('wp-color-picker'),
         false,
         true
      );
   }

   /*
    * saving custom fields
    */
   public static function save_price_table_data($post_id) {
      if( isset($_POST['cd-pt0-color']) ) {
         if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
         }

         wp_verify_nonce(plugin_basename(__FILE__), 'cd-pt0-meta-box-nonce');

         //global $post;
         //$pid = $post->ID;
         $to_save = array();
         foreach(self::$plugin_fields as $key => $value) {
            $to_save[$value] = 
               sanitize_text_field($_POST['cd-pt0-'.$value]);
         }
         //$to_save = false;
         update_post_meta($post_id, '_cd-pt0-pm-array-all', $to_save);
      }
   }

   public static function customize_register( $wp_customize ) {
      $wp_customize->add_section( 'f93_price_table', 
         array(
            'priority' 	      => 29,
            'capability'      => 'edit_theme_options',
            'theme_supports'  => '',
            'title'	      => __('(+) Cenniki', 'zerif-lite-child'),
            'description'     => 
               __('Pokaż cenniki które chcesz wyrónić na stronie głównej.',
                  'zerif-lite-child')
         )
      );

      //big title text color
      $wp_customize->add_setting( 'f93_price_table_shortcodes', array(
         'type'		=> 'theme_mod',
         'default'	=> '',
         //'sanitize_callback'=> 'sanitize_hex_color'
         )
      );


      $wp_customize->add_control(
         new WP_Customize_Control(
            $wp_customize,
            'f93_price_table_shortcodes',
            array(
               'label'          => __( 'Shortcodes', 'zerif-lite-child' ),
               'section'        => 'f93_price_table',
               'settings'       => 'f93_price_table_shortcodes',
               'type'           => 'textarea',
               )
            )
         );

   }

}

?>
