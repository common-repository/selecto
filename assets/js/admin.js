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
jQuery(document).ready(function () {var active_tab = window.location.hash.replace("#top#", "");if (active_tab == "") {active_tab = "basic";}jQuery("#" + active_tab).addClass("active");jQuery("#" + active_tab + "-tab").addClass("nav-tab-active");jQuery("#rfq-tabs a").click(function () {jQuery("#rfq-tabs a").removeClass("nav-tab-active");jQuery(".rfqtab").removeClass("active");var id = jQuery(this).attr("id").replace("-tab", "");jQuery("#" + id).addClass("active");jQuery(this).addClass("nav-tab-active");});});