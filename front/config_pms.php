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

define( '_WPEXEC', 1)
/*
DEFAULT CONFIGURATION
*/
function g_defs(){	
	return '
downloads=1
download_unzip=''
udownloads=1
udownload_unzip=1
admin_nolimits=1
downloads_limit=10
download_listing_quota=10
udownloads_limit=100
udownload_listing_quota=100
curLang=en-GB
revert2defs=''
rfq_bounds_title=h1
rfq_add_theme=twentytwelve
rfq_remove_theme=-
';
}
function get_pmsDef(){	
	$t = explode("\n", g_defs());
	$r = array();
	foreach($t as $v){
		$n = explode("=", $v);
		if(count($n) == 1){ $r[$n[0]] = ''; }
		else{ $r[$n[0]] = $n[1]; }
		unset($n);
	}
	return $r;
}
?>