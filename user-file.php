<?php
add_action('wp_head', 'fbopengraph');
add_action('wp_footer', 'fbmlsetting');
add_filter ('the_content', 'commentscode');
add_filter('language_attributes', 'fbcomment_schema');
//add_filter('widget_text', 'do_shortcode');
add_shortcode('vivafbcomment', 'commentshortcode');

global $fboptn;

// ---code from facebook comment code generator
function fbmlsetting() {
	$fboptn = get_option('fbcomment');
	
	
if (!isset($fboptn['fbml'])) {$fboptn['fbml'] = "";}
if ($fboptn['fbml'] == 'on') {
	?>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $fboptn['lang']; ?>/sdk.js#xfbml=1&appId=<?php echo $fboptn['appID']; ?>&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<?php	}
} 
// ---End code from facebook comment code generator

// ----Code for Adding XFBML 
function fbcomment_schema($attr) {
 	$fboptn = get_option('fbcomment');
if (!isset($fboptn['fbns'])) {$fboptn['fbns'] = "";}
if (!isset($fboptn['opengraph'])) {$fboptn['opengraph'] = "";}
	if ($fboptn['opengraph'] == 'on') {$attr .= "\n xmlns:og=\"http://opengraphprotocol.org/schema/\"";}
	if ($fboptn['fbns'] == 'on') {$attr .= "\n xmlns:fb=\"http://www.facebook.com/2008/fbml\"";}
	return $attr;
}
 
// ----Code for Adding Open Graph meta
 function fbopengraph() {
	$fboptn = get_option('fbcomment'); ?>
<meta property="fb:app_id" content="<?php echo $fboptn['appID']; ?>"/>
<meta property="fb:admins" content="<?php echo $fboptn['mods']; ?>"/>
<meta property="og:locale" content="<?php echo $fboptn['lang']; ?>" />
<meta property="og:locale:alternate" content="<?php echo $fboptn['lang']; ?>" />
<?php
}

// ----Code for hideWpComments
$fboptn = get_option('fbcomment');

if (!isset($fboptn['postshideWpComments'])) {$fboptn['postshideWpComments'] = "off";}
if (!isset($fboptn['pageshideWpComments'])) {$fboptn['pageshideWpComments'] = "off";}

if ($fboptn['postshideWpComments'] == 'on' ) {
   function posts_enqueueHideWpCommentsCss() 
     {
         wp_register_style('posts-front-css', plugins_url('css/fb-comments-hidewpcomments-posts.css',__FILE__));
        wp_enqueue_style('posts-front-css');
     }				
    add_action('init', 'posts_enqueueHideWpCommentsCss');
   }


if ( $fboptn['pageshideWpComments'] == 'on' ) {

function pages_enqueueHideWpCommentsCss() 
{
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
$fboptn = get_option('fbcomment');
$pagesnw = $fboptn['pagesid'];
$totalpages = explode(",",$pagesnw);
$allpage = get_all_page_ids();
if(empty($allpage)){                                                                 
	$allpage = '';
}
else {
	$allpage = array_diff($allpage,$totalpages);
}

/*
echo '<pre>';
print_r($totalpages);
echo '</pre>';

echo '<pre>';
print_r($allpage);
echo '</pre>';
*/


if (!isset($fboptn['html5'])) {$fboptn['html5'] = "off";}
if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "off";}
if (!isset($fboptn['posts'])) {$fboptn['posts'] = "off";}
if (!isset($fboptn['pages'])) {$fboptn['pages'] = "off";}
if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "off";}
if (!isset($fboptn['count'])) {$fboptn['count'] = "off";}
if (!isset($fboptn['countmsg'])) {$fboptn['countmsg'] = "0";}
	if (!isset($fboptn['titlecls'])) {$fboptn['titlecls'] = "";}
	if (!isset($fboptn['countmsgcls'])) {$fboptn['countmsgcls'] = "";}
	if (!isset($fboptn['archive'])) {$fboptn['archive'] = "";}
	if (!isset($fboptn['posttypes'])) {$fboptn['posttypes'] = "off";}
	if (!isset($fboptn['pagesid'])) {$fboptn['pagesid'] = 00;}
	
	$pages = $fboptn['pagesid'];
     $pages1 = explode(',', $pages);
		if(!empty($pages)){
			foreach($pages1 as $page) {
				if(is_page($page) && $fboptn['pages'] == 'on' ){		
	 	 			return $content;  	//return blank for exclude page ids
	 			}
	 			if(is_single($page) && $fboptn['posts'] == 'on'){		
	 				return $content;  	//return blank for exclude post ids
	 			}
	 		}
		}
	
	/* Start Page code*/
	if (
	     ( is_page($allpage) && $fboptn['pages'] == 'on'  && comments_open() && !(is_home() || is_front_page()) ) 
       ) {
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
			$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.com -->".$commenttitle.$commentcount;
			if ($fboptn['html5'] == 'on') {
			$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    		} else {
    			$content .= "<fb:comments href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
     		}
			if (!empty($fboptn['pluginsite'])) {
				if($fboptn['pluginsite'] == 'on'){
					$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
				}
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
	/* End Page code*/

	/* Start Home/Archive code*/
	if ( 
		  (is_archive() && $fboptn['archive'] == 'on') ||
		  ((is_home() || is_front_page()) && $fboptn['homepage'] == 'on')
       ) {
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
			$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.com -->".$commenttitle.$commentcount;
			if ($fboptn['html5'] == 'on') {
			$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    		} else {
    			$content .= "<fb:comments href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
     		}
			if (!empty($fboptn['pluginsite'])) {
				if($fboptn['pluginsite'] == 'on'){
					$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
				}
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
	/* End Home/Archive code*/
	
	/* Start code for posts and CPT */
	$posttype = get_post_type();
	/* Start code for posts*/
		if (
				(is_single() && $fboptn['posts'] == 'on' && ($posttype=='post')  && comments_open())
			) {
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
			$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.com -->".$commenttitle.$commentcount;
			if ($fboptn['html5'] == 'on') {
			$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    		} else {
    			$content .= "<fb:comments href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
     		}
			if (!empty($fboptn['pluginsite'])) {
				if($fboptn['pluginsite'] == 'on'){
					$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
				}
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
	/* End code for posts */
		
	/* ---loop for all custom post type--- */
	foreach( $fboptn as $key => $value ) {
		if($key == $value){  
			$fboptn[$key];
	
	
	/* Start code for CPT */
	if (
	     ((is_single()) && ($fboptn['posttypes'] == 'on') && ($posttype == $fboptn[$key]))
      ) {
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
			$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.com -->".$commenttitle.$commentcount;
			if ($fboptn['html5'] == 'on') {
			$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    		} else {
    			$content .= "<fb:comments href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
     		}
			if (!empty($fboptn['pluginsite'])) {
				if($fboptn['pluginsite'] == 'on'){
					$content .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
				}
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
	/* End code for CPT */
	
}}	
	/* End code for posts and CPT */
   return $content;
}


// -------Add facebook shortcode------
function commentshortcode($fbsrt) {
    extract(shortcode_atts(array(
		"fbsrtcode" => get_option('fbcomment'),
		"url" => get_permalink(),
    ), $fbsrt));
    if (!empty($fbsrt)) {
        foreach ($fbsrt as $key => $option)
            $fbsrtcode[$key] = $option;
	}
	if($fbsrtcode['appID'] != "") {
		if ($fbsrtcode['count'] == 'on') {
			
			$cls4 = $fbsrtcode['countmsgcls'];
			$commentcount = "<p class='commentcount ".$cls4."'>";
			$commentcount .= "<fb:comments-count href=".$url."></fb:comments-count> ".$fbsrtcode['countmsg']."</p>";
		}
		if ($fbsrtcode['title'] != '') {
			$cls3 = $fbsrtcode['titlecls'];
			$commenttitle = "<h3 class='coments-title ".$cls3."'>";
			$commenttitle .= $fbcomment['title']."</h3>";
		}
		$contentshortcode = "<!-- Facebook Comments for WordPress: http://peadig.com/wordpress-plugins/facebook-comments/ -->".$commenttitle.$commentcount;

      	if ($fbsrtcode['html5'] == 'on') {
			$contentshortcode .=	"<div class=\"fb-comments\" data-href=\"".$url."\" data-num-posts=\"".$fbsrtcode['num']."\" data-width=\"".$fbsrtcode['width']."\" data-colorscheme=\"".$fbsrtcode['scheme']."\"></div>";

    } else {
    $contentshortcode .= "<fb:comments href=\"".$url."\" num_posts=\"".$fbsrtcode['num']."\" width=\"".$fbsrtcode['width']."\" colorscheme=\"".$fbsrtcode['scheme']."\"></fb:comments>";
     }

	if (!empty($fbsrtcode['pluginsite'])) {
		if($fbsrtcode['pluginsite'] == 'on'){
      $contentshortcode .= '<p class="pluginsite">'.__( 'Facebook Comments Plugin Powered by', 'facebook-comment-by-vivacity' ). '<a href="http://www.vivacityinfotech.net"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
	}}
	
	}
	else {
       $fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
   $contentshortcode .= '<div class="error" style="color:red; font-weight:bold;">
            <p>'. __( 'Please Enter Your Facebook App ID. Required for FB Comments.', 'facebook-comment-by-vivacity' ). ' <a href="'.$fb_adminUrl.'">'. __( 'Click here for FB Comments Settings page', 'facebook-comment-by-vivacity' )
            .'</a></p>
            </div>';
        }
  return $contentshortcode;
}
?>