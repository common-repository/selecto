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

defined('_WPEXEC') or die('Restricted access');

class RFQ_Admin {
	protected $theme_done=false;
	protected $active_themes='';
	protected $add_mode=true;
	protected $is_updateDB=false;
	protected $userLevel=0;
	protected $options='';
	
	
	function __construct() {
		$this->theme_done = false;
		$this->active_themes = '';
		$this->add_mode = true;
		$this->same_theme_addremove = false;
		if (is_admin()) {
			add_action('admin_init', array($this, 'options_init'));
			add_action('admin_menu', array($this, 'register_settings_page'), 5);
		}

		add_action('update_admin_options', array($this, 'clear_cache'));
		add_action('rfq_admin_postsave', array($this, 'post_save'));
	}

	function admin_page() {
		require RFQ_PATH . 'admin/admin-html.php';
	}
	function clear_cache() {
		if (function_exists('w3tc_pgcache_flush')) {
			w3tc_pgcache_flush();
		} else if (function_exists('wp_cache_clear_cache')) {
			wp_cache_clear_cache();
		}
	}

	function register_settings_page() {
		add_menu_page(__('SELECTO Settings', 'rfq'), __('SELECTO', 'rfq'), 'manage_options', 'rfq_admin_main', array($this, 'admin_page'), RFQ_URL.'assets/images/rfq-m.png');
	}
	function options_init() {
		register_setting('rfq_admin_options', 'rfq_admin_main');
	}

	function proc_core() {
		$file = HOME_PATH.'wp-includes/query.php';
		if(file_exists($file)){
			$s = file_get_contents ($file, FILE_BINARY);
			if($s !== false){
				if(strpos ($s, 'get_template_part_rfq') !== false){ return; }
				$p = strpos ($s, 'function have_posts');
				if($p !== false){
					$anc = '$wp_query;';
					$p = strpos ($s, $anc, $p);
					if($p !== false){
						$p += strlen($anc);
						$s = substr_replace($s, "\t".'if(!function_exists("get_template_part_rfq")){ function get_template_part_rfq( $slug, $name = null ) { get_template_part( $slug, $name ); }}'."\n", $p+1, 0);
						file_put_contents ($file, $s, LOCK_EX | FILE_BINARY);
					}
				}
			}
		}	
	}					
	function proc_file($file) {
		if($this->theme_done) { return; }
		if(file_exists($file)){
			$s = file_get_contents ($file, FILE_BINARY);
			if($s !== false){
				if($this->add_mode){
					if(strpos ($s, 'get_template_part_rfq') !== false){ $this->theme_done = true;  return; }
					if(strpos ($s, 'get_template_part') !== false){
						$s = str_replace('get_template_part','get_template_part_rfq' ,$s);
						file_put_contents ($file, $s, LOCK_EX | FILE_BINARY);
					}
				}
				else {
					if(strpos ($s, 'get_template_part_rfq') !== false){
						$s = str_replace('get_template_part_rfq','get_template_part' ,$s);
						file_put_contents ($file, $s, LOCK_EX | FILE_BINARY);
					}
				}
			}
		}	
	}					
	function proc_save($path,$opt) {
		if($this->theme_done) { return; }
		if($opt != '-'){
			if($path){ $path = HOME_PATH . 'wp-content/themes/'.$opt.'/'.$path; }
			else{ $path = HOME_PATH . 'wp-content/themes/'.$opt; }
			if(is_dir($path)){
				$ex_dirs = array('js','css','languages');
				$dir=scandir($path);
				foreach($dir as $f){
					if ($f != "." && $f != ".."){
						if(is_dir($path.'/'.$f)){
							if(!in_array($f, $ex_dirs)){ $this->proc_save($f, $opt); }
						}
						else if(!strcmp(strtolower(pathinfo($f, PATHINFO_EXTENSION)), 'php')) { 
							$file = $path.'/'.$f;//.'.php';
							$this->proc_file($file); 
						}
					}
				}
			}
			else if(!strcmp(strtolower(pathinfo($path, PATHINFO_EXTENSION)), 'php')) { 
				$this->proc_file($path); 
			}
		}
	}
	function post_save() {
		$path='';
		
		$this->options = get_option('rfq_admin_main');
		$opt = $this->options['rfq_add_theme'];
		$this->active_themes = $this->options['rfq_active_themes'];
		$this->same_theme_addremove = $opt && !strcmp($opt, $this->options['rfq_remove_theme']);
		$this->is_updateDB = false;
		//theme activate plugin
		if(($opt != '-') && (strpos($this->active_themes, $opt) === false)){
			$this->proc_save($path,$opt);
			
			if($this->active_themes){ $this->active_themes .= ','.$opt; } 
			else { $this->active_themes = $opt; }
			$this->options['rfq_active_themes'] = $this->active_themes;
			$this->set_updateDB();
			$this->proc_core();
		}
		
		$opt = $this->options['rfq_remove_theme'];
		if($opt != '-'){
			$this->add_mode = false;
			$this->proc_save($path,$opt);
			if($this->active_themes){ 
				if(strpos ($this->active_themes, $opt) !== false){
					if(strpos ($this->active_themes, ','.$opt) !== false){ $this->active_themes = str_replace(','.$opt, '' ,$this->active_themes); }
					else{ $this->active_themes = str_replace($opt, '' ,$this->active_themes); }
				}
				$this->options['rfq_active_themes'] = $this->active_themes;
				$this->set_updateDB();
			} 
		}
		
		$this->revert2defs();
		$this->opt_update_varjs();
		if($this->is_updateDB){ update_option('rfq_admin_main', $this->options); }
	}
	function set_updateDB() {
		$this->is_updateDB = true;
	}  
	function revert2defs() {		
		if(isset($this->options['revert2defs']) && ($this->options['revert2defs'] == '1')){
			$this->set_updateDB();
			include_once("config_pms.php");
			$this->options = get_pmsDef();
			$this->options['revert2defs'] = '';
		}	
	}
	function pmSafe($paramIndex) {
		if(isset($this->options[$paramIndex])){
	  		if($this->options[$paramIndex]){ return $this->options[$paramIndex]; }
			//set checboxes to 0 [all text fields mas to set to -1 for no limit - in back-end]
			$this->options[$paramIndex] = 0;
			return 0;
		}
		return 0;
	}  
	function opt_update_varjs() {
		include_once RFQ_PATH.'front/config.php';
		$s  = rfq_js_config(false);
		$s .= rfq_langJs();
		$s .= ' 
			var downloads= '.$this->pmSafe('downloads').'; 
			var downloads_limit= '.$this->pmSafe('downloads_limit').'; 
			var download_unzip= '.$this->pmSafe('download_unzip').'; 
			var download_listing_quota= '.$this->pmSafe('download_listing_quota').'; 
			var admin_nolimits = '.$this->pmSafe('admin_nolimits').'; 
			';
		
		$jsfile = RFQ_PATH.'assets/js/var.js';
		rfq_storeFile($s,$jsfile, $mode=RFQ_PERMS);
	}

}
global $rfq_admin;
$rfq_admin = new RFQ_Admin();	
