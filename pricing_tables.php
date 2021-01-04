<?php
/*
Plugin Name: CezarDEV Pricing Tables
Author: CezarDEV
Description: Create your own best looking pricing tables.
Text Domain: cd-pt0
Version: 0.0.9 (rc1)
 */

if(!defined('ABSPATH'))
   exit;

require_once( plugin_dir_path(__FILE__).'PriceTablePlugin.php');

register_activation_hook(  __FILE__, array('PriceTablePlugin', 'activation'));
register_deactivation_hook(__FILE__, array('PriceTablePlugin', 'deactivation'));
register_uninstall_hook(   __FILE__, array('PriceTablePlugin', 'uninstall'));

add_action('init',
   array('PriceTablePlugin', 'hook_init'));

add_action('add_meta_boxes',
   array('PriceTablePlugin', 'create_meta_box'));

add_action('admin_enqueue_scripts',
   array('PriceTablePlugin', 'add_admin_css_and_js'));

add_action('save_post',
   array('PriceTablePlugin', 'save_price_table_data'));

add_action('customize_register',
   array('PriceTablePlugin', 'customize_register'));

?>
