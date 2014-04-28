<?php
/**
* Plugin Name: Facebook Comments by Vivacity
* Plugin URI: http://www.vivacityinfotech.com
* Description: A simple Facebook Comments plugin for your site.It is a social plugin for enables Facebook users commenting on your site.
* Version: 1.0.1
* Author: Vivacity Infotech Pvt. Ltd.
* Author URI: http://www.vivacityinfotech.net
*/
/*
Copyright 2014  Vivacity InfoTech Pvt. Ltd.  (email : vivacityinfotech.jaipur@gmail.com)
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