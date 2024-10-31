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
function rfq_langJs() {
	$i = 1;
	$s = '
var RFQ_MSG_'.$i++.' = "'.__("No supplier is selected to send RFQ").'";
var RFQ_MSG_'.$i++.' = "'.__("Please fill the RFQ Detail field").'";
var RFQ_MSG_'.$i++.' = "'.__("Please fill the Subject field").'";
var RFQ_MSG_'.$i++.' = "'.__("Please fill the Your Name field").'";
var RFQ_MSG_'.$i++.' = "'.__("Please fill the Email field").'";
var RFQ_MSG_'.$i++.' = "'.__("Please fill the Company Name field").'";
var RFQ_MSG_'.$i++.' = "'.__("You are not selected anything yet.<br />Make a selection now!").'";
var RFQ_MSG_'.$i++.' = "'.__("Your selections:").'";
var RFQ_MSG_'.$i++.' = "'.__("Send a RFQ to the suppliers you\'ve selected").'";
var RFQ_MSG_'.$i++.' = "'.__("Not selected anything to send RFQ.").'";
var RFQ_MSG_'.$i++.' = "'.__("Please make some selection on items you want and then push this button to send your RFQ.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you as Guest can only select up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" articles/items/suppliers. To lift this limit and take the benefits using our tool which is created to assist your inquiry effort, please sign up to get full service capacity and to enjoy supreme interactivity experience.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you can only select up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" articles/items/suppliers. To lift this limit, please, contact admin.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you as Guest can only send up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" emails. To lift this limit and take the benefits using our tool which is created to assist your inquiry effort, please sign up to get full service capacity and to enjoy supreme interactivity experience.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you as Guest can only download up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" articles/items. To lift this limit and take the benefits using our tool which is created to assist your inquiry effort, please sign up to get full service capacity and to enjoy supreme interactivity experience.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you can only send up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" emails. To lift this limit, please, contact admin.").'";
var RFQ_MSG_'.$i++.' = "'.__("Sorry, you can only download up to ").'";
var RFQ_MSG_'.$i++.' = "'.__(" articles/items. To lift this limit, please, contact admin.").'";
var RFQ_MSG_'.$i++.' = "'.__("No emails allowed").'";
var RFQ_MSG_'.$i++.' = "'.__("No downloads allowed").'";
var RFQ_MSG_'.$i++.' = "'.__("Actions").'";
var RFQ_MSG_'.$i++.' = "'.__("Select action").'";
var RFQ_MSG_'.$i++.' = "'.__("Send to me").'";
var RFQ_MSG_'.$i++.' = "'.__("Download zipped listing").'";
var RFQ_MSG_'.$i++.' = "'.__("Download listing").'";
var RFQ_MSG_'.$i++.' = "'.__("Send email to articles authors").'";
var RFQ_MSG_'.$i++.' = "'.__("Send RFQ").'";
var RFQ_MSG_'.$i++.' = "'.__("No actions are setup").'";
var RFQ_MSG_'.$i++.' = "'.__("Send to me").'";
var RFQ_MSG_'.$i++.' = "'.__("Download zipped listing").'";
var RFQ_MSG_'.$i++.' = "'.__("Download listing").'";
var RFQ_MSG_'.$i++.' = "'.__("Email to publish users").'";
var RFQ_MSG_'.$i++.' = "'.__("Send RFQ").'";
var RFQ_MSG_'.$i++.' = "'.__("No actions").'";
var RFQ_MSG_'.$i++.' = "'.__("Send email with articles/items you\'ve selected to your self and / or your friend[s]").'";
var RFQ_MSG_'.$i++.' = "'.__("Download zipped listing with articles/items you\'ve selected").'";
var RFQ_MSG_'.$i++.' = "'.__("Download unzipped listing with articles/items you\'ve selected").'";
var RFQ_MSG_'.$i++.' = "'.__("Send email to articles autors you\'ve selected").'";
var RFQ_MSG_'.$i++.' = "'.__("Send RFQ to suppliers you\'ve selected").'";
var RFQ_MSG_'.$i++.' = "'.__("No actions are setup yet. Please setup some actions from admin side//45").'";
var RFQ_MSG_'.$i++.' = "'.__("Menu").'";
var RFQ_MSG_'.$i++.' = "'.__("Select action from Actions menu").'";
var RFQ_MSG_'.$i++.' = "'.__("Bulk command").'";
var RFQ_MSG_'.$i++.' = "'.__("Check all from this page").'";
var RFQ_MSG_'.$i++.' = "'.__("UnCheck all from this page").'";
var RFQ_MSG_'.$i++.' = "'.__("Delete all selections").'";
';
	return $s;
}
function get_template_part_rfq($slug, $name){
	ob_start();
	get_template_part($slug, $name);
	$result = ob_get_contents();
	ob_end_clean();
	$result = apply_filters( 'rfqanch', $result );
	echo $result;
}
function rfq_checkDS_f($s){
	if(isset($s[0]) && ($s[0] != DS)){ return DS . $s; }
	return $s;
}
function rfq_checkDS_b($s){
	if(isset($s) && ($s[strlen($s)-1] != DS)){ return $s . DS; }
	return $s;
}
function rfq_checkDS_b_no($s){
	if(isset($s) && ($s[strlen($s)-1] == DS)){ $s = rtrim($s, "\/"); return $s; }
	return $s;
}
//..FTP global func
function rfq_find_subFolderFtp() {
	$t = str_replace(HOME_URL, '', RFQ_URL);
	$t = str_replace($t, '', RFQ_PATH);
	if(!defined('HOME_PATH')){ 	define('HOME_PATH', $t); }
	$ss = explode('.', $_SERVER['SERVER_NAME']);
	$ssc = count($ss);
	$srvn = $ss[$ssc-2].'.'.$ss[$ssc-1];
	$i = strpos($_SERVER['DOCUMENT_ROOT'],$srvn);
	if($i !== false){
		$t = substr ($_SERVER['DOCUMENT_ROOT'], $i+strlen($srvn), 10000000000);
	}
	else {
		$t = str_replace($_SERVER['DOCUMENT_ROOT'], '', HOME_PATH);
	}
	return rfq_checkDS_b_no($t);
}	
function rfq_useFtp(){
	global $g_ftpopt;
	if($g_ftpopt) { return isset($g_ftpopt['rfq_use_ftp']) ? true : false; }
	$g_ftpopt = get_option('rfq_admin_main');
	return isset($g_ftpopt['rfq_use_ftp']) ? true : false;
}
function rfq_get_ownerFtp($useShell = true){
	$f = HOME_PATH.'wp-admin/admin.php';
	if(function_exists('shell_exec') && $useShell){
		$s = `ls -l $f`;
		$t = explode(' ', $s);
		$s = $t[2];
		unset($t);
		return $s;
	}
	if(function_exists(posix_getpwuid)){ 
		$t = posix_getpwuid(fileowner($f));
		$s = $t['name'];
		unset($t);
		return $s; 
	}
	return '';
}
function rfq_normilisePath2Ftp($path){
	global $g_ftpopt;
	if(!$path) { return $path; }
	$sftp = rfq_folderFtp();	
	$i = strpos($path,$sftp);
	if($i !== false){
		$path = $sftp.substr ($path, $i+strlen($sftp), 10000000000);
	}
	return $path;
}
function rfq_folderFtp(){
	global $g_ftpopt;
	$ftpFolder = '';
	if($g_ftpopt) { 
		if(isset($g_ftpopt['rfq_ftp_path'])){ return $g_ftpopt['rfq_ftp_path']; }
		$ftpFolder = rfq_find_subFolderFtp();
		$g_ftpopt['rfq_ftp_path'] = $ftpFolder;
		return $g_ftpopt; 
	}
	
	$g_ftpopt = get_option('rfq_admin_main');
	if(isset($g_ftpopt['rfq_ftp_path'])){
		if($g_ftpopt['rfq_ftp_path']){			
			$ftpFolder = $g_ftpopt['rfq_ftp_path'];
		}
		else{			
			$ftpFolder = rfq_find_subFolderFtp();
			$g_ftpopt['rfq_ftp_path'] = $ftpFolder;
		}
	}
	else{			
		$ftpFolder = rfq_find_subFolderFtp();
		$g_ftpopt['rfq_ftp_path'] = $ftpFolder;
	}
	return $ftpFolder;
}
function rfq_connectFtp(){
	global $g_ftp;
	global $g_ftpopt;
	if($g_ftp) { return $g_ftp; }
	$options = get_option('rfq_admin_main');
	if(!isset($options['rfq_ftp_password'])){ return ''; }

	if(!defined('HOME_PATH')){
		$t = str_replace(HOME_URL, '', RFQ_URL);
		$t = str_replace($t, '', RFQ_PATH);
		define('HOME_PATH', $t);
	}		

	if(isset($g_ftpopt)){ unset($g_ftpopt); }
	$g_ftpopt = array();
	$g_ftpopt['hostname'] 	= $options['rfq_ftp_hostname'];
	$g_ftpopt['username']	= $options['rfq_ftp_username'];
	$g_ftpopt['password']	= $options['rfq_ftp_password'];
	$g_ftpopt['port'] 		= intval($options['rfq_ftp_port'], 10);
	$g_ftpopt['path']		= $options['rfq_ftp_path'];
	$g_ftpopt['ssl'] 		= false;//$options['rfq_ftp_connect'];
	
	if(!defined('FS_CONNECT_TIMEOUT')){ 	define('FS_CONNECT_TIMEOUT', 3000); }
	if(!defined('FS_CHMOD_DIR')){ 	define('FS_CHMOD_DIR', 0755); }
	if(!defined('FS_CHMOD_FILE')){ 	define('FS_CHMOD_FILE', 0644); }
	
	require HOME_PATH . 'wp-admin/includes/class-wp-filesystem-base.php';
	require HOME_PATH . 'wp-admin/includes/class-wp-filesystem-ftpext.php';

	$g_ftp = new WP_Filesystem_FTPext($g_ftpopt);
	$g_ftp->connect();
	return $g_ftp;
}
function rfq_isWritable(){
	return is_writable(RFQ_PATH.'admin');
}
function rfq_postSave(){
	if(isset($_GET['settings-updated'])){ do_action('rfq_admin_postsave'); }
}
function rfq_is_folderEmpty($folder){
	if(is_dir($folder) ){
		$files = opendir($folder);
		$i=0;
		while ($file=readdir($files)){ $i++; if ($i>2){ return false; } }
		return true; 
    }
	return false; 
}
function rfq_base2binary($base){
	$t = explode(',', $base);
	$code = $t[count($t)-1];
	$out = '';
	for ($i=0; $i < ceil(strlen($code)/256); $i++) { $out .= base64_decode(substr($code,$i*256,256)); }
	return $out;
}
function rfq_storeFile($s,$FileName, $mode=RFQ_PERMS){
	if(!$FileName) { return false; }

	if(rfq_useFtp()){
		$ftp = rfq_connectFtp();
		$FileName = rfq_normilisePath2Ftp($FileName);
			
		$ftp->put_contents($FileName, $s);
	}
	else{
		if(file_exists($FileName)){ $file = fopen ($FileName, "wb"); }
		else { 
			if(is_dir(dirname($FileName))){ $file = fopen ($FileName, "xb"); }
			else { return false; }
		}
		if(!$file){ return false; }
	 
		fwrite($file, $s);//, strlen($s)); 
		if(fclose($file)){ @chmod($FileName, $mode); }
	}
	return true;
}

?>