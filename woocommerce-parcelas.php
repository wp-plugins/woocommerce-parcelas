<?php
/*
* Plugin Name: WooCommerce Parcelas
* Plugin URI: https://github.com/filiprimo/woocommerce-parcelas
* Description: Adiciona quantidade de parcelas e o valor de cada parcela, nas páginas que listam todos os produtos e na página individual de cada produto. Suporta valor mínimo para cada parcela. Totalmente compatível com produto variável.
* Author: Filipe Seabra
* Author URI: http://www.filipecsweb.com.br/
* Version: 1.2.2
* License: GPLv2 or later
* Text Domain: woocommerce-parcelas
* Domain Path: lang/
*/

if(!defined('ABSPATH')){
	exit;
}

define('WOO_PARCELAS_DIR', plugin_dir_path(__FILE__));
define('WOO_PARCELAS_URL', plugin_dir_url(__FILE__));

/* Load textdomain */
function load_fswp_texdomain(){
	load_plugin_textdomain('woocommerce-parcelas', false, dirname(plugin_basename(__FILE__)).'/lang/');
}
add_action('plugins_loaded', 'load_fswp_texdomain');

/* Load .js */
function load_fswp_scripts(){
	wp_enqueue_script('woocommerce-parcelas-admin', WOO_PARCELAS_URL.'assets/js/admin.js', 'jquery', false, false);
	// wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );

	wp_enqueue_style('woocommerce-parcelas-admin', WOO_PARCELAS_URL.'assets/css/admin.css', '', false);
	// wp_enqueue_style( $handle, $src, $deps, $ver, $media );
}
add_action('admin_enqueue_scripts', 'load_fswp_scripts');

require_once(WOO_PARCELAS_DIR.'woocommerce-parcelas-options.php');

if(isset($fs_options['fswp_ativar']) && $fs_options['fswp_ativar'] == '1'){
	require_once(WOO_PARCELAS_DIR.'woocommerce-parcelas-action.php');
}