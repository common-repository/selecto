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

class rfqEngine{

   static $searchEl='<h1>';			//search element in the parsed string
   static $searchElLen=0;			//search element len
   static $searchElclose='</h1>';	//search element in the parsed string
   static $searchElcloseLen=0;		//search element len
   static $injetParentElExp='';		//bound element inject expression 
   static $injectMainExp='';		//main inject expression 

	function rfqEngine(){
		self::$searchElLen = strlen(self::$searchEl);
		self::$searchElcloseLen = strlen(self::$searchElclose);
		self::$injetParentElExp = ' id="hrfq%s" ';
		self::$injectMainExp = '<div class="rfq" title="Send a RFQ / Download your selections"><input id="rfq%s" class="rfqChkbox" type="checkbox" value="1" autocomplete="off" name="rfq%s"></div>';
	}

	static  function set_titleElement($boundTitleElement){
		if(!$boundTitleElement){ return; }
		if(strpos($boundTitleElement, '<') !== false){ 
			$r = array('<','>');
			$boundTitleElement = str_replace($r, '', $boundTitleElement); 
		}
		self::$searchEl = '<'.$boundTitleElement;//.'>';
		self::$searchElLen = strlen(self::$searchEl);
		self::$searchElclose = '</'.$boundTitleElement.'>';
		self::$searchElcloseLen = strlen(self::$searchElclose);
	}
	static  function set_Button($injectMainExp){
		if(!$injectMainExp){ return; }
		self::$injectMainExp = $injectMainExp;
	}
	static function set($searchEl='<h1>', $searchElclose='</h1>', $injetParentElExp='', $injectMainExp=''){
		self::$searchEl = $searchEl;
		self::$searchElLen = strlen(self::$searchEl);
		self::$searchElclose = $searchElclose;
		self::$searchElcloseLen = strlen(self::$searchElclose);
		if($injetParentElExp){ 
			self::$injetParentElExp = $injetParentElExp; 
		}
		if($injectMainExp){ 
			self::$injectMainExp = $injectMainExp; 
		}
	}

	static function inject($parsedStr, $postID){

		if(!$parsedStr) { return $parsedStr; }

		$p = stripos($parsedStr, self::$searchEl);
		if($p === false){ return $parsedStr; }

		$inj = sprintf(self::$injetParentElExp, $postID);
		$p += self::$searchElLen;
		$parsedStr = substr_replace($parsedStr, $inj, $p, 0);
	
		$p = stripos($parsedStr, self::$searchElclose,$p);
		if($p === false){ return $parsedStr; }
		$p += self::$searchElcloseLen;

		$inj = str_replace('%s', $postID, self::$injectMainExp); 
		$parsedStr = substr_replace($parsedStr, $inj, $p, 0);
		return $parsedStr;
	}

}


?>