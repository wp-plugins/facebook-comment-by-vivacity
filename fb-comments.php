<?php
/**
* Plugin Name: Facebook Comments by Vivacity
* Plugin URI: http://www.vivacityinfotech.net
* Description: A simple Facebook Comments plugin for your blog that enables FB User`s to comment on your blog or website.
* Version: 1.0.5
* Author: Vivacity Infotech Pvt. Ltd.
* Author URI: http://www.vivacityinfotech.net
Text Domain: facebook-comment-by-vivacity
Domain Path: /languages/
*/
/*
Copyright 2014  Vivacity InfoTech Pvt. Ltd.  (email : support@vivacityinfotech.com)
    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.


    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

 add_action('init', 'load_viva_transl');
    function load_viva_transl()
   {
       load_plugin_textdomain('facebook-comment-by-vivacity', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');
   }
  
if ( is_admin())
	require 'admin-file.php';
else
	require 'user-file.php';
	


// Add link - settings on plugin page
function fb_comment($links) {
  $settings_link = '<a href="options-general.php?page=fbcomment">'. __( "Settings", "facebook-comment-by-vivacity" ).'</a>';
 array_unshift($links, $settings_link);
 return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'fb_comment' );
?>
