<?php
add_action('wp_head', 'fbopengraph');
add_filter ('the_content', 'commentscode', 100);
add_filter ('the_excerpt', 'commentscode');
add_filter('language_attributes', 'fbcomment_schema');
//add_filter('widget_text', 'do_shortcode');
add_shortcode('vivafbcomment', 'commentshortcode');
global $fboptn;

// ----Code for Adding XFBML 
function fbcomment_schema($attr) {
 	$fboptn = get_option('fbcomment');
	if (!isset($fboptn['opengraph'])) {$fboptn['opengraph'] = "";}
	if ($fboptn['opengraph'] == 'on') {$attr .= "\n xmlns:og=\"http://opengraphprotocol.org/schema/\"";}
	if ($fboptn['fbns'] == 'on') {$attr .= "\n xmlns:fb=\"http://www.facebook.com/2008/fbml\"";}
	return $attr;
}
 
// ----Code for Adding Open Graph meta
function fbopengraph() {
	$fboptn = get_option('fbcomment'); 
	?>
<meta property="fb:app_id" content="<?php echo $fboptn['appID']; ?>"/>
<meta property="og:locale" content="<?php echo $fboptn['lang']; ?>" />
<meta property="og:locale:alternate" content="<?php echo $fboptn['lang']; ?>" />
<?php
}

// print debug messages
function fviva_debug ( $s ) 
{
	error_log( print_r( $s, 1 ) );
}

// enqueue javascript
add_action('wp_enqueue_scripts', 'fviva_scripts_load');
// js loader
function fviva_scripts_load() 
{
	//global $post;
	global $admin_user_id;
	
	$fboptn = get_option( 'fbcomment' );
	$app_id = $fboptn['appID'];
	$admin_user_id = $fboptn['mods'];
	$width = $fboptn['width'];
	$colorscheme = $fboptn['scheme'];
	$lang_region = $fboptn['lang'];

	# NOTE: This block may need to deregister additional scripts 
	#  if other plugins load the facebook sdk under another handle.
	#  These will all use the global var FB and may conflict.
      wp_deregister_script( 'facebook-sdk' );
		wp_deregister_script( 'facebook-jssdk' );
		wp_deregister_script( 'facebook' );

   $fbsdk = 'http://connect.facebook.net/'.$lang_region.'/all.js#xfbml=1';

	if ( $app_id ) 
	{
		$fbsdk .= '&appId='. $app_id;
	}
	
	// check for admin user id
	if ( $admin_user_id ) 
	{
		add_action('wp_head', 'fviva_fbadmin_wp_head');
		function fviva_fbadmin_wp_head(){
			global $admin_user_id;
			echo '<meta property="fb:admins" content="{' . $admin_user_id . '}"/> <!-- Facebook Admin ID -->' . PHP_EOL;
		}
	}	
	
	wp_register_script( 'facebook-sdk', $fbsdk, array('jquery') );
	wp_enqueue_script( 'facebook-sdk' );

	wp_deregister_script( 'fviva-scripts' );

	# widget, global js data fb-comments
	$fbresponse = plugin_dir_url('/').'facebook-comment-by-vivacity/fb-comments.js';
	
	$jsparam = array();
	$jsparam['ajaxurl']   = admin_url( 'admin-ajax.php' );
	$jsparam['permalink'] = get_permalink(); 

	if  ( $width ) 
	{
		$jsparam['width'] = $width;
	}
	if ( $colorscheme ) 
	{
		$jsparam['colorscheme'] = $colorscheme;
	}

	wp_register_script( 'fviva-scripts', $fbresponse, array('jquery', 'facebook-sdk'));
	wp_localize_script( 'fviva-scripts', 'fviva_global_data', $jsparam );
	wp_enqueue_script( 'fviva-scripts' );

}

// Send email
if ( ! function_exists('fviva_comment_notify') )
{
	function fviva_comment_notify( $data = null ) 
	{
		if ( ! $data ) 
		{
			$data = $_REQUEST;
		}

		$fboptn = get_option( 'fbcomment' );

		// read ajax packet
		 	$commentID       = $data['commentID'];
		   $parentCommentID = $data['parentCommentID'];
		 	$href            = $data['href'];
		   $actionTaken	  = $data['actionTaken'];
		 	$userName        = $data['userName'];
		   $commentText     = $data['commentText'];
		 	$userId			  = $data['userId'];  
		 
		// get current time
		$wp_currenttime = current_time('mysql');
		$wp_time = explode(" ", $wp_currenttime);
		$wp_time[1]; 
		//end current time
		
		$fdate = date(get_option('date_format'));
		$ftime = $wp_time[1]; 
		$admin_email = $fboptn['admin_email'];

		if ( ! $admin_email ) {
			$admin_email = get_bloginfo('admin_email');
		}

	 	$blogurl = get_bloginfo('url');
		
		$message = "";
		if ( $href ) 
		{
			$message .= 'Page: '.$href."<br>";
		}
		
		if ( $userName && $userId ) 
		{
			$message .= 'From: '.'<a href="https://www.facebook.com/profile.php?id='.$userId.'">'.$userName.'</a><br>';
		}
		
		# comment or reply?
		$commentLabel = 'Comment';
		if ( $parentCommentID ) 
		{
			$commentLabel = 'Reply';
		}	

		if ( $commentText ) 
		{
			$message .= $commentLabel . ': '.$commentText."<br>";
		}
		
		$message .= 'Submitted on '.$fdate.' at '.$ftime."<br>";
		$headers = 'X-Mailer: PHP/' . phpversion() . "\r\n" .
                'Content-Type: text/html; charset=ISO-8859-1'."\r\n".
                'MIME-Version: 1.0'."\r\n\r\n";

		// uncomment to see email message in log:
		//fviva_debug (array($admin_email, "new $commentLabel on $blogurl", $message));
		wp_mail($admin_email, "You have a new $commentLabel on $blogurl", $message, $headers);
	}
}

// ajax callback: email and terminate thread
// for both logged-in and non-logged-in users
add_action('wp_ajax_fviva_comment_created', 'fviva_comment_notify_handler');
add_action('wp_ajax_nopriv_fviva_comment_created', 'fviva_comment_notify_handler');

if ( ! function_exists('fviva_comment_notify_handler') )
{
	function fviva_comment_notify_handler( $data = null )
	{
		$result = fviva_comment_notify( $data = null ) ;
		$response = array();

		# ajax confirm message for sys debug
		$response = array( 'status' => 1, 'message' => 'ok' );
		if ( ! $result )
		{
			$response = array( 'status' => 0, 'message' => 'could not send mail' );

			// uncomment for verbose mail debug
    		/*try
			{
				# return more verbose wp_mail errors
				global $ts_mail_errors;
				global $phpmailer;
				if (!isset($ts_mail_errors))
				{
					$ts_mail_errors = array();
				}
				if (isset($phpmailer))
				{
					$ts_mail_errors[] = $phpmailer->ErrorInfo;
				}
				$response = array( 'status' => 0, 'message' => 'error', 'data' => $ts_mail_errors );
			}
			catch ( Exception $ex )
			{
				$response = array( 'status' => 0, 'message' => 'error', 'data' => $ex->getMessage() );
			}*/

			// end debug
		}
		print json_encode( $response );
		die();
	}
}

// ----Code for hideWpComments
$fboptn = get_option('fbcomment');
if (!isset($fboptn['postshideWpComments'])) {$fboptn['postshideWpComments'] = "off";}
if (!isset($fboptn['pageshideWpComments'])) {$fboptn['pageshideWpComments'] = "off";}
if ($fboptn['postshideWpComments'] == 'on' ) {
   function posts_enqueueHideWpCommentsCss() {
		wp_register_style('posts-front-css', plugins_url('css/fb-comments-hidewpcomments-posts.css',__FILE__));
		wp_enqueue_style('posts-front-css');
   }				
   add_action('init', 'posts_enqueueHideWpCommentsCss');
}
if ( $fboptn['pageshideWpComments'] == 'on' ) {
	function pages_enqueueHideWpCommentsCss() {
         wp_register_style('pages-front-css', plugins_url('css/fb-comments-hidewpcomments-pages.css',__FILE__));
         wp_enqueue_style('pages-front-css');
 	}				
	add_action('init', 'pages_enqueueHideWpCommentsCss');
}
if (!isset($fboptn['hideWpComments'])) {$fboptn['hideWpComments'] = "";}
# Enqueue correct stylesheet if user wants to hide the WordPress commenting form
if ($fboptn['hideWpComments'] == "on" ) {
	function fbComments_enqueueHideWpCommentsCss() {
		wp_register_style('front-css', plugins_url('css/fb-comments-hidewpcomments.css',__FILE__));
      wp_enqueue_style('front-css');
	}
	add_action('init', 'fbComments_enqueueHideWpCommentsCss');
}
// ----End Code for hideWpComments

// Comments 
function commentscode($content) {

	$fboptn = get_option('fbcomment'); //array of all options value
	
	if (!isset($fboptn['html5'])) {$fboptn['html5'] = "off";}
	if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "off";}
	if (!isset($fboptn['posts'])) {$fboptn['posts'] = "off";}
	if (!isset($fboptn['pages'])) {$fboptn['pages'] = "off";}
	if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "off";}
	if (!isset($fboptn['count'])) {$fboptn['count'] = "off";}
	if (!isset($fboptn['countmsg'])) {$fboptn['countmsg'] = "0";}
	if (!isset($fboptn['titlecls'])) {$fboptn['titlecls'] = "";}
	if (!isset($fboptn['countmsgcls'])) {$fboptn['countmsgcls'] = "";}
	if (!isset($fboptn['pagesid'])) {$fboptn['pagesid'] = 00;}
	if (!isset($fboptn['archive'])) {$fboptn['archive'] = "";}
	if (!isset($fboptn['posttypes'])) {$fboptn['posttypes'] = "off";}
	$posttype = get_post_type();
  	$pages = $fboptn['pagesid'];
     $pages1 = explode(',', $pages);
		if(!empty($pages)){
			foreach($pages1 as $page) {
				if(is_page($page) && $fboptn['pages'] == 'on' ){		
	 	 			return $content;  	//return blank for exclude page ids
	 			}
	 			if(is_single($page) && $fboptn['posts'] == 'on' && ($posttype=='post')){		
	 				return $content;  	//return blank for exclude post ids
	 			}
	 		}
		} 

	global $totalposts;
	$ex_pages = $fboptn['pagesid'];
	$totalex_pages = explode(",",$ex_pages);
	$allpage = get_all_page_ids();		// get all page ids of site.
	$allpage = array_diff($allpage,$totalex_pages); // Array of all pages with excluded ids.
   
	/* --- custom query for fecth array of all posts id --- */
	$argsPosts = array( 'post_type' => 'post', 'posts_per_page' => -1 );
	$loopPosts = new WP_Query( $argsPosts );
		while ( $loopPosts->have_posts() ) : $loopPosts->the_post();
    		$postid = get_the_ID();
    		$totalposts[] .= $postid;
		endwhile;
		wp_reset_query(); // Reset Query
	/* --- End custom query for fecth array of all posts id --- */
	
	$totalposts = array_diff($totalposts,$totalex_pages); // Array of all posts with excluded ids.
 
	/* ---Show commnet box on pages--- */
	if (is_page($allpage) && $fboptn['pages'] == 'on'  && comments_open() && !(is_home() || is_front_page()) ) {
		if($fboptn['appID'] != "") {
			if ($fboptn['count'] == 'on') {
				$cls2 = $fboptn['countmsgcls'];
				$commentcount = "<p class='commentcount ".$cls2."'>";
				$commentcount .= "<fb:comments-count href=\"".get_permalink()."\"></fb:comments-count>"." ".$fboptn['countmsg']."</p>";
			}
			if ($fboptn['title'] != '') {
				$cls1 = $fboptn['titlecls'];
				$commenttitle = '<h3 class="coments-title '.$cls1.'">';
				$commenttitle .= $fboptn['title']."</h3>";
			}
				$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.net -->".$commenttitle.$commentcount;
      	if ($fboptn['html5'] == 'on') {
				$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    		}
    		else {
				$content .= "<div id=\"fb-comments-div\" class=\"fb-comments\"><fb:comments  notify=\"true\" migrated=\"1\" href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\" ></fb:comments></div>";
			}
			if ( !empty($fboptn['pluginsite']) && $fboptn['pluginsite'] == 'on' ) {
				$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
			}
    
    	}
		else {
			$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
			$content .= '<div class="error" style="color:#FF0000; font-weight:bold;"><p>'. __( 'Please Enter Your Facebook App ID. Required for FB Comments.', 'facebook-comment-by-vivacity' ). ' <a href="'.$fb_adminUrl.'">'. __( 'Click here for FB Comments Settings page', 'facebook-comment-by-vivacity' ).'</a></p></div>';
		}
	}
	/* End ---Show commnet box on pages--- */

	/* ---Show commnet box on posts/archive/home--- */
	if (	(is_single($totalposts) && $fboptn['posts'] == 'on' && ($posttype=='post')  && comments_open()) ||
			(is_archive() && $fboptn['archive'] == 'on') ||
			((is_home() || is_front_page()) && $fboptn['homepage'] == 'on')) {
        	
		if($fboptn['appID'] != "") {
			if ($fboptn['count'] == 'on') {
				$cls2 = $fboptn['countmsgcls'];
				$commentcount = "<p class='commentcount ".$cls2."'>";
				$commentcount .= "<fb:comments-count href=\"".get_permalink()."\"></fb:comments-count>"." ".$fboptn['countmsg']."</p>";
			}
			if ($fboptn['title'] != '') {
				$cls1 = $fboptn['titlecls'];
				$commenttitle = '<h3 class="coments-title '.$cls1.'">';
				$commenttitle .= $fboptn['title']."</h3>";
			}
				$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.net -->".$commenttitle.$commentcount;
			if ($fboptn['html5'] == 'on') {
				$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";
			}
			else {
				$content .= "<div id=\"fb-comments-div\" class=\"fb-comments\"><fb:comments notify=\"true\" migrated=\"1\" href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments></div>";
			}

			if (!empty($fboptn['pluginsite']) && $fboptn['pluginsite'] == 'on' ) {
				$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
			}
		}
		else {
			$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
			$content .= '<div class="error" style="color:#FF0000; font-weight:bold;"><p>'. __( 'Please Enter Your Facebook App ID. Required for FB Comments.', 'facebook-comment-by-vivacity' ). ' <a href="'.$fb_adminUrl.'">'. __( 'Click here for FB Comments Settings page', 'facebook-comment-by-vivacity' ).'</a></p></div>';
		}
	}
	/* End ---Show commnet box on posts/archive/home--- */  

	/* ---loop for all custom post type--- */
	foreach( $fboptn as $key => $value ) {
		if($key == $value){  
			$fboptn[$key];
 
		/* --- custom query for fecth array of all CPT ids --- */ 
		$args = array( 'post_type' => $fboptn[$key], 'posts_per_page' => -1 );
		$loop = new WP_Query( $args );
		while ( $loop->have_posts() ) : $loop->the_post();
    		 $customPost = get_the_ID();
    		 $customPostId[] .= $customPost;
		endwhile;
		wp_reset_query(); // Reset Query
		/* End--- custom query for fecth array of all CPT ids --- */
		$customPostId = array_diff($customPostId,$totalex_pages); // Array of all CPT with excluded ids.

		$pages =  $fboptn['pagesid'];
			$pages1 = explode(',', $pages);
			if(!empty($pages)){
				foreach($pages1 as $page) {
					if((is_single($page)) && ($fboptn['posttypes'] == 'on') && ($posttype == $fboptn[$key])){		
						return $content;  	//return blank for exclude CPT post ids  	
	 				}
				}
			} 		
			/* ---Show commnet box on CPT posts--- */
			if ((is_single($customPostId)) && ($fboptn['posttypes'] == 'on') && ($posttype == $fboptn[$key])) {
				if($fboptn['appID'] != "") {
					if ($fboptn['count'] == 'on') {
						$commentcount = "<p class='commentcount'>";
						$commentcount .= "<fb:comments-count href=\"".get_permalink()."\"></fb:comments-count>"." ".$fboptn['countmsg']."</p>";
					}
					if ($fboptn['title'] != '') {
						$commenttitle = "<h3 class='coments-title'>";
						$commenttitle .= $fboptn['title']."</h3>";
					}
						$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.net -->".$commenttitle.$commentcount;
					if ($fboptn['html5'] == 'on') {
						$content .=	"<div id=\"fb-comments-div\" class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";
					}
					else {
						$content .= "<fb:comments notify=\"true\" migrated=\"1\" href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
					}

					if (!empty($fboptn['pluginsite']) && $fboptn['pluginsite'] == 'on' ) {
						$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
					}
 				}
				else {
	$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
   $content .= '<div class="error" style="color:#FF0000; font-weight:bold;">
            <p>'. __( 'Please Enter Your Facebook App ID. Required for FB Comments.', 'facebook-comment-by-vivacity' ). ' <a href="'.$fb_adminUrl.'">'. __( 'Click here for FB Comments Settings page', 'facebook-comment-by-vivacity' )
            .'</a></p>
            </div>';
				}
			}
			/* End ---Show commnet box on CPT posts--- */
		} 
		  
	}
 
	/* End ---loop for all custom post type--- */ 
	return $content;
}

// -------Add facebook shortcode------
function commentshortcode($fbsrt) {
	extract(shortcode_atts(array(
		"fbsrtcode" => get_option('fbcomment'),
		"url" => get_permalink(),
	), $fbsrt));
	if (!empty($fbsrt)) {
		foreach ($fbsrt as $key => $option) {
           $fbsrtcode[$key] = $option;
		}          
	}
	
	if (!isset($fbsrtcode['html5'])) {$fbsrtcode['html5'] = "off";}
		
	if($fbsrtcode['appID'] != "") {
		if ($fbsrtcode['count'] == 'on') {
			$cls4 = $fbsrtcode['countmsgcls'];
			$commentcount = "<p class='commentcount ".$cls4."'>";
			$commentcount .= "<fb:comments-count href=".$url."></fb:comments-count> ".$fbsrtcode['countmsg']."</p>";
		}
		if ($fbsrtcode['title'] != '') {
			$cls3 = $fbsrtcode['titlecls'];
			$commenttitle = "<h3 class='coments-title ".$cls3."'>";
			$commenttitle .= $fbsrtcode['title']."</h3>";
		}
			$contentshortcode = "<!-- Facebook Comments for WordPress: http://peadig.com/wordpress-plugins/facebook-comments/ -->".$commenttitle.$commentcount;

		if ($fbsrtcode['html5'] == 'on') {
			$contentshortcode .=	"<div class=\"fb-comments\" data-href=\"".$url."\" data-num-posts=\"".$fbsrtcode['num']."\" data-width=\"".$fbsrtcode['width']."\" data-colorscheme=\"".$fbsrtcode['scheme']."\"></div>";
		}
		else {
			$contentshortcode .= "<div id=\"fb-comments-div\" class=\"fb-comments\"><fb:comments notify=\"true\" migrated=\"1\" href=\"".$url."\" num_posts=\"".$fbsrtcode['num']."\" width=\"".$fbsrtcode['width']."\" colorscheme=\"".$fbsrtcode['scheme']."\"></fb:comments></div>";
		}

		if (!empty($fbsrtcode['pluginsite']) && $fbsrtcode['pluginsite'] == 'on') {
			$contentshortcode .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
		}
	
	}
	else {
			$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
			$contentshortcode .= '<div class="error" style="color:red; font-weight:bold;"><p>'. __( 'Please Enter Your Facebook App ID. Required for FB Comments.', 'facebook-comment-by-vivacity' ). ' <a href="'.$fb_adminUrl.'">'. __( 'Click here for FB Comments Settings page', 'facebook-comment-by-vivacity' ).'</a></p></div>';
	}
	return $contentshortcode;
}
?>