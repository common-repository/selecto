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
	define('DEBUG_MODE',0);
	if(DEBUG_MODE){ error_reporting(E_ALL); ini_set('display_errors', true); }

if(isset($_GET['p'])){
	if(!defined('RFQ_PATH')){		
		$s = base64_decode($_GET['p']);
		$i = explode(',', $s);
		define('RFQ_PATH', $i[0]);
		define('RFQ_URL', $i[1]);
		define('HOME_URL', $i[2]);
		if(!defined('DIRECTORY_SEPARATOR')) { define('DIRECTORY_SEPARATOR','/'); }
		if(!defined('DS')) { define('DS','/'); }
	}
}
else if(!defined('RFQ_PATH')){ return; }

if(!defined('HOME_PATH')){
	$t = str_replace(HOME_URL, '', RFQ_URL);
	$t = str_replace($t, '', RFQ_PATH);
	define('HOME_PATH', $t);
}		

define('RFQ_CONFIG', base64_encode ( RFQ_PATH.','.RFQ_URL.','.HOME_URL ));

if(!isset($_SESSION['useCount'])){ $_SESSION['useCount'] = 0; }
function rfq_check_DS($s){
	if(isset($s[0]) && ($s[0] != DS)){ return DS. $s; }
	return $s;
}
define('SENDER_SITE_TITLE',$_SERVER['HTTP_HOST']);
define('JSITE_TIME_ZONE',"Europe/Berlin");
/*
====================================================
List of timezones in the group Europe
===================================================
Europe/Amsterdam, Europe/Andorra, Europe/Athens, Europe/Belfast, Europe/Belgrade 
Europe/Berlin, Europe/Bratislava, Europe/Brussels, Europe/Bucharest, Europe/Budapest 
Europe/Chisinau, Europe/Copenhagen, Europe/Dublin, Europe/Gibraltar, Europe/Guernsey 
Europe/Helsinki, Europe/Isle_of_Man, Europe/Istanbul, Europe/Jersey, Europe/Kaliningrad 
Europe/Kiev, Europe/Lisbon, Europe/Ljubljana, Europe/London, Europe/Luxembourg 
Europe/Madrid, Europe/Malta, Europe/Mariehamn, Europe/Minsk, Europe/Monaco 
Europe/Moscow, Europe/Nicosia, Europe/Oslo, Europe/Paris, Europe/Prague 
Europe/Riga, Europe/Rome, Europe/Samara, Europe/San_Marino, Europe/Sarajevo 
Europe/Simferopol, Europe/Skopje, Europe/Sofia, Europe/Stockholm, Europe/Tallinn 
Europe/Tirane, Europe/Tiraspol, Europe/Uzhgorod, Europe/Vaduz, Europe/Vatican 
Europe/Vienna, Europe/Vilnius, Europe/Volgograd, Europe/Warsaw, Europe/Zagreb 
Europe/Zaporozhye, Europe/Zurich       
*/

//.. attachments field name in mailing form
define('RFQ_ATTACH_FIELD_NAME','files');
//.. admin emal where are collected users an guests RFQ messages
if(!defined('RFQ_ADMIN_EMAIL')){ define('RFQ_ADMIN_EMAIL','support@'.$_SERVER['HTTP_HOST']); }

define('USERLEVEL_GUEST',0);
define('USERLEVEL_USER',1);
define('USERLEVEL_ADMIN',10);
date_default_timezone_set ("Europe/Berlin");

function rfq_js_config($addScriptTags= true){
if($addScriptTags){ 
$s = '<script type="text/javascript">
';
}
else{ $s = ''; }

$s .= ' var USE_COUNT = "'.$_SESSION['useCount'].'";';
$s .= ' var RFQ_CONFIG = "'.RFQ_CONFIG.'";';
$s .= ' var RFQ_PATH = "'.RFQ_PATH.'";';
$s .= ' var RFQ_URL = "'.RFQ_URL.'";';
$s .= ' var HOME_URL = "'.HOME_URL.'";';
$s .= ' var SENDER_SITE_TITLE = "'.SENDER_SITE_TITLE.'";';
$s .= ' var USERLEVEL_GUEST = '.USERLEVEL_GUEST.';';
$s .= ' var USERLEVEL_USER = '.USERLEVEL_USER.';';
$s .= ' var USERLEVEL_ADMIN = '.USERLEVEL_ADMIN.';';

if($addScriptTags){ 
$s .= '
</script>';
}
return $s;
}

?>