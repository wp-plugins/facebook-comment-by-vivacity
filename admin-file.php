<?php
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
		'num' => '10',
		'count' => 'on',
		'countmsg' => 'comments',
		'title' => 'Comments',
		'titleclass' => '',
		'width' => '500',
		'countstyle' => '',
		'pluginsite' => 'off',
		'scheme' => 'light'
		
	
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
            <p>Please Enter Facebook App ID. Required for Fb comments. <a href="'.$fb_adminUrl.'"><input type="submit" value="App ID" class="button2" /></a></p>
            </div>';
   }  
}
add_action('admin_notices', 'fbcomments_msg');



// Admin Settings
function fbcomment_option() {
?>
<link href="<?php echo plugins_url( 'style.css' , __FILE__ ); ?>" rel="stylesheet" type="text/css">
 <div class="wrap">
 
  <div class="top">
  <h3>FB Comments For Wp Plugin <small>by <a href="http://www.vivacityinfotech.com" target="_blank">Vivacity Infotech Pvt. Ltd.</a>
  </h3>
    </div> <!-- ------End of top-----------  -->
    
	<div class="inner_wrap">
			
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
if (!isset($fboptn['jquery'])) {$fboptn['jquery'] = "";}
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
	<h3 class="title">Set Up Your Facebook App ID</h3> <!-- ----Set Up Your Facebook App ID -->
	<table class="admintbl">
	  <tr>
	   <th>To get an App ID: <strong><a href="https://developers.facebook.com/apps" target="_blank">Create an App</a></strong></th>
	   <td><small>click <strong>+ <a href="https://developers.facebook.com/apps" target="_blank">Create New App</a></strong> to the top menu [Apps] of the facebook page. Name the App like "Comments" and give it an app namespace. Once you have it enter it here:</small><br><strong>APP ID: </strong>
	   <input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" /><br><br>
      </td>
	  </tr>
	</table> <!-- -----End Set Up Your Facebook App ID -->
</div>
<?php } else { ?>
	<h3 class="title">Facebook App Setting</h3> <!-- ----Facebook App Setting -->
	<table class="admintbl">
		<tr>
		  <th>To setup your App ID: <strong><a href="https://developers.facebook.com/apps<?php if ($fboptn['appID'] != "") { echo "/".$fboptn['appID']."/summary"; } ?>" target="_blank">Your App Setup</a></strong></th>
		  <td><small>choose your App and click <strong>Edit Settings</strong>. Please Enter <strong><?php echo $domainname; ?></strong> in both "App Domains" and as the "Site URL"</small></td>
		</tr>
		<tr>
		 <th>
		 <a href="https://developers.facebook.com/apps" target="_blank">Create a New App</a>     
		 </th>
		 <td><small>If you want to set up a new App Id click <strong>Create a New App</strong> </small>
		 </td>
		</tr>
	</table> <!-- -----End Facebook App Setting----- -->
<?php } ?>	
	<h3 class="title">Main Settings</h3> <!-- -----Main Settings -->
	<table class="admintbl">
	
<?php if ($fboptn['appID']!="") { ?>
		<tr><th><label for="appID">Facebook App ID</label></th>
			 <td><input id="appID" type="text" name="fbcomment[appID]" value="<?php echo $fboptn['appID']; ?>" /></td>
		</tr>
				
<?php } ?>
		<tr><th><label for="fbml">Enable FBML</label></th>
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
		<tr><th><label for="pluginsite">Credit</label></th>
		   <td><input id="credit" name="fbcomment[pluginsite]" type="checkbox" value="on" <?php checked('on', $fboptn['pluginsite']); ?> />   <small>only enable this if you want to show plugin site.</small>
		</td>
		</tr>
   </table> <!-- ------End Main Settings--------- -->
	
<h3 class="title">Moderation</h3>   <!-- ------ Moderation--------- --> 
<table class="admintbl">
	<tr><th><a href="https://developers.facebook.com/tools/comments<?php if ($fboptn['appID'] != "") { echo "?id=".$fboptn['appID']."&view=queue"; } ?>" target="_blank">Moderation Area</a></th>
					<td><small>If you're a moderator you will see notifications within facebook.com. If you don't want to have moderator status, click on "Moderation Area<" and use this link to  left.</small></td>
	</tr>
		<tr><th><label for="appID">Moderators</label></th>
		<td><input id="mods" type="text" name="fbcomment[mods]" value="<?php echo $fboptn['mods']; ?>" size="50" /><br><small>All admins to the App ID can moderate comments,By default. To add moderators, enter each Facebook User ID by a comma without spaces. To find your Facebook User ID,<a href="https://developers.facebook.com/tools/explorer/?method=GET&path=me" target="blank">click here</a> where you will see your own. To view someone else's, replace "me" with their username in the input provided</small></td>
		</tr>
</table>  <!-- ------End Moderation--------- --> 
	
	
	<h3 class="title">Display Settings</h3> <!-- ---Display Settings -->
	 <table class="admintbl">
		<tr><th><label for="posts">Posts</label></th>
			<td><input id="posts" name="fbcomment[posts]" type="checkbox" value="on" <?php checked('on', $fboptn['posts']); ?> /></td>
		</tr>
		<tr><th><label for="pages">Pages</label></th>
			 <td><input id="pages" name="fbcomment[pages]" type="checkbox" value="on" <?php checked('on', $fboptn['pages']); ?> /></td>
		</tr>
		<tr><th><label for="homepage">Homepage</label></th>
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
			<td><input id="num" type="text" name="fbcomment[num]" value="<?php echo $fboptn['num']; ?>" /> <small>default is <strong>10
			</strong></small>
			</td>
		</tr>
		<tr><th><label for="width">Width</label></th>
			<td><input id="width" type="text" name="fbcomment[width]" value="<?php echo $fboptn['width']; ?>" /> <small>default is <strong>580</strong></small>
			</td>
		</tr>
		<tr><th><label for="title">Title</label></th>
			<td><input id="title" type="text" name="fbcomment[title]" value="<?php echo $fboptn['title']; ?>" /> with a CSS class of <input type="text" name="fbcomment[titleclass]" value="<?php echo $fboptn['titleclass']; ?>" />
			</td>
		</tr>
		<tr><th><label for="count">Show Comment Count</label></th>
			<td><input id="count" name="fbcomment[count]" type="checkbox" value="on" <?php checked('on', $fboptn['count']); ?> />
			</td>
		</tr>
		<tr><th><label for="countmsg">Comment text</label></th>
			<td><input id="countmsg" type="text" name="fbcomment[countmsg]" value="<?php echo $fboptn['countmsg']; ?>" /> with a CSS class of <input type="text" name="fbcomment[countstyle]" value="<?php echo $fboptn['countstyle']; ?>" />
			</td>
		</tr>
	</table>		<!-- -----End Display Settings---- -->
			
		<div class="submitform">
			<input type="submit" class="button1" value="<?php _e('Save Changes') ?>" />
		</div>
</form>		
	
<!-- ---End of facebook App Id settings---- -->
		</div> <!-- --------End of inner_wrap--------- -->
  </div> <!-- ---------End of wrap-------- -->
<?php } ?>