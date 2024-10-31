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

error_reporting(E_ALL); ini_set('display_errors', true);
// No direct access allowed to this file
defined( '_WPEXEC' ) or die( 'Restricted access' );

require_once('engine.php');
 
class RFQ_Srv {

	static $prms='';
	static $engine='';
	protected $userLevel=0;
	static $active=1;
	protected $user='';
   
	function RFQ_Srv (){
		self::$active = 1;
		$opts = $this->getParams();
		$themes = false;
		if(!$opts){ 
			$msg = __('PLEASE SETUP AND SAVE THE SELECTO PLUGIN IN ADMIN BACKEND!', 'rfq');
			echo "<br/><br/>".$msg."<br/><br/>";
			echo '<script type="text/javascript">alert("'.$msg.'")</script>'; 
		}
		else { $themes = $opts['rfq_active_themes']; }
		
		$curr_theme = wp_get_theme();

		$pms = $curr_theme->get( 'TextDomain' );
		if(!$pms){ $pms = $curr_theme->get( 'template:WP_Theme:private' ); }
		if(!$pms){
			$pms = strtolower("$curr_theme");
			$pms = str_replace(' ','',$pms);
		}

		if(!$themes || ($themes && (strpos ($themes, $pms) === false))){ 
			self::$active = 0; 
		}
		else{ 
			add_action( 'init', array( $this, 'init' ));
		}
	}      
	function Init () {
		add_action( 'wp_print_styles', array( $this, 'load_styles'));
		add_action( 'wp_print_scripts', array( $this, 'load_scripts' ));
		add_action('rfqanch', array($this, 'rfq_anch'),10,1);
		self::$engine = new rfqEngine();
		$this->userLevel=0;
		$this->updateConfigVars();
		$this->getUser();
   }  
   function getUser() {
		global $current_user;
		if(!$this->user) {
			$this->user = array();
			get_currentuserinfo();
			$this->user['username'] 	= $current_user->user_login;
      		$this->user['email']		= $current_user->user_email;
      		$this->user['first-name']	= $current_user->user_firstname;
      		$this->user['last-name'] 	= $current_user->user_lastname;
      		$this->user['display-name'] = $current_user->display_name;
      		$this->user['id'] 			= $current_user->ID;	
			
			$userdata 					= get_userdata(1);
      		$this->userLevel 			= $userdata->user_level;
		}
   }  
	function load_styles() {
		wp_enqueue_style( 'rfq-css', RFQ_URL . 'assets/css/rfq.css', RFQ_VERSION );
	}
	function load_scripts() {
		wp_enqueue_script( 'rfq-var', RFQ_URL . 'assets/js/var.js', false, RFQ_VERSION, true );
		//wp_enqueue_script( 'rfq-jquery', RFQ_URL . 'assets/js/jquery.js', false, RFQ_VERSION, true );
		wp_enqueue_script( 'rfq-cooky', RFQ_URL . 'assets/js/cooky.js', array( 'jquery' ), RFQ_VERSION, true );
		wp_enqueue_script( 'rfq-json2', RFQ_URL . 'assets/js/json2.js', array( 'jquery' ), RFQ_VERSION, true );
		wp_enqueue_script( 'rfq-jquery_02', RFQ_URL . 'assets/js/jquery_02.js', array( 'jquery' ), RFQ_VERSION, true );
		wp_enqueue_script( 'rfq-jquery_01', RFQ_URL . 'assets/js/jquery_01.js', array( 'jquery' ), RFQ_VERSION, true );
			echo '<script type="text/javascript"> var userLevel = '.$this->userLevel.'; </script>'; 
	}
	function updateConfigVars() {
		self::$prms = $this->getParams();

		if($this->userLevel == USERLEVEL_ADMIN){
			if(isset(self::$prms['admin_nolimits']) && ($this->userLevel == USERLEVEL_ADMIN)){
	
				self::$prms['downloads'] = 1;
				self::$prms['downloads_limit'] = 10000000;
				self::$prms['download_unzip'] = 1;
				self::$prms['download_listing_quota'] = 10000000;
			}	
		}

		$rfq_inject_fragment = '<div class="rfq" title="'. __('Send / Download selections you\'ve maked').'."><input id="rfq%s" class="rfqChkbox" type="checkbox" value="1" autocomplete="off" name="rfq%s"></div>';
		if(isset(self::$prms['rfq_inject_fragment']) && self::$prms['rfq_inject_fragment']){
			$rfq_inject_fragment = self::$prms['rfq_inject_fragment'];
		}
		else{ // store the i button exp
			self::$prms['rfq_inject_fragment'] = $rfq_inject_fragment;
			self::saveParams(self::$prms);
		}
		
		$rfq_bounds_title = 'h1';
		if(isset(self::$prms['rfq_bounds_title']) && self::$prms['rfq_bounds_title']){
			$rfq_bounds_title = self::$prms['rfq_bounds_title'];
		}
		
		self::$engine->set_titleElement($rfq_bounds_title);
		self::$engine->set_Button($rfq_inject_fragment);

	}  
	function rfq_anch($s){
		if(!self::$active) { return; }
		if(is_front_page() || is_home() || !is_admin()) { $s = self::$engine->inject($s, get_the_ID()); }
		return $s;
	} 
	function getParams(){
    	return get_option('rfq_admin_main');
	}
   function saveParams($params) {
		update_option('rfq_admin_main', $params);
   }
   function getParam($paramName) {
		$params = $this->getParams();
		if(isset($params[$paramName])){ return $params[$paramName]; }
		return '';
   }
   function saveParam($paramName,$paramValue) {
		$params = $this->getParams();
		$params[$paramName] = $paramValue;
		$this->saveParams($params);
   }
   
}

global $rfq_main;
$rfq_main = new RFQ_Srv();
