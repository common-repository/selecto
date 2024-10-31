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

defined('_WPEXEC') or die('Restricted access');

class RFQ_Elements {
	protected $form='rfq_admin_main'; 
	protected $options=''; 
	protected $is_init=false; 
	
	function __construct() {
		add_action('init', array($this, 'init'));
	}

	function init() {
		if (isset($_GET['rfq_reset_defaults'])) {
		}
		$this->get_options();

		if (is_admin()) {
			add_action('admin_print_scripts', array($this, 'load_scripts'));
			add_action('admin_print_styles', array($this, 'load_styles'));
		}
	}

	function get_options() {
		$this->options = get_option($this->form);
		if(!$this->options || ($this->options && !is_array($this->options))){ $this->is_init = true; }
	}
	function reset_defaults() {
		foreach (get_rfq_options_arr() as $opt) {
			delete_option($opt);
		}
		rfq_defaults();

		rfq_title_test();
	}

	function admin_footer($submit = true) {
		if ($submit) {
			?>
			<div class="submit"><input type="submit" class="button-primary" name="submit"
									   value="<?php _e("Save Settings", 'rfq'); ?>"/></div>
		<?php } ?>
		</form>
		</div>
		</div>
	<?php
	}

	function load_styles() {
		wp_enqueue_style('rfq-admin-css', RFQ_URL . 'assets/css/admin.css', RFQ_VERSION);
	}
	function load_scripts() {
		wp_enqueue_script('rfq-admin-script', RFQ_URL . 'assets/js/admin.js', false, RFQ_VERSION, true);
		//wp_enqueue_script('rfq-admin-script', RFQ_URL . 'assets/js/jquery.js', false, RFQ_VERSION, true);
	}
	//..$empty_def => if non empty will be used to fill the value of $this->options[$var] if it is empty.
	function opt($var, $default, $empty_def = '') {
	
		//if (!$this->options || !isset($this->options[$var])){ 
		if ($this->is_init){ 
			if($default) { $this->options[$var] = $default; }
			else $this->options[$var] = ''; 
		}
		if(!isset($this->options[$var])){ $this->options[$var] = ''; }
		if($empty_def){
			if(!$this->options[$var]){ $this->options[$var] = $empty_def; }
		}
		return esc_attr($this->options[$var]);
	}
	function checkbox($var, $label, $default = '', $label_left = true) {

		$this->opt($var, $default);

		if($this->options[$var] == true){ $this->options[$var] = 'on'; }

		if ($label_left !== false) {
			$output_label = '<label class="rfq_checkbox" for="' . esc_attr($var) . '">' . $label . ': &nbsp; </label>';
			$output_label = '<div class="label_spcr">'.$output_label.'</div>';
			$class        = 'rfq_checkbox';
		} else {
			$output_label = '<label for="' . esc_attr($var) . '">' . $label . '</label>';
			$class        = 'rfq_checkbox left';
		}
		$output_input = "<input class='$class' type='checkbox' id='" . esc_attr($var) . "' name='" . esc_attr($this->form) . "[" . esc_attr($var) . "]' " . checked($this->options[$var], 'on', false) . ' value="1" />';

		if ($label_left !== false) {
			$output = $output_label . $output_input;
		} else {
			$output = $output_input . $output_label;
		}
		return $output . '<br class="clear" />';
	}
	function textinput($var, $label, $default = '', $empty_def = '') {

		$val = $this->opt($var, $default, $empty_def);

		return '<div class="label_spcr"><label class="rfq_textinput" for="' . esc_attr($var) . '">' . $label . ': &nbsp; </label></div><input class="rfq_textinput" type="text" id="' . esc_attr($var) . '" name="' . $this->form . '[' . esc_attr($var) . ']" value="' . $val . '"/>' . '<br class="clear" />';
	}
	function textarea($var, $label, $default = '', $empty_def = '', $class = '') {

		$val = $this->opt($var, $default, $empty_def);
		return '<div class="label_spcr"><label class="rfq_textarea" for="' . esc_attr($var) . '">' . esc_html($label) . ': &nbsp; </label></div><textarea class="rfq_textarea ' . $class . '" id="' . esc_attr($var) . '" name="' . $this->form . '[' . esc_attr($var) . ']">' . $val . '</textarea>' . '<br class="clear" />';
	}
	function hidden($var, $default = '') {

		$val = $this->opt($var, $default);
		return '<input type="hidden" id="hidden_' . esc_attr($var) . '" name="' . $this->form . '[' . esc_attr($var) . ']" value="' . $val . '" >';
	}
	function select($var, $label, $values, $default = '') {

		$var_esc = $this->opt($var, $default);
		$field_esc = esc_attr($var); 

		$output  = '<div class="label_spcr"><label class="rfq_select" for="' . $field_esc . '">' . $label . ': &nbsp; </label></div>';
		$output .= '<select class="rfq_select" name="' . $this->form . '[' . $field_esc . ']" id="' . $field_esc . '">';

		foreach ($values as $value => $txt) {
			$sel = '';
			if ($var_esc == $value){ $sel = 'selected="selected" '; }

			$output .= '<option ' . $sel . 'value="' . esc_attr($value) . '">' . $txt . '</option>';
		}
		$output .= '</select>';
		return $output . '<br class="clear"/>';
	}
	function radio($var, $values, $label, $default = '') {
		$var_esc = $this->opt($var, $default);

		$output = '<br/><div class="label_spcr"><label class="rfq_select">' . $label . ': &nbsp; </label></div>';
		foreach ($values as $key => $value) {
			$key = esc_attr($key);
			$output .= '<input type="radio" class="rfq_radio" id="' . $var . '-' . $key . '" name="' . esc_attr($this->form) . '[' . $var . ']" value="' . $key . '" ' . ($this->options[$var] == $key ? ' checked="checked"' : '') . ' /> <label class="rfq_radio" for="' . $var . '-' . $key . '">' . esc_attr($value) . '</label>';
		}
		$output .= '<br/>';

		return $output;
	}
	function unit_error($txt) {
		return  '<h2 class="rfq-opt-error">' . $txt . '</h2>';
	}
	function unit_title($txt) {
		return  '<h2 class="rfq-opt-title">' . $txt . '</h2>';
	}
	function unit_frame() {
		return '<div class="unit_frame">';
	}
	function unit_frame_end() {
		return '</div>';
	}
	function descr($txt) {
		return '<p class="rfq_descript">' . $txt . '</p>';
	}
	function postbox($id, $title, $content) {
		?>
		<div id="<?php echo esc_attr($id); ?>" class="rfqbox">
			<h2><?php echo $title; ?></h2>
			<?php echo $content; ?>
		</div>
		<?php		
	}
} 
global $rfq_els;
$rfq_els = new RFQ_Elements();
