<?php

add_action('wp_head', 'fbopengraph');
add_action('wp_footer', 'fbmlsetting');
add_filter ('the_content', 'commentscode');
add_filter('language_attributes', 'fbcomment_schema');

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



// Comments 
function commentscode($content) {
		$fboptn = get_option('fbcomment');
if (!isset($fboptn['html5'])) {$fboptn['html5'] = "off";}
if (!isset($fboptn['pluginsite'])) {$fboptn['pluginsite'] = "off";}
if (!isset($fboptn['posts'])) {$fboptn['posts'] = "off";}
if (!isset($fboptn['pages'])) {$fboptn['pages'] = "off";}
if (!isset($fboptn['homepage'])) {$fboptn['homepage'] = "off";}
if (!isset($fboptn['count'])) {$fboptn['count'] = "off";}
	if (
	   (is_single() && $fboptn['posts'] == 'on') ||
       (is_page() && $fboptn['pages'] == 'on') ||
       ((is_home() || is_front_page()) && $fboptn['homepage'] == 'on')) {

		if ($fboptn['count'] == 'on') {
			if ($fboptn['countstyle'] == '') {
				$commentcount = "<p>";
			} else {
				$commentcount = "<p class=\"".$fboptn['countstyle']."\">";
			}
			
			 $commentcount .= "<fb:comments-count href=\"".get_permalink()."\"></fb:comments-count>".$fboptn['countmsg']."</p>";
			 
			
		 $countnew = '<iframe src="http://www.facebook.com/plugins/comments.php?href='.get_permalink().'&permalink=1" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:130px; height:16px;" allowTransparency="true"></iframe>';
			 
			 ?>
		
		<?php
		}
		if ($fboptn['title'] != '') {
			if ($fboptn['titleclass'] == '') {
				$commenttitle = "<h3>";
			} else {
				$commenttitle = "<h3 class=\"".$fboptn['titleclass']."\">";
			}
			$commenttitle .= $fboptn['title']."</h3>";
		}
		$content .= "<!-- FB Comments For Wp: http://www.vivacityinfotech.com -->".$commenttitle.$countnew.$commentcount;

      	if ($fboptn['html5'] == 'on') {
			$content .=	"<div class=\"fb-comments\" data-href=\"".get_permalink()."\" data-numposts=\"".$fboptn['num']."\" data-width=\"".$fboptn['width']."\" data-colorscheme=\"".$fboptn['scheme']."\"></div>";

    } else {
    $content .= "<fb:comments href=\"".get_permalink()."\" num_posts=\"".$fboptn['num']."\" width=\"".$fboptn['width']."\" colorscheme=\"".$fboptn['scheme']."\"></fb:comments>";
     }
 //echo $countno = "demo"."<fb:comments-count href=".get_permalink()."></fb:comments-count>";
    if ($fboptn['pluginsite'] != 'no') {
        if ($fboptn['pluginsite'] != 'off') {
            if (empty($fbcomment[pluginsite])) {
      $content .= '<p>FB Comments Powered by <a href="http://www.vivacityinfotech.com"  target="_blank" >Vivacity Infotech Pvt. Ltd.</a></p>';
    }}}
  }
return $content;
}





  ?>
 
 
 
