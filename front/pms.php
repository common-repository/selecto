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
class rfqPMS {

	static function pms(){
		include_once("config_pms.php");
		$t = explode("\n", get_pmsDef());
		$r = array('\\','\/',' ','"','\n','\r',"\n","\r","'");
		$k = array();
		$v = array();
		foreach($t as $val){
			if($val){
				$a = explode('=', $val);
				if(isset($a[0]) && $a[0]) {
					$a[0] = str_replace($r,'',$a[0]);
					if(trim($a[0])){
						$k[] = '"'.$a[0].'"';
						if(isset($a[1])) { 
							if($a[1]) { 
								$a[1] = str_replace($r,'',$a[1]);
								if($a[1]) { $v[] = '"'.$a[1].'"'; }
								else { $v[] = '""'; }
							}
							else { $v[] = '""'; }
						} 
						else { $v[] = '""'; }
					}
				}
				unset($a);
			}
		}
		$r = array_combine($k, $v);
		//print_r($r); 
		return $r;
	}
}


?>