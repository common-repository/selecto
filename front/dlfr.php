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
if(isset($_POST)){
	if(isset($_POST['Q'])){
	session_start();
		$_SESSION['Q'] = $_POST['Q'];
	}	
}

if(!defined('_WPEXEC')){ define('_WPEXEC',1); }
if(isset($_GET['ucnt']) && $_GET['ucnt']) {
	session_start();
	if(!isset($_SESSION['useCount'])){ $_SESSION['useCount'] = 0; }
	$_SESSION['useCount']++;
	echo $_SESSION['useCount'];
}	
// make download of selections.
else if(isset($_GET['dl'])) {
	session_start();
	
	if(isset($_GET['p'])){
		if(!defined('RFQ_PATH')){		
			$s = base64_decode($_GET['p']);
			$i = explode(',', $s);
			define('RFQ_PATH', $i[0]);
			define('RFQ_URL', $i[1]);
			define('HOME_URL', $i[2]);
			if(!defined('DS')) { define('DS','/'); }
		}
	}
	defined( 'RFQ_PATH' ) or die( 'Restricted access' );

	$t = str_replace(HOME_URL, '', RFQ_URL);
	$t = str_replace($t, '', RFQ_PATH);
	define('HOME_BASE', $t);
	include_once( HOME_BASE . 'wp-blog-header.php' );
	include_once(dirname(__FILE__)."/rfq_Srv.php");
	
	include_once(RFQ_PATH.'tmpl/downloads/mail_tmpl.php'); 
	$res = '';
	if(isset($_SESSION['Q'])){
		$res=array();
		foreach($_SESSION['Q'] as $i){
			$res[]=rfq_loadArticle($i);
		}
	}
	else{
		$cooky = isset($_COOKIE["RFQ_BAG"]) ? $_COOKIE["RFQ_BAG"] : (isset($_SESSION["RFQ_BAG"]) ? $_SESSION["RFQ_BAG"] : '');
		if($cooky){ 
			$res = rfq_cookieProc_getArticles($cooky); 
		}
	} 

	if($res) {
		$s  = rfq_mailTmpl_header();
		$s .= rfq_mailTmpl_body($res);
		$s .= rfq_mailTmpl_futer();
		//echo "<br/>AAA: ".$s;
		if($_GET['dl'] == '0'){
			//echo 'TMP: '.HOME_PATH.'tmp';return;
			if(!is_dir(HOME_PATH.'tmp')){
				//echo 'TMPaaa: '.HOME_PATH.'tmp';return;
				rfq_mkDir(HOME_PATH.'tmp', $permissions=0777);
			}
			if(rfq_zip($s)){
				header("Content-Type: application/octet-stream");
				header("Content-Disposition: attachment; filename=".basename(rfq_get_zipDefFileName()));
				readfile(rfq_get_zipDefFileName());
			}
			else { echo rfq_userErrorMsg(); }
		}	
		else if($_GET['dl'] == '1'){
			header('Content-Disposition: attachment; filename="'.pathinfo($_SERVER['HTTP_HOST'],PATHINFO_FILENAME).'.html"');
			echo $s;
		}	
	}
	else {
   		echo rfq_userErrorMsg();
	}	
}

?>