=== Facebook Comments by Vivacity ===
Contributors: vivacityinfotech.jaipur
Donate link: http://bit.ly/1icl56K
Tags: Facebook Comments, Comments , Social Plugin, Facebook, Comment ,Social, Open Graph, Opengraph, Protocol , XFBML , HTML5 , Moderation , Moderator, shortcode , template , template shortcode , WP comments , WP default comments, admin notification, email notification, admin email notification.
License: GPLv2 or later
Requires at least:3.0
Tested up to:4.1.1
Stable tag: 1.0.7

== Description ==

A simple Facebook Comments plugin for your website.It`s a social plugin for enables Facebook users commenting on your site.There are potions for enable Facebook comments on posts/pages/home/Archive/CPT of your website.

You cab also customize setting for following features:

* Hide/show Facebook comments on posts/pages/home page.
* You can customize Width of comment box.
* You can choose color scheme (Light or Dark)
* Use HTML5 or XFBML versions of Facebook comments.
* Moderation option is also there.
* Shortcode for templates or posts/pages/home page.
* Feature for remove WordPress default comment form on posts/pages.
* Multi-languages support is also available.
* Option for select Facebook comment box language from a bunch of different languages to display Facebook comment box into your native language.
* POT file is added for plugin translation into your languages.
* Option for add class-name for title and count-text.
* Hide/show Fb comments box on Archive pages.
* Option for exclude Fb comment box using page/post/CPT id's.
* New Admin email notification feature, admin will be notified via email if there is new comment on admin's website.

= Translators =

* English(US) (en_Us) - [Team Vivacity](http://vivacityinfotech.net/)
* French (fr_FR) - [Team Vivacity](http://vivacityinfotech.net/)
* Bosnian (bs_BA) - [Ogi Djuraskovic](http://firstsiteguide.com/)
* Spain - Spanish (es-ES) - [Ogi Djuraskovic](http://firstsiteguide.com/)
* Serbian (sr_RS) - [Team Vivacity](http://vivacityinfotech.net/)


If you have created your own language pack, or have an update of an existing one, you can send [gettext PO and MO files](http://codex.wordpress.org/Translating_WordPress) to [Us](http://vivacityinfotech.net/contact-us/) so that We can bundle it into this plugin.
You can download the latest [POT file](http://plugins.svn.wordpress.org/facebook-comment-by-vivacity/tags/1.0.3/languages/facebook-comment-by-vivacity.pot), and [PO files in each language](http://plugins.svn.wordpress.org/facebook-comment-by-vivacity/tags/1.0.3/languages/).


= Rate Us / Feedback =

Please take the time to let us and others know about your experiences by leaving a review, so that we can improve the plugin for you and other users.

= Want More? =

If You Want more functionality or some modifications, just drop us a line what you want and We will try to add or modify the plugin functions.

If you like the plugin please [Donate here](http://bit.ly/1icl56K). 


== Installation ==

1. Download the "Facebook Comments by Vivacity" Plugin (Plugin zip file).
2. Extract it in the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. (Optional) Customize the plugin in the Settings > FB Comments menu

Visit our <a href="http://vivacityinfotech.net/blog/wp-facebook-comments-by-vivacity/">demo page</a> for more information.

== Frequently Asked Questions ==

= Issues with display =
* Please update to latest version. Up-to-date API's are available.

= How to hide Facebook Comment Box position on any page if checkbox enabled for all pages =
You can remove Facebook Comment Box from some pages , if "Pages " checkbox enabled for pages. For this please insert page ids separate them with commas,[ like 5, 21] in "Exclude Using Page IDs" field.

You can remove Facebook like button from some pages , if "show on page " checkbox enabled for pages. For this please insert page ids separate them with commas,[ like 5, 21] in "Exclude Using Page IDs" field.

= How can add Facebook Comments into templates  =
You can insert echo do_shortcode('[vivafbcomment]'); code into your templates for use this shortcode.

You can also use below options to override the the settings used above.

* url - leave blank for current URL
* width - minimum must be 350
* num - number of comments
* count - comment count on/off
* scheme - color scheme: light/dark
* pluginsite - enter "1" to link to the plugin

For Example:

[vivafbcomment url="http://vivacityinfotech.net/" width="375" count="on" num="6" countmsg="awesome comments"]

= How can get new comment notification via email? =
Admin will be notified via email if there is new comment on admin's website. For this feature we have new textbox field ('Notification Email Id') in back-end in which you can insert email id on which email will be send for new comments. If this option field is empty, email will be send to your default email id.
== Screenshots ==

1. "Facebook Comments by Vivacity" plugin installed and appears in the plugins menu.
2. "Facebook Comments by Vivacity" Settings Section.
3. Displaying Facebook Comments on home page.
4. Displaying Facebook Comments on page.

== Changelog ==

= 1.0.7 =
* New Admin email notification feature, admin will be notified via email if there is new comment on admin's website.
* Added options for add CSS classes to titles.
* Optional checkbox for hide/show Fb comments box on Archive pages.
* Updated option for exclude Fb comment box using id's. Now you can control accessibility of FB comment-box on pages/posts/CPT by providing id's.
* Updated pot file for language support.
* Added new po/mo file for Serbian(sr_RS) and Bosnian(bs_BA) language support.

= 1.0.6 =
Now you can control accessibility of Facebook comment box on pages by providing page id's.

= 1.0.5 =
* Added CSS classes to titles.
* Included new upgraded script for Facebook Comments.

= 1.0.4 =
* Added po/mo file for Serbian(sr_RS) and Spain-Spanish (es-ES) language support.

= 1.0.3 =
* Multi languages support.
* POT file added for translate plugin in your language.
* Option added for select Facebook comment box language from a bunch of different languages to display Facebook comment box into selected language.

= 1.0.2 =
* Feature for remove WordPress default comments on posts/pages.
* shortcode for Facebook Comments.

= 1.0.1 =
* Initial Release of Plugin

