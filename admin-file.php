<?php
//add Jquery to settings page
add_action( 'admin_menu', 'my_admin_plugin' );
function my_admin_plugin() {
    wp_register_script( 'my_plugin_script', plugins_url('/script.js', __FILE__), array('jquery'));
    wp_enqueue_script( 'my_plugin_script' );
}
// ---End -- add Jquery to settings page

// default values
function fbcomment_init(){
	register_setting( 'fbcomment_option', 'fbcomment' );
	$new_fboptn = array(
		'fbml' => 'on',
		'opengraph' => 'off',
		'fbns' => 'off',
		'html5' => 'on',
		'posts' => 'on',
		'pages' => 'off',
		'homepage' => 'off',
		'appID' => '',
		'mods' => '',
		'num' => '6',
		'count' => 'on',
		'countmsg' => 'comments',
		'title' => 'Comments',
		'width' => '500',
		'pluginsite' => 'off',
		'scheme' => 'light',
		'hideWpComments' => 'off',
		'postshideWpComments' => 'off',
		'pageshideWpComments' => 'off',
		'selected_types' => 'selected_types'
		
	
	);

	// if old fboptn exist, update to array
	foreach( $new_fboptn as $key => $value ) {
		if( $existing = get_option( 'fbcomment_' . $key ) ) {
			 $new_fboptn[$key] = $existing;
			delete_option( 'fbcomment_' . $key );
		}

	}
	add_option( 'fbcomment', $new_fboptn );
}
add_action('admin_init', 'fbcomment_init' );

// Add sub menu [FB Comments] page to the Settings menu. 
function show_fbcomment_option() {
	add_options_page('FB Comments', 'FB Comments', 'manage_options', 'fbcomment', 'fbcomment_option');
}
add_action('admin_menu', 'show_fbcomment_option');

// Error message
function fbcomments_msg(){
 $fboptn = get_option('fbcomment');
// print_r($fboptn);
 
  	if ($fboptn['appID'] == "") {
	$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
      echo '<div class="error">
            <p>Please Enter Your Facebook App ID. Required for Fb comments. <a href="'.$fb_adminUrl.'">Click here</a></p>
            </div>';
   }  
}
add_action('admin_notices', 'fbcomments_msg');

 
// Admin Settings

function fbcomment_option() {
	?>
<link href="<?php echo plugins_url( 'css/style.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">


 <div class="wrap">
 
  <div class="top">
  <h3>FB Comments Plugin <small>by <a href="http://www.vivacityinfotech.com" target="_blank">Vivacity Infotech Pvt. Ltd.</a>
  </h3>
    </div> <!-- ------End of top-----------  -->
    
	<div class="inner_wrap">
	 <div class="left">
			
<form method="post" action="options.php" id="options">
			<?php settings_fields('fbcomment_option'); ?>
			<?php $fboptn = get_option('fbcomment'); 

if (!isset($fboptn['fbml'])) {$fboptn['fbml'] = "";}
if (!isset($fboptn['fbns'])) {$fboptn['fbns'] = "";}
if (!isset($fboptn['opengraph'])) {$fboptn['opengraph'] = "";}
if (!isset($fboptn['html5'])) {$fboptn['html5'] = "";}
if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "";}
if (!isset($fboptn['posts'])) {$fboptn['posts'] = "";}
if (!isset($fboptn['pages'])) {$fboptn['pages'] = "";}
if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "";}
if (!isset($fboptn['count'])) {$fboptn['count'] = "";}
if (!isset($fboptn['countmsg'])) {$fboptn['countmsg'] = "";}
if (!isset($fboptn['jquery'])) {$fboptn['jquery'] = "";}
if (!isset($fboptn['hideWpComments'])) {$fboptn['hideWpComments'] = "selected_types";}
if (!isset($fboptn['selected_types'])) {$fboptn['selected_types'] = "";}
if (!isset($fboptn['postshideWpComments'])) {$fboptn['postshideWpComments'] = "";}
if (!isset($fboptn['pageshideWpComments'])) {$fboptn['pageshideWpComments'] = "";}


?>		
<!-- get domain name -->
<?php  $domainname = get_option('siteurl');
$domainname = str_replace('http://', '', $domainname);
$domainname = str_replace('www.', '', $domainname);?>
<!-- end get domain name -->

<!-- facebook App Id settings -->
		
<?php if ($fboptn['appID'] == "") { 
?>
<div class="error">
<h3 class="setup">Set Up Your Facebook App ID</h3> <!-- ----Set Up Your Facebook App ID -->
	<table class="form-table admintbl">
	  <tr>
	   <th>
      <strong><a href="https://developers.facebook.com/apps" target="_blank">Create an App</a></strong><br>
      <small>To get App Id click on "<a href="https://developers.facebook.com/apps" target="_blank">Create an App</a>".</small>
      </th>
	   <td><small> Enter App Id into below textbox.</small><br>
	       <input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" />
	        <strong>APP ID</strong><br>
      </td>
	  </tr>
	</table> <!-- -----End Set Up Your Facebook App ID -->
</div>
<?php } else { ?>
	<h3 class="title" id="fbappsettings">Facebook App Setting</h3> <!-- ----Facebook App Setting -->
	<div id="fbappsettingstbl" class="togglediv">
	<table class="form-table admintbl">
		<tr>
		  <th><small>To edit your App ID click on below link:</small><br>
		  <strong><a href="https://developers.facebook.com/apps<?php if ($fboptn['appID'] != "") { echo "/".$fboptn['appID']."/summary"; } ?>" target="_blank">Your App Setup</a></strong></th>
		  <td><small>choose your App and click <strong>Edit Settings</strong>. Please Enter <strong><?php echo $domainname; ?></strong> in both "App Domains" and "Site URL"</small></td>
		</tr>
		<tr>
		 <th>
		 <a href="https://developers.facebook.com/apps" target="_blank">Create a New App</a>     
		 </th>
		 <td><small>If you want to set up a new App Id click <strong>Create a New App</strong> </small>
		 </td>
		</tr>
	</table> <!-- -----End Facebook App Setting----- -->
	</div>
<?php } ?>	
	<h3 class="title" id="mainsettings">Main Settings</h3> <!-- -----Main Settings -->
	<div  id="mainsettingstbl" class="togglediv">
	<table class="form-table admintbl">
	
<?php if ($fboptn['appID']!="") { ?>
		<tr><th><label for="appID">Facebook App ID</label></th>
			 <td><input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" /></td>
		</tr>
				
<?php } ?>
		<tr><th><label for="fbml">Enable XFBML</label></th>
			<td><input id="fbml" name="fbcomment[fbml]" type="checkbox" value="on" <?php checked('on', $fboptn['fbml']); ?> /> <small>only disable this if you already have XFBML enabled elsewhere</small></td>
		</tr>
		<tr><th><label for="fbns">Use Facebook NameServer</label></th>
			<td><input id="fbns" name="fbcomment[fbns]" type="checkbox" value="on" <?php checked('on', $fboptn['fbml']); ?> /> <small>only enable this if Facebook Comments do not appear</small></td>
		</tr>
		<tr><th><label for="opengraph">Use Open Graph NameServer</label></th>
			<td><input id="opengraph" name="fbcomment[opengraph]" type="checkbox" value="on" <?php checked('on', $fboptn['opengraph']); ?> /> <small>only enable this if Facebook comments are not appearing, not all information is being passed to Facebook or if you have not enabled Open Graph elsewhere within WordPress</small></td>
		</tr>
		<tr><th><label for="html5">Use HTML5</label></th>
			<td><input id="html5" name="fbcomment[html5]" type="checkbox" value="on" <?php checked('on', $fboptn['html5']); ?> /></td>
		</tr>
		<tr><th><label for="pluginsite">Show plugin site url:</label></th>
		   <td><input id="credit" name="fbcomment[pluginsite]" type="checkbox" value="on" <?php checked('on', $fboptn['pluginsite']); ?> />   <small>only enable this if you want to show plugin site.</small>
		</td>
		</tr>
   </table> 
   </div> <!-- ------End Main Settings--------- -->
	
	<h3 id="displaysettings" class="title">Display Settings</h3> <!-- ---Display Settings -->
	<div id="displaysettingstbl" class="togglediv">
	 <table class="form-table admintbl">
		<tr><th><label for="posts">Posts</label></th>
			<td><input id="posts" name="fbcomment[posts]" type="checkbox" value="on" <?php checked('on', $fboptn['posts']); ?> /></td>
		</tr>
		<tr><th><label for="pages">Pages</label></th>
			 <td><input id="pages" name="fbcomment[pages]" type="checkbox" value="on" <?php checked('on', $fboptn['pages']); ?> /></td>
		</tr>
		<tr><th><label for="homepage">Home</label></th>
			<td><input id="home" name="fbcomment[homepage]" type="checkbox" value="on" <?php checked('on', $fboptn['homepage']); ?> />
			</td>
		</tr>
		<tr><th><label for="scheme">Colour Scheme</label></th>
			<td>
				<select name="fbcomment[scheme]">
					<option value="light" <?php if ($fboptn['scheme'] == 'light') { echo ' selected="selected"'; } ?> >Light</option>
					<option value="dark" <?php if ($fboptn['scheme'] == 'dark') { echo ' selected="selected"'; } ?> >Dark</option>
				</select>
			</td>
		</tr>
		<tr><th><label for="num">Number of Comments</label></th>
			<td><input id="num" type="text" name="fbcomment[num]" value="<?php echo $fboptn['num']; ?>" /> <small> - Default no of comments is <strong>6
			</strong></small>
			</td>
		</tr>
		<tr><th><label for="width">Width</label></th>
			<td><input id="width" type="text" name="fbcomment[width]" value="<?php echo $fboptn['width']; ?>" />px <small> - Default comment box width is <strong>500px</strong></small>
			</td>
		</tr>
		<tr><th><label for="title">Title</label></th>
			<td><input id="title" type="text" name="fbcomment[title]" value="<?php echo $fboptn['title']; ?>" />
			</td>
		</tr>
		<tr><th><label for="count">Show Comment Count</label></th>
			<td><input id="count" name="fbcomment[count]" type="checkbox" value="on" <?php checked('on', $fboptn['count']); ?> />
			</td>
		</tr>
		<tr><th><label for="countmsg">Comment text</label></th>
			<td><input id="countmsg" type="text" name="fbcomment[countmsg]" value="<?php echo $fboptn['countmsg']; ?>" />
			</td>
		</tr>
	</table>
	</div>		<!-- -----End Display Settings---- -->
	
	<h3 id="moderation" class="title">Moderation Settings</h3>   <!-- ------ Moderation--------- --> 
	<div id="moderationtbl" class="togglediv">
   <table class="form-table admintbl">
	<tr><th><a href="https://developers.facebook.com/tools/comments<?php if ($fboptn['appID'] != "") { echo "?id=".$fboptn['appID']."&view=queue"; } ?>" target="_blank">Moderation Area</a></th>
					<td><small>If you are a moderator, you will see notifications within facebook.com. If you don't want to have moderator status, click on "Moderation Area" and use this link to left.</small></td>
	</tr>
		<tr><th><label for="appID">Moderators</label></th>
		<td><input id="mods" type="text" name="fbcomment[mods]" value="<?php echo $fboptn['mods']; ?>" size="50" /><br><small>All admins to the App ID can moderate comments,By default. To add moderators, enter each Facebook User ID by a comma without spaces. To find your Facebook User ID,<a href="https://developers.facebook.com/tools/explorer/?method=GET&path=me" target="blank">click here</a> where you will see your own. To view someone else's, replace "me" with their username in the input provided</small></td>
		</tr>
  </table>
  </div>  <!-- ------End Moderation--------- --> 
  
  
  
  
  
  
  
  
  
 <h3 id="" class="title"> Hide/Show default wp comments</h3>   <!-- ------ default wp comments--------- --> 
	<div id="" class="togglediv">
   <table class="form-table admintbl">

<!-- ------everywhere--------- --> 

<div class="everywhere">
		<tr>
		<th class="rgtth"><input type="radio"  class="mode" id="fbComments_hideWpComments" name="fbcomment[hideWpComments]" onchange="setFries()" value="on" <?php checked('on', $fboptn['hideWpComments']); ?>   /></th>
		<td>
		<label for="fbComments_hideWpComments"> <?php _e('Hide WordPress Default Comments System From Website'); ?></label>

		</td>
		</tr>
		
</div>		
<div class="clr"></div>


<tr>
<th class="rgtth"><input type="radio" class="mode" id="selected_types" name="fbcomment[hideWpComments]" onchange="setFries()" value="selected_types" <?php checked( 'selected_types', $fboptn['hideWpComments']); ?> /> </th>
<td>
<label for="selected_types"> <?php _e('Hide WordPress Default Comments System On Certain Post Types'); ?></label>
</td>
</tr>
  </table>
     <table class="form-table admintbl posts-pages">

<!-- ------post--------- --> 		
		<tr><th><label for="posts_hideWpComments"> <?php _e('Hide WordPress Default Comments System On Posts'); ?></label>
		</th>
		<td>
		<input type="checkbox" class="checkmode" id="posts_hideWpComments" name="fbcomment[postshideWpComments]"  value="on" <?php checked('on', $fboptn['postshideWpComments']); ?>
<?php if($fboptn['hideWpComments'] == 'on'){ ?> disabled="true"  <?php	} ?> />
				</td>
		</tr>
<!-- ------page--------- --> 		
		<tr><th><label for="fbComments_hideWpComments"> <?php _e('Hide WordPress Default Comments System On Pages'); ?></label>
		</th>
		<td>
		<input type="checkbox" class="checkmode" id="pages_hideWpComments" name="fbcomment[pageshideWpComments]"  value="on" <?php checked('on', $fboptn['pageshideWpComments']); ?>
		<?php if($fboptn['hideWpComments'] == 'on'){ ?> disabled="true"  <?php	} ?> />
				</td>
		</tr>
	

		
  </table>
  </div>  <!-- ------End default wp comments--------- --> 

		
		
		
		
		
		
		
			
		<div class="submitform">
			<input type="submit" class="button1" value="<?php _e('Save Changes') ?>" />
		</div>
</form>	

<!-- ---End of facebook App Id settings---- -->

	
			 </div> <!-- --------End of left div--------- -->
 <div class="right">
	<center>
		<div class="emaildiv">
					
		</div>	
			
		<div class="bottom">
		    <h3 id="shortcodedesc-comments" class="title">Shortcode For Templates</h3>
     <div id="shortcodedesctbl-comments" class="togglediv">  
			<table class="right-tbl">
				<tr><td>
<p>You can also insert FB Comment Box  manually in any page or post or <strong>template</strong> by simply using the shortcode <strong>[vivafbcomment]</strong>. <br>
You can insert <strong>echo do_shortcode('[vivafbcomment]');</strong> code into your templates for use this shortcode.
</p>
<p>You can also use below options to override the the settings used above.</p>
<ul>
<li><strong>url</strong> - leave blank for current URL</li>
<li><strong>width</strong> -  minimum must be <strong>350</strong></li>
<li><strong>num</strong> - number of comments</li>
<li><strong>count</strong> - comment count on/off</li>
<li><strong>scheme</strong> - color scheme: light/dark</li>
<li><strong>pluginsite</strong> - enter "1" to link to the plugin</li>
</ul>
<p><strong>For Example:</strong></p>
<p>[vivafbcomment url="http://vivacityinfotech.net/" width="375" count="on" num="6" countmsg="awesome comments"]</p>
			</td>
				</tr>
		</table>
	</div> 
</div>
<div class="bottom">
		    <h3 id="download-comments" class="title">Download Free Plugins</h3>
     <div id="downloadtbl-comments" class="togglediv">  
	<h3 class="company">
<strong>Vivacity InfoTech Pvt. Ltd.</strong>
has following plugins for you :
</h3>
<ul class="">
<li><a target="_blank" href="http://wordpress.org/plugins/wp-twitter-feeds/">WP Twitter Feeds</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-fb-share-like-button/">WP Facebook Like Button</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-facebook-fanbox-widget/">WP Facebook FanBox</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-google-analytics-scripts/">WP Google Analytics Scripts</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-xml-sitemap/">WP XML Sitemap</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-facebook-auto-publish/">WP Facebook Auto Publish</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-twitter-autopost/">WP Twitter Autopost</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-responsive-jquery-slider/">WP Responsive Jquery Slider</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-google-plus-one-button/">WP Google Plus One Button</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-qr-code-generator/">WP QR Code Generator</a></li>
<li><a target="_blank" href="http://wordpress.org/plugins/wp-inquiry-form/">WP Inquiry Form</a></li>

</ul>
  </div> 
</div>		
<div class="bottom">
		    <h3 id="donatehere-comments" class="title">Donate Here</h3>
     <div id="donateheretbl-comments" class="togglediv">  
     <p>If you want to donate , please click on below image.</p>
	<a href="http://tinyurl.com/owxtkmt" target="_blank"><img class="donate" src="<?php echo plugins_url( 'assets/paypal.gif' , __FILE__ ); ?>" width="150" height="50" title="Donate here"></a>		
  </div> 
</div>	
<div class="bottom">
 <h3 id="aboutauthor-comments" class="title">About The Author</h3>
     <div id="aboutauthortbl-comments" class="togglediv">  
	<p> <strong>Vivacity InfoTech Pvt. Ltd. , an ISO 9001:2008 Certified Company,</strong>is a Global IT Services company with expertise in outsourced product development and custom software development with focusing on software development, IT consulting, customized development.We have 200+ satisfied clients worldwide.</p>	
<h3 class="company">
<strong>Vivacity InfoTech Pvt. Ltd.</strong>
has expertise in :
</h3>
<ul class="">
<li>Outsourced Product Development</li>
<li>Customized Solutions</li>
<li>Web and E-Commerce solutions</li>
<li>Multimedia and Designing</li>
<li>ISV Solutions</li>
<li>Consulting Services</li>
<li>
<a target="_blank" href="http://www.lemonpix.com/">
<span class="colortext">Web Hosting</span>
</a>
</li>
 <h3><strong><a target="_blank" href="http://www.vivacityinfotech.com/contactus.html" >Contact Us Here</a></strong></h3>
</ul>
  </div> 
</div>	
	</center>
 </div><!-- --------End of right div--------- -->
</div> <!-- --------End of inner_wrap--------- -->
		
		
  </div> <!-- ---------End of wrap-------- -->
<?php } ?>