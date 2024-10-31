<?php
/**
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
// header, stiling, title
function rfq_mailTmpl_header() {
	return '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>'.__('Your selections at', 'rfq').' '.$_SERVER['HTTP_HOST'].'</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
html, body, .frm{
   width: 100%;
   height:100%;
   margin: 0;
   padding: 0;
   border: 0;
}
.cap{
	width: 100%;
	height:40px;
	padding: 0;
	border: 0;
	margin: 0px;
	text-align: center;
	vertical-align: middle;
	top: 0px;
}
.mid{
	width: 100%;
	height:100%;
	min-height: 0%;
	padding: 0;
	border: 0;
	margin: 0;
}
.bot{
	width: 100%;
	height:20px;
	padding: 0;
	border: 0;
	text-align: center;
	vertical-align: middle;
	margin: 0px;
	bottom: 0;
}
fieldset, legend {
	padding: 1px 8px 5px 8px;
	margin: 10px;
	border:  1px dotted #aaaaaa;
	color:#333333;
	background-color: #fafafa;
}
.rfq_futer{
	width:80%;
	padding-top:3px;
	padding-bottom:1px;
	margin-left:auto;
	margin-right:auto;
	text-align:center;
	height: 16px;
}
.rfq_futer .futer{
	border-top:solid #cccccc 1px;
	width:100%;
	text-align:right;
	font-family:Arial,Helvetica,sans-serif;
	font-size:xx-small;
	color:#999999;
}
.lab{
	width: auto;
	height:18px;
	line-height: 17px;
	padding: 1px 4px 1px 3px;
	border: 1px solid #999999;
	text-align: left;
	margin: 1px;
	top: 0;
	left: 0;
	position: fixed;
	border:  1px solid #999999;
	background-color: #EDFFD9;
	color: #999999;
	font-size: 11px;
	font-family: Arial, Helvetica, sans-serif;
	font-weight: bold;
	text-decoration: none;
}
.lab a{
	text-decoration: none;
	color: #999999;
	vertical-align: middle;
}
</style>
</head>
<body>
<div class="lab"><a href="http://'.$_SERVER['HTTP_HOST'].'" title="'.__('Visit the', 'rfq').' '.$_SERVER['HTTP_HOST'].' '.__('for more fresh news & info', 'rfq').'" target="_blank">'.$_SERVER['HTTP_HOST'].'</a></div>
<table class="frm" cellspacing="1" cellpadding="1"><tr><td class="cap"><center><h1>'.__('Your selections', 'rfq').',</h1><h4>'.__('that you are maked on', 'rfq').' '.date('l jS \of F Y G:i [e]').', '.__('at', 'rfq').' '.$_SERVER['HTTP_HOST'].'.</h4></center></td></tr><tr><td>';
}		
function rfq_mailTmpl_futer() {
	return '</td></tr><tr><td class="mid"></td></tr><tr><td class="bot"><div class="rfq_futer"><div class="futer">'.__('Selecto V 1.01', 'rfq').'</div></div></td></tr></table></body></html>';
}

// strictly body text
function rfq_mailTmpl_body($textData_ARR) {
	$i=1; $s = '';
	foreach($textData_ARR as $v){ $s .= '<fieldset><legend>'.$i.'</legend><h4>Author: '.$v['user_name'].'</h4><h2>'.$v['title'].'</h2>'.$v['text'].'</fieldset>';	$i++; }
	return $s;
}
?>