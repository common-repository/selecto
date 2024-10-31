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

defined( '_WPEXEC' ) or die( 'Restricted access' );
// ------- CONFIGURATION SECTION START ----------------------------------------------------------------------------------------------------------------
include_once(dirname(__FILE__)."/config.php");

if(DEBUG_MODE){ }
global $g_rfq;
global $g_rfqLimit;
global $debugMSG;
global $rfq_PARAMS;
global $userLevel;

$g_rfq='';
$g_rfqLimit=0;
$debugMSG='';
$rfq_PARAMS='';
$userLevel = 0;

function rfq_cookieProc($cookie) { 
	$cooky = rfq_getCookyDATA(); 
	$rfq = '';
	if($cooky){ 
		$rfq=array();
		foreach($cooky as $i){ $rfq[]=rfq_loadLink($i); }
	}
	return $rfq;
}
function rfq_getCookies() { 
	$cooky = rfq_getCookyDATA(); 
	$rfq = 0;
	if($cooky){ 
		$rfq=array();
		foreach($cooky as $i){ $rfq[] = trim ( 'rfq', $i); }
	}
	return $rfq;
}
//====FROM RFQ.PHP =====================================================================================================================================================
	function rfq_params2ARRAY($p){
		$t = explode(',', $p);
		$k = array();
		$v = array();
		foreach($t as $val){
			$val = str_replace('"', '', $val);
			$a = explode(':', $val);
			if(count($a)>1){
				$k[] = $a[0];
				$v[] = $a[1];
			}
		}
		$r = array_combine($k, $v);
		return $r;
	}
	function rfq_getParams(){
		global $rfq_PARAMS;
		$rfq_PARAMS = get_option('rfq_admin_main');
		return $rfq_PARAMS; 
	}
//======================================================================================================================================================================
//functions to provide download facilities [zip & unzip mode] 
//   in file download_tmpl.php is template to formating downloads to users [ editable by user]
//======================================================================================================================================================================
	function rfq_userErrorMsg() {
   		$s  = "<script type=\"text/javascript\"> ";   
   		$s .= " window.parent.errorDownloadMsg(\"". __('Download error. Please try later').".\"); ";
   		$s .= "</script>";
		return $s;
	}	
	function rfq_get_zipDefFileName() { return HOME_PATH.'tmp/rfq_'.pathinfo($_SERVER['HTTP_HOST'],PATHINFO_FILENAME).'_'. $_SERVER['REMOTE_ADDR'] .'.zip'; }
	function rfq_zip($s, $inFileName='', $zipFileName='') { 
		$zip = new ZipArchive;
		if(!$zipFileName){ $zipFileName = rfq_get_zipDefFileName(); }
		$res = $zip->open($zipFileName, ZipArchive::CREATE);
		if ($res === TRUE) { 
			if(!$inFileName){ $inFileName = pathinfo($_SERVER['HTTP_HOST'],PATHINFO_FILENAME).'.html'; }
			$zip->addFromString($inFileName, $s);  
			$zip->close(); 
			chmod ($zipFileName, RFQ_PERMS);
			return true; 
		}
		return false;	
	}
	
	function rfq_loadLink($post_id) {
		$id = str_replace ( 'rfq', '', $post_id);
		$link = get_post($id);
		$email = get_the_author_meta( 'user_email', $link->post_author );
		$user_name = get_the_author_meta( 'user_nicename', $link->post_author );
		return array('id'=>$post_id, 'title'=>$link->post_title, 'user_name'=>$user_name, 'user_email'=>$email);
	}
	function rfq_loadArticle($post_id) { 
		$id = str_replace ( 'rfq', '', $post_id);
		//echo "<br/><br/><br/><br/>rfq_loadArticle[rfq_loadArticle]: ".$post_id;
		$link = get_post($id);
		$email = get_the_author_meta( 'user_email', $link->post_author );
		$user_name = get_the_author_meta( 'user_nicename', $link->post_author );
		$t='<a href="'.$link->guid.'" title="'.$link->post_title.'" target="_blank">'.$link->post_title.'</a>';
		return array(
		'id'=>$post_id, 
		'title'=>$t, 
		'user_name'=>$user_name, 
		'user_email'=>$email, 
		'post_date'=>$link->post_date,
		'text'=>$link->post_content);
	}
	
	function rfq_cookieProc_getArticles($cookie) { 
		$cooky = rfq_getCookyDATA(); 
		$rfq = '';
		if($cooky){ 
			$rfq=array();
			foreach($cooky as $i){ $rfq[]=rfq_loadArticle($i); }
		}
		return $rfq;
	}
	function rfq_getCookyByPOST() { 	
		global $debugMSG;
		$rfq=0;
		foreach($_POST as $k => $i){
			if($i == 'on'){ 
				if(!$rfq){ $rfq = array(); }
				$rfq[]=rfq_loadArticle($k);
			}
		}
		return $rfq;
	}
	function is_chkboxChecked($chkBoxName,$chkBoxValue){
		if(!empty($_POST[$chkBoxName])){
			foreach($_POST[$chkBoxName] as $chkval){
				if($chkval == $chkBoxValue){ return true; }
			}
		}
		return false;
	}
function rfq_getCookyDATA() { 	
	$cooky = isset($_SESSION["Q"]) ? $_SESSION["Q"] : '';
	if($cooky){ 
		return $cooky;
	}
	else {
		//$cooky = isset($_REQUEST["lnko"]) ? $_REQUEST["lnko"] : (isset($_COOKIE["RFQ_BAG"]) ? $_COOKIE["RFQ_BAG"] : (isset($_SESSION["RFQ_BAG"]) ? $_SESSION["RFQ_BAG"] : ''));
		$cooky = isset($_COOKIE["RFQ_BAG"]) ? $_COOKIE["RFQ_BAG"] : (isset($_SESSION["RFQ_BAG"]) ? $_SESSION["RFQ_BAG"] : '');
		$pattern = "/([0-9a-zA-Z]+)(,[0-9a-zA-Z]+)*/i";
		if(preg_match_all($pattern, $cooky,$matches,PREG_PATTERN_ORDER)){
			$t = $matches[0];
			array_shift($t);
			if(count($t)){
				return $t;
			}
		}	
	} 
	return '';
}
//======================================================================================================================================================================
//======================================================================================================================================================================

?>