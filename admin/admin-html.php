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

// opt tabs
defined('_WPEXEC') or die('Restricted access');

global $rfq_els;
global $rfq_admin;
rfq_postSave();
?>
<div class="wrap">

<a href="http://cmscript.net/" title="<?php _e('Visit our Site - cmscript.net', 'rfq');?>">
	<div style="background: url('<?php echo RFQ_URL; ?>assets/images/rfq-t.png') no-repeat;" class="icon32"></div>
</a>

<h2 id="rfq-title"><?php _e("Selecto: Settings", 'rfq'); ?></h2>

<div id="rfq_content">

<h2 class="nav-tab-wrapper" id="rfq-tabs">
	<a class="nav-tab" id="basic-tab" href="#top#basic"><?php _e('Basic Options', 'rfq');?></a>
	<a class="nav-tab" id="setup-tab" href="#top#setup"><?php _e('Setup', 'rfq');?></a>
	<a class="nav-tab" id="actions-tab" href="#top#actions"><?php _e('Actions', 'rfq');?></a>
</h2>
<?php
	echo '<form action="' . admin_url('options.php') . '" method="post" id="rfqform">';
	settings_fields('rfq_admin_options');
?>
<div id="basic" class="rfqtab">
	<?php

	echo $rfq_els->unit_title(__('FTP credentials', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->checkbox('rfq_use_ftp', __('Use FTP', 'rfq'),!rfq_isWritable());
	echo $rfq_els->descr(__('Use FTP option when unable to access directly your web server.<br/>Tip: if during installation checkbox "Use FTP" is checked this is marked by the system that can\'t to directly access your web server. So you have to provide the FTP credentials.<br/>Else if checkbox is not checked leave FTP section blank.', 'rfq'));
	echo $rfq_els->textinput('rfq_ftp_hostname', __('FTP hostname', 'rfq'), $default = '127.0.0.1');
	echo $rfq_els->textinput('rfq_ftp_username', __('FTP username', 'rfq'), $default = rfq_get_ownerFtp());
	echo $rfq_els->textinput('rfq_ftp_password', __('FTP password', 'rfq'), $default = '');
	echo $rfq_els->textinput('rfq_ftp_port', __('FTP port', 'rfq'), $default = '21');
	echo $rfq_els->textinput('rfq_ftp_path', __('FTP path', 'rfq'), $default = rfq_folderFtp());
	echo $rfq_els->descr(__('FTP path is a subpath where site resides relatively to site root, for which FTP credentials are valid. For example: if WordPress installation is resides on site subfolder [wp] then this subpath will be [wp]. Else WordPress installation is resides on site root then this subpath will be empty. ', 'rfq'));
	echo $rfq_els->radio('rfq_ftp_connect', array('1'=>'FTP','2'=>'FTPS (SSL)'), __('FTP connect', 'rfq'), $default = '1');
	echo $rfq_els->descr(__('Please enter your FTP credentials, if the system was unable to acomplish installation [see on top of FTP section for more description]. It\'s required to properly access your web server for various plugin tasks. If you do not remember your credentials, you should contact your web host.', 'rfq'));

	echo $rfq_els->unit_frame_end();

	echo $rfq_els->unit_title(__('Revert to defaults', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->checkbox('revert2defs', __('Revert all to defaults', 'rfq'));
	echo $rfq_els->descr(__('Since checked then simple save.', 'rfq'));

	echo $rfq_els->unit_frame_end();

	?>
</div>
<div id="setup" class="rfqtab">
	<?php

	echo $rfq_els->unit_title(__('Leave these fields as is', 'rfq'));	
	echo $rfq_els->descr(__('Change the defaults with care, following instractions, else leave these fields as is.', 'rfq'));
	echo $rfq_els->unit_frame();
	echo $rfq_els->textinput('rfq_bounds_title', __('Title bound element', 'rfq'), $default = 'h1');
	/*echo $rfq_els->textinput('rfq_bounds_article', __('Article bound element');*/
	echo $rfq_els->descr(__('Set the bound tag of article title to this field. Usualy that would be h1 or h2 [by default is set to h1].<br/>To find out what bound tag uses your theme, simply click right mouse button on article title and inspect by browser suitable menu action.', 'rfq'));
	
	$def = '<div class="rfq" title="'. __('Send / Download selections you\'ve maked', 'rfq').'."><input id="rfq%s" class="rfqChkbox" type="checkbox" value="1" autocomplete="off" name="rfq%s"></div>';
	echo $rfq_els->textarea('rfq_inject_fragment', __('Button expresion', 'rfq'), $def, $def);
	echo $rfq_els->descr(__('Set button inject expresion, if failure, don\'t worry, simple clear this text area and save. Defaults will be reinstalled.', 'rfq'));

	echo $rfq_els->unit_frame_end();
	

	$path=HOME_PATH . 'wp-content/themes/';
	$dir=scandir($path);
	$r=array();
	$r["-"]="";
	foreach($dir as $v){ if ($v != "." && $v != ".."){ if(is_dir($path.$v)){ if ($v != "downloads"){ $r[$v]=$v; } } } }
	echo $rfq_els->unit_title(__('On what theme want to run Selecto plugin', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->select('rfq_add_theme', __('Activate Selecto plugin', 'rfq'), $r, $default = 'twentytwelve');
	echo $rfq_els->descr(__('Select theme where is allowed to run Selecto plugin.<br/>Note: can not set the same theme to allow and disallow running of plugin.', 'rfq'));

	echo $rfq_els->unit_frame_end();


	echo $rfq_els->unit_title(__('From what theme want to remove Selecto plugin', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->select('rfq_remove_theme', __('Dectivate Selecto plugin', 'rfq'), $r, $default = '');
	echo $rfq_els->descr(__('Select theme where is not allowed to run Selecto plugin.<br/>Note: can not set the same theme to allow and disallow running of plugin.', 'rfq'));

	echo $rfq_els->unit_frame_end();
	
	echo $rfq_els->hidden('rfq_active_themes');

?>
</div>
<div id="actions" class="rfqtab">
<?php

	echo $rfq_els->unit_title(__('Download limits for guests', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->checkbox('downloads', __('Downloads', 'rfq'), 1);
	echo $rfq_els->descr(__('Allow downloads.', 'rfq'));
	
	echo $rfq_els->textinput('downloads_limit', __('Downloads limit', 'rfq'), '10');
	echo $rfq_els->descr(__('Downloads number limit [set negative for no limit].', 'rfq'));

	echo $rfq_els->checkbox('download_unzip', __('Download of unzipped listing', 'rfq'), 1);
	echo $rfq_els->descr(__('Allow download selections as unzipped listing [to increase interactivity]. Else allowed to download only zipped listings.', 'rfq'));
	
	echo $rfq_els->textinput('download_listing_quota', __('Download listing max items', 'rfq'), '10');
	echo $rfq_els->descr(__('Maximum quantity of selected items in listing allowed to download. Set negative for no limit.', 'rfq'));

	echo $rfq_els->unit_frame_end();
	
	
	echo $rfq_els->unit_title(__('Download limits for users', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->checkbox('udownloads', __('Downloads', 'rfq'), 1);
	echo $rfq_els->descr(__('Allow downloads.', 'rfq'));
	
	echo $rfq_els->textinput('udownloads_limit', __('Downloads limit', 'rfq'), '100');
	echo $rfq_els->descr(__('Downloads number limit [set negative for no limit].', 'rfq'));

	echo $rfq_els->checkbox('udownload_unzip', __('Download of unzipped listing', 'rfq'), 1);
	echo $rfq_els->descr(__('Allow download selections as unzipped listing [to increase interactivity]. Else allowed to download only zipped listings.', 'rfq'));
	
	echo $rfq_els->textinput('udownload_listing_quota', __('Download listing max items', 'rfq'), '100');
	echo $rfq_els->descr(__('Maximum quantity of selected items in listing allowed to download. Set negative for no limit.', 'rfq'));

	echo $rfq_els->unit_frame_end();
	
	
	echo $rfq_els->unit_title(__('Undo all limits for admins', 'rfq'));	
	echo $rfq_els->unit_frame();
	echo $rfq_els->checkbox('admin_nolimits', __('Undo limits', 'rfq'), 1);
	echo $rfq_els->descr(__('Undo all limits for admins.', 'rfq'));

	echo $rfq_els->unit_frame_end();
	
	
	
	?>
</div>

<script type="text/javascript">
jQuery("#rfq_add_theme").on("change", function(){
	if(jQuery("#rfq_remove_theme").val() == jQuery(this).val()){
		alert("<?php _e('Note: can not set the same theme to allow and disallow running of plugin.'); ?>");
		jQuery("#rfq_remove_theme").val('');
	}
});
jQuery("#rfq_remove_theme").on("change", function(){
	if(jQuery("#rfq_add_theme").val() == jQuery(this).val()){
		alert("<?php _e('Note: can not set the same theme to allow and disallow running of plugin.'); ?>");
		jQuery("#rfq_add_theme").val('');
	}
});
</script>

<?php  
$rfq_els->admin_footer();

