=== SELECTO ===
Contributors: Dmitry Vadis (dmvadis@gmail.com)
Donate link: http://www.cmscript.net/products-softwares/selecto-universall-tool.html/
Tags: content
Requires at least: 3.0.1
Tested up to: 3.5.1
Stable tag: 1.01
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

		Universal tool to allow users to collect articles they like 
	and download selected articles as zip file or view all collected 
	articles as html page directly. 
	Allowing to your users use collected articles offline. 
	Increasing by that way, significantly, your site interactivity 
	and usability for your users.

== Installation ==
INSTALLATION NOTE
									
	Upload:
		Assuming easy task to upload zip file [SELECTO-WP.zip] to 
	WordPress plugin folder: [WORDPRESS FOLDER/wp-content/plugins/]
	This can be achieved by using WordPress plugins upload facility 
	or using some of FTP client program or server backend facilities.
	
	Check if on first tab - "Basic Options", checkbox - "Use FTP:" 
	is checked by the system. Then, if checked, you have to provide 
	your FTP credentials. 
	Note: simple mark to pay attention on files, folders owner to 
	match to your FTP credentials. If checkbox - "Use FTP:" not 
	checked then, simple, not bothering with FTP at all.
	
	Setup:
		1. Check if on first tab - "Basic Options", 
			is checked checkbox - "Use FTP:". 
			If checked then you have to provide your FTP credentials, 
			else leave blank.
		2. On second tab - "Setup" and section - "Leave these fields 
			as is" for text field - "Title bound element" 
			mast to provide tag element [h1, h2] which is used 
			in articles title of used theme [by default use h1]. 
			Usualy this is h1 or h2 tag.
		    To check what tag is used in articles titles simple 
			make right mouse click on the article title and choice 
			menu option - "Inspect Element with FireBug" in 
			browser Firefox or something similar for other browsers.
		3. On section - "On what theme want to run Selecto plugin" 
			and dropdown list - "Activate Selecto plugin" select 
			the theme for which you want to activate the plugin. 
			Note: not all, third party, themes implements correctly 
			the WordPress framework procedura. 
			So if something is went wrong, please switch to an 
			preinstalled theme, as for example, Twenty Twelve.
	
	Now save and you are ready to use plugin.
	
		All rest options are self descriptive and you can to tune 
	them accordingly of how you want to use the plugin. 
	
	Best wishes.
	Author Dmitry Vadis.
	
== Frequently Asked Questions ==

= What is FTP path =

		FTP path is the subfolder where is installed your 
	WP installation and if FTP is provided for site root. 
	If your WP installation resides in site root or FTP 
	is provided for this subfolder then leave this field blank. 

= What exactly do button "Check all from this page" =

	Do what is exactly seys "Check all from this page" 
	and "UnCheck all from this page" selects and unselects 
	the articles from current page. 
	So this button operates only on current page.

== Screenshots ==

	1. A screenshot of front end, screenshot-1.png. from [/assets] folder.
	2. A screenshot of back end, screenshot-2.png. from [/assets] folder

== Changelog ==

= 1.0 =

== Upgrade Notice ==

= 1.0 =


== A brief Markdown Example ==

		Select the articles you like and download 
	1. as zipped file.
	2. or unzipped html file.
	
	