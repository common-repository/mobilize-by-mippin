<?php
/*
Plugin Name: Mobilize by Mippin Wordpress Plugin
Plugin URI: http://code.google.com/p/mippin-dev/wiki/WordPressPlugin
Description: This plugin will detect cell phones, and redirect to Mippin.com where your content is perfectly rendered using your Blog's RSS feed content.  If the device is a PC then it renders as normal.
Version: 1.0
Author: Robin Jewsbury based on Mike Rowehl's original mobile plugin
Author URI: http://m.mippin.com
*/

/*  Copyright 2008 (email : info@Mippin.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */


function mippin_rss_url() {
    $alt_base = get_option('mippin_alternatebaseurl');
    if ( $alt_base && !empty($alt_base) ) {
        return 'http://' . $alt_base . $_SERVER['REQUEST_URI'];
    }
	/* now the live version */
    return 'http://mippin.com/link/mobilize.jsp?url=' . urlencode($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
}



function mippin_redirect() {

	$isMobile = false;

	$op = strtolower($_SERVER['HTTP_X_OPERAMINI_PHONE']);
	$no = strtolower($_SERVER['HTTP_X_MOBILE_GATEWAY']);
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$ac = strtolower($_SERVER['HTTP_ACCEPT']);
	$ip = $_SERVER['REMOTE_ADDR'];

	$isMobile = strpos($ac, 'application/vnd.wap.xhtml+xml') !== false
        || $op != ''
        || $no != '' 
        || strpos($ua, 'sony') !== false 
        || strpos($ua, 'iphone') !== false 
        || strpos($ua, 'ipod') !== false 
        || strpos($ua, 'moto') !== false 
        || strpos($ua, 'motorola') !== false 
        || strpos($ua, 'nec') !== false 
        || strpos($ua, 'netfront') !== false 
        || strpos($ua, 'novarra-vision') !== false 
        || strpos($ua, 'opera mini') !== false 
        || strpos($ua, 'untrusted') !== false 
        || strpos($ua, 'sagem') !== false 
        || strpos($ua, 't-mobile') !== false 
        || strpos($ua, 'symbian') !== false 
        || strpos($ua, 'nokia') !== false 
        || strpos($ua, 'samsung') !== false 
        || strpos($ua, 'mobile') !== false
        || strpos($ua, 'windows ce') !== false
        || strpos($ua, 'epoc') !== false
        || strpos($ua, 'opera mini') !== false
        || strpos($ua, 'nitro') !== false
        || strpos($ua, 'j2me') !== false
        || strpos($ua, 'midp-') !== false
        || strpos($ua, 'cldc-') !== false
        || strpos($ua, 'netfront') !== false
        || strpos($ua, 'mot') !== false
        || strpos($ua, 'up.browser') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'audiovox') !== false
        || strpos($ua, 'blackberry') !== false
        || strpos($ua, 'ericsson,') !== false
        || strpos($ua, 'panasonic') !== false
        || strpos($ua, 'philips') !== false
        || strpos($ua, 'sanyo') !== false
        || strpos($ua, 'sharp') !== false
        || strpos($ua, 'sie-') !== false
        || strpos($ua, 'portalmmm') !== false
        || strpos($ua, 'blazer') !== false
        || strpos($ua, 'avantgo') !== false
        || strpos($ua, 'danger') !== false
        || strpos($ua, 'palm') !== false
        || strpos($ua, 'series60') !== false
        || strpos($ua, 'palmsource') !== false
        || strpos($ua, 'pocketpc') !== false
        || strpos($ua, 'smartphone') !== false
        || strpos($ua, 'rover') !== false
        || strpos($ua, 'ipaq') !== false
        || strpos($ua, 'au-mic,') !== false
        || strpos($ua, 'alcatel') !== false
        || strpos($ua, 'ericy') !== false
        || strpos($ua, 'up.link') !== false
        || strpos($ua, 'vodafone/') !== false
        || strpos($ua, 'wap1.') !== false
        || strpos($ua, 'wap2.') !== false;

	if (strpos($ua, 'Intel Mac OS X') !== false
		|| strpos($ua, 'PPC Mac OS X') !== false
        || strpos($ua, 'Mac_powerPC') !== false
        || strpos($ua, 'SunOS') !== false
        || strpos($ua, 'Windows NT') !== false
        || strpos($ua, 'Windows 98') !== false
        || strpos($ua, 'WinNT') !== false )
			$isMobile = false;

	 // PHP Code to redirect to Mippin: 

	if($isMobile){
	    $id = get_option('mippin_id');
	    if ( $id && !empty($id) && ($id !== '') ) {
	       header('Location: ' . 'http://mippin.com/mip/prev/list.jsp?id=' . $id);
	    }
	   else {
		   header('Location: ' . mippin_rss_url());
	   }
	   exit();
	}

}


function mippin_admin() {
	if (function_exists('add_submenu_page')) {
		add_options_page('Mippin Setup', 'Mippin', 10, basename(__FILE__), 'mippin_admin_page');
	}
}


function mippin_admin_page() {
	if (isset($_POST['mippin_options_submit'])) {
		update_option('mippin_id', $_POST['mippin_id']);
		update_option('mippin_alternatebaseurl', $_POST['mippin_alternatebaseurl']);

		echo '<div id="message" class="updated fade"><p><strong>';
		_e('Options saved.');
		echo '</strong></p></div>';
	}

	$id = get_option('mippin_id');
	if ($id === false) {
		$id = '';
	}

	$altbaseurl = get_option('mippin_alternatebaseurl');
	if ($altbaseurl === false) {
		$altbaseurl = '';
    }

?>
	<div class="wrap">
	<h2>Mippin Options Page</h2>

	<form name="mippin_options_form" action="<?php echo $_SERVER['PHP_SELF'] . '?page=' . basename(__FILE__); ?>" method="post">
	<ul style="width:75%">
	<li>This Mippin plugin is configuration free.  It will just work once the plugin it is activated.  However, it is possible to change some settings, both here and in <a href="http://mippinmaker.com/app/faces/jsp/manageMippsites.xhtml" target="_new" >MippinMaker</a> if you want.  MippinMaker allows you to change the mobile rendering (colours and graphics used and allows you to monetize your mobile version with ads).</li>
    <li><strong>RSS Feed Url</strong>: <input type="text" name="mippin_alternatebaseurl" value="<?php echo $altbaseurl;?>" /> (optional)<br />
    Mippin uses your site's RSS feed to get the content from your posts. Mippin finds the RSS feed automatically, but if you need it to use a different url enter one here.</li>
	<li><strong>Mippin ID</strong>: <input type="text" name="mippin_id" id="mippin_id" value="<?php echo $id;?>" /> (optional for experts only)<br />
    If there is more than one version of your site on Mippin or you want the redirect to Mippin to be much faster, you may need want to enter its Mippin ID.  Use  <a href="http://mippinmaker.com/app/faces/jsp/manageMippsites.xhtml" target="_new" >MippinMaker</a> to change the colors and graphic shown on the cell phone version of your site.</li>
	</ul>
	<div class="submit" style="float:right">
	<input type="submit" name="mippin_options_submit" value="<?php _e('Update Options &raquo;') ?>"/>
	</div>
	</form>
<?php
}

function mippin_widget_init() {
  if ( !function_exists('register_sidebar_widget') ) {
    return;
  }

  function mippin_widget($args) {
    extract($args);
    echo $before_widget;
    echo $before_title.'Mobile Version'.$after_title;
    echo "<a href='" . mippin_rss_url() . "'>Switch to mobile view:</a>";
    echo "<a href='" . mippin_rss_url() . "'><img style='border:none;'  src='http://www.mippin.com/app/images/blogger_button.gif' /></a><br />";
    echo $after_widget;
  }
  register_sidebar_widget('Mippin Widget', 'mippin_widget');
}

add_action('template_redirect', 'mippin_redirect');
add_action('admin_menu', 'mippin_admin');
add_action('plugins_loaded', 'mippin_widget_init');

?>
