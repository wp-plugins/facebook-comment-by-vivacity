<?php
/**
* Plugin Name: Facebook Comments by Vivacity
* Plugin URI: http://www.vivacityinfotech.com
* Description: A simple Facebook Comments plugin for your site.It is a social plugin for enables Facebook users commenting on your site.
* Version: 1.0.0
* Author: Vivacity Infotech Pvt. Ltd.
* Author URI: http://www.vivacityinfotech.net
*/

if ( is_admin())
	require 'admin-file.php';
else
	require 'user-file.php';

// Add link - settings on plugin page
function fb_comment($links) {
  $settings_link = '<a href="options-general.php?page=fbcomment">Settings</a>';
 array_unshift($links, $settings_link);
 return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'fb_comment' );

?>