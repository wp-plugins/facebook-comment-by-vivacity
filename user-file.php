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
  js.src = "//connect.facebook.net/en_IN/all.js#xfbml=1&appId=<?php echo $fboptn['appID']; ?>";
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
		
if (!isset($fboptn['html5'])) {$fboptn['html5'] = "off";}
if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "off";}
if (!isset($fboptn['posts'])) {$fboptn['posts'] = "off";}
if (!isset($fboptn['pages'])) {$fboptn['pages'] = "off";}
if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "off";}
if (!isset($fboptn['count'])) {$fboptn['count'] = "off";}
if (!isset($fboptn['countmsg'])) {$fboptn['countmsg'] = "0";}
	if (
	   (is_single() && $fboptn['posts'] == 'on') ||
       (is_page() && $fboptn['pages'] == 'on') ||
       ((is_home() || is_front_page()) && $fboptn['homepage'] == 'on')) {
if($fboptn['appID'] != "") {
		if ($fboptn['count'] == 'on') {
			
			 $commentcount = "<p>";
			 $commentcount .= "<fb:comments-count href=\"".get_permalink()."\"></fb:comments-count>"." ".$fboptn['countmsg']."</p>";
			 ?>
	
		<?php
		}
		if ($fboptn['title'] != '') {
			
				$commenttitle = "<h3>";
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
      $content .= '<p>FB Comments Powered by <a href="http://www.vivacityinfotech.com"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
	}}
    
    }
else {
	$fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
   $content .= '<div class="error" style="color:#FF0000; font-weight:bold;">
            <p>Please Enter Your Facebook App ID. Required for FB Comments. <a href="'.$fb_adminUrl.'">Click here for FB Comments page
            </a></p>
            </div>';
   }
  }
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
			
			$commentcount = "<p>";
			$commentcount .= "<fb:comments-count href=".$url."></fb:comments-count> ".$fbsrtcode['countmsg']."</p>";
		}
		if ($fbsrtcode['title'] != '') {
			$commenttitle = "<h3>";
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
      $contentshortcode .= '<p>FB Comments Powered by <a href="http://www.vivacityinfotech.com"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
	}}
	
	}
	else {
       $fb_adminUrl = get_admin_url()."options-general.php?page=fbcomment";
   $contentshortcode .= '<div class="error" style="color:red; font-weight:bold;">
            <p>Please Enter Your Facebook App ID. Required for FB Comments. <a href="'.$fb_adminUrl.'">Click here for FB Comments page</a></p>
            </div>';
        }
  return $contentshortcode;
}

?>
 
 
 
