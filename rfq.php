<?php
/*
Plugin Name: Selecto
Plugin URI: http://cmscript.net
Description: Universal tool to collect users selections and number of options to utilize that data. Increasing significantly the site interactivity and usability.
Version: 1.1
Author: Dmitry Vadis <dmvadis@gmail.com> 
Author URI: -
Copyright: Copyright (C) Dmitry Vadis - All rights reserved worldwide 2011-2013, by the Dmitry Vadis.
License: GPL2.

Questions, suggestions and requests please address to info@cmscript.net.
*/
	if(!defined('_WPEXEC')){ define('_WPEXEC',1); }
	define( 'RFQ_VERSION', '1.0.1' );
	if(!defined('RFQ_PERMS')){ 	define('RFQ_PERMS', 0775); }

	if(!defined('RFQ_PATH')){ 		define('RFQ_PATH',plugin_dir_path(__FILE__)); }
	if(!defined('RFQ_URL')){ 		define('RFQ_URL',plugin_dir_url(__FILE__)); }
	if(!defined('HOME_URL')){ 		define('HOME_URL',home_url('/')); }
	if(!defined('RFQ_BASENAME')){ 	define('RFQ_BASENAME',plugin_basename(__FILE__)); }

	define('RFQ_FILE',__FILE__);
	if(!defined('DS')) { define('DS','/'); }
	
	if(version_compare(PHP_VERSION,'5.2','<')){
		if(is_admin()){
			require_once ABSPATH.'/wp-admin/includes/plugin.php';
			deactivate_plugins(__FILE__);
			wp_die(__( 'Selecto requires PHP 5.3 or higher, as does WordPress 3.3 and higher. The plugin has now disabled itself. ', 'rfq' ));
		} 
		else { return; }
	}
//------------------------------------------------------------------------------------------------------------------------
global $g_ftp;
global $g_ftpopt;
$g_ftp='';
$g_ftpopt='';

function rfq_install() {
add_option("rfq_admin_main", '', '', 'yes');
}

function rfq_remove() {
delete_option('rfq_admin_main');
}
//----------------------------------------------------------------------------------------------------------------------------------------------
function rfq_init() {
	$p_dir = RFQ_PATH.DS.'lang';
	load_plugin_textdomain( 'rfq', false, $p_dir );		
}

function rfq_frontend_init() {
	require RFQ_PATH . 'front/config.php';
	require RFQ_PATH . 'front/functions.php';
	require RFQ_PATH . 'front/rfq-srv.php';
}
function rfq_admin_init() {
	$t = str_replace(HOME_URL, '', RFQ_URL);
	$t = str_replace($t, '', RFQ_PATH);
	if(!defined('HOME_PATH')){ 	define('HOME_PATH', $t); }
	require RFQ_PATH . 'front/functions.php';
	require RFQ_PATH . 'admin/admin.php';
	require RFQ_PATH . 'admin/elements.php';
}
if ( is_admin() ) {
	add_action( 'plugins_loaded', 'rfq_admin_init');
	register_activation_hook(__FILE__,'rfq_install'); 
	register_deactivation_hook( __FILE__, 'rfq_remove' );
} else {
	add_action( 'plugins_loaded', 'rfq_frontend_init');
}
function rfq_deactivate() {
	rfq_flush_rules();
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	} else if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
}
function rfq_activate() {
	rfq_flush_rules();
	if ( function_exists( 'w3tc_pgcache_flush' ) ) {
		w3tc_pgcache_flush();
	} else if ( function_exists( 'wp_cache_clear_cache' ) ) {
		wp_cache_clear_cache();
	}
}
function rfq_flush_rules() {
	global $wp_rewrite;
	$wp_rewrite->flush_rules();
}
