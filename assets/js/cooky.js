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
function set_sessionCooky(cookieName, cookieValue) {if (is_sessionCooky_Enabled()) {document.cookie = escape(cookieName) + "=" + escape(cookieValue) + "; path=/";return true;} else {return false;}}function is_sessionCooky_Enabled() {document.cookie = "is_sessionCooky_Enabled=Enabled";if (getCooky("is_sessionCooky_Enabled") == "Enabled") {return true;} else {return false;}}function getCooky(cookieName) {var exp = new RegExp(escape(cookieName) + "=([^;]+)");if (exp.test(document.cookie + ";")) {exp.exec(document.cookie + ";");return unescape(RegExp.$1);} else {return false;}}function is_Cooky_Enabled() {setCooky("is_Cooky_Enabled", "Enabled", "m", 1);if (getCooky("is_Cooky_Enabled") == "Enabled") {return true;} else {return false;}}function setCooky(CookieName, CookieValue, periodType, offset) {var expireDate = new Date;offset = offset / 1;switch (periodType) {case "Y":var year = expireDate.getYear();if (year < 1000) {year = year + 1900;}expireDate.setYear(year + offset);break;case "M":expireDate.setMonth(expireDate.getMonth() + offset);break;case "D":expireDate.setDate(expireDate.getDate() + offset);break;case "H":expireDate.setHours(expireDate.getHours() + offset);break;case "m":expireDate.setMinutes(expireDate.getMinutes() + offset);break;default:document.cookie = escape(CookieName) + "=" + escape(CookieValue) + "; path=/";return;}document.cookie = escape(CookieName) + "=" + escape(CookieValue) + "; expires=" + expireDate.toGMTString() + "; path=/";}function deleteCookie(cookieName) {if (getCooky(cookieName)) {setCooky(cookieName, "Pending delete", "years", -1);}return true;}