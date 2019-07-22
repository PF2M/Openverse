<?php
// OpenverseHTML: Prints HTML elements.
/*
openHead(); - Prints header
openFoot(); - Prints footer
openNoContentWindow('text'); - prints "text" as a no content window
*/

function openHead($title = null, $auth = null) { global $has_pjax; if(!$has_pjax) {
// Start of header
?><!DOCTYPE html><html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title><?php global $title; 
	if(!empty($title)) {
	echo $title . ' - Openverse';
		} else {
		echo 'Openverse';
		}?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="apple-mobile-web-app-title" content="Openverse">
    <meta name="description" content="Openverse is the best way to promote some pussy." />
    <meta name="keywords" content="Openverse, Miiverse, PF2M, Nintendo, alternative, social, network, Hatena" />
    <script src="/assets/openverse.js"></script>
    <link rel="stylesheet" href="/assets/openverse.css">
    <style>
        @keyframes spinner {
            from { transform: rotateY(0deg);    }
            to   { transform: rotateY(-360deg); }
        }
        .empathy-button[data-track-action="cancelYeah"] {
            animation: spinner 0.75s linear;
        }
    </style>
</head>
<body id="closed-source-LOLOLOLOL">
<?php } ?>
<div id="wrapper" <?php global $signed_in; global $classes;
if(!$signed_in){
$classes[] = 'guest';
} if(!empty($classes)) {
echo 'class="' . implode(' ', $classes) . '"'; 
} ?>>
<div id="sub-body">
<menu id="global-menu">
<li id="global-menu-logo"><a href="/"><img src="/assets/img/menu-logo.png" alt="Openverse"></a></li>
<?php
global $auth;
if($signed_in) {
date_default_timezone_set($_SESSION['user_timezone']);
echo '<li id="global-menu-list"><ul>
<li id="global-menu-mymenu"';
global $selected;
if(isset($selected) && $selected=='user') {
echo ' class="selected"';
}
echo '><a href="/users/' . htmlspecialchars($_SESSION['user_id']) . '"><span class="icon-container';
switch($_SESSION['user_rank']) {
case 1:
echo ' donator'; break;
case 2:
echo ' tester'; break;
case 3:
echo ' moderator'; break;
case 4:
echo ' administrator'; break;
case 5:
echo ' developer'; break;
}
echo '"><img src="';
$avatar = $_SESSION['user_avatar'];
$feeling_id = false;
require_once __DIR__ . '/../openverse-php/avatar.php';
echo $avatar.'" alt="User Page"></span><span>User Page</span></a></li>
<li id="global-menu-feed"';
if(isset($selected) && $selected=='activity') {
echo ' class="selected"';
}
echo '><a href="/activity" class="symbol"><span>Activity Feed</span></a></li>
<li id="global-menu-community"';
if(isset($selected) && $selected=='communities') {
echo ' class="selected"';
}
echo '><a href="/" class="symbol"><span>Communities</span></a></li>
<li id="global-menu-news"';
if(isset($selected) && $selected=='news') {
echo ' class="selected"';
}
echo '><a href="/news/my_news" class="symbol"></a></li>
<li id="global-menu-my-menu"><button class="symbol js-open-global-my-menu open-global-my-menu" id="my-menu-btn"></button>
 <menu id="global-my-menu" class="invisible none">
  <li><a href="/settings/profile" class="symbol my-menu-profile-setting"><span>Profile Settings</span></a></li>
  <li><a href="/settings/account" class="symbol my-menu-miiverse-setting"><span>Account Settings</span></a></li>
  <li><a href="/titles/1/1" class="symbol my-menu-info"><span>Openverse Announcements</span></a></li>
  <li><a href="/titles/1/2" class="symbol my-menu-info"><span>Openverse Changelog</span></a></li>
  <li><a href="/guide/" class="symbol my-menu-guide"><span>Openverse Code of Conduct</span></a></li>
  <li><a href="/guide/legal" class="symbol my-menu-guide"><span>Legal Stuff</span></a></li>
  <li><a href="/guide/faq" class="symbol my-menu-guide"><span>Frequently Asked Questions (FAQ)</span></a></li>';
  if($signed_in && $_SESSION['user_rank'] > 3) {
  echo '<li><a href="/admin/" class="symbol my-menu-miiverse-setting"><span>Admin Tools</span></a></li>';
  }
  print '
  <li>
   <form action="/account/logout';
   $_SERVER['REQUEST_URI'] = strtok($_SERVER['REQUEST_URI'],'?');
        if($_SERVER['REQUEST_URI'] !== '/') {
            echo '?callback=' . htmlspecialchars(substr($_SERVER['REQUEST_URI'],1,strlen($_SERVER['REQUEST_URI'])-1));
        }
   echo '" method="post" id="my-menu-logout" class="symbol">
    <input type="submit" value="Log Out" />
   </form>
  </li>
 </menu>
</li>';
    } elseif(!isset($auth)){
		echo '<li id="global-menu-login">
		<a href="/account/login" class="login"><input type="image" src="/assets/img/sign-in.png"></a>
		</li>';
		/*
        echo '<li id="global-menu-login"><form action="/account/login';
        if($_SERVER['REQUEST_URI'] !== '/') {
            echo '?callback=' . htmlspecialchars(substr($_SERVER['REQUEST_URI'],1,strlen($_SERVER['REQUEST_URI'])-1));  
        }
        echo '" method="post"><input type="image" src="/assets/img/sign-in.png"></form></li>';
		*/
    }
?>
</ul></menu></div><div id="main-body">
<?php if($has_pjax) { global $title; echo "    <title>{$title} - Openverse</title>"; } }
// End of header
function openFoot() { global $has_pjax; ?><div id="footer"><div id="footer-inner"><div class="link-container"><p><a href="https://www.pf2m.com/">PF2M's Website</a></p><p><a href="https://www.pf2m.com/contact/">Contact Us</a></p><p><a href="https://www.paypal.me/PF2M">Donate</a></p><p id="copyright"><a href="https://miiverse.nintendo.net/">Openverse is not-for-profit and is not associated with Miiverse, Nintendo, or Hatena.</a><br><a href="https://www.hostblast.online/aff.php?aff=6394">Website hosted by HostBlast (for now), sign up today and get unlimited web hosting for as low as $1/mo!</a></p></div></div></div></div><?php if(!$has_pjax) { ?>
</body></html><?php }}



function openUserSidebar() {
echo '<div id="sidebar" class="';
if(isset($personal) && $personal == true) {
    echo 'general';
} else {
    echo 'user';
}
echo '-sidebar">';
if ((!empty($personal) && $signed_in == true) || empty($personal)) {
echo '<div class="sidebar-container">';
if ($row['user_favorite_post']!=0) {
$fpsql = 'SELECT * FROM posts WHERE post_id = ' . mysqli_real_escape_string($link, $row['user_favorite_post']);
$fpresult = mysqli_query($link, $fpsql);
$fprow = mysqli_fetch_assoc($fpresult);
echo '<a id="sidebar-cover" class="sidebar-cover-image" href="/posts/'.htmlspecialchars($row['user_favorite_post']).'" style="background-image: url('.htmlspecialchars($fprow['post_screenshot']).')"><img src="'.htmlspecialchars($fprow['post_screenshot']).'"></a>';
}
echo '<div id="sidebar-profile-body"';
if ($row['user_favorite_post']!=0) {
echo ' class="with-profile-post-image"';
}
$avatar = $row['user_avatar'];
$feeling_id = false;
include 'avatar.php';
echo '><div class="icon-container';
if($row['user_rank'] == 1) {
echo ' donator';
}
if($row['user_rank'] == 2) {
echo ' tester';
}
if($row['user_rank'] == 3) {
echo ' moderator';
}
if($row['user_rank'] == 4) {
echo ' administrator';
}
if($row['user_rank'] == 5) {
echo ' developer';
}
echo '"><a href="/users/'.htmlspecialchars($row['user_id']).'"><img src="'.$avatar.'" class="icon"></a></div>';
if(strlen($row['user_rank'])!=0 && $row['user_rank']!=0) {
echo '<p class="user-organization">';
if($row['user_rank'] == 1) {
echo 'Donator';
}
if($row['user_rank'] == 2) {
echo 'Tester';
}
if($row['user_rank'] == 3) {
echo 'Moderator';
}
if($row['user_rank'] == 4) {
echo 'Administrator';
}
if($row['user_rank'] == 5) {
echo 'Developer';
}
echo '</p>';
}
echo '<a href="/users/'.htmlspecialchars($row['user_id']).'" class="nick-name">'.htmlspecialchars($row['user_name']).'</a><p class="id-name">'.htmlspecialchars($row['user_id']).'</p></div>';
if(!isset($personal) || $personal == false) {
if($row['user_pid']!=$_SESSION['user_pid']){
echo '<div class="user-action-content"><div class="toggle-button"><button type="button" class="follow-button button symbol';
if(mysqli_num_rows(mysqli_query($link, 'SELECT follow_to FROM follows WHERE follow_to = '.mysqli_real_escape_string($link,$row['user_pid']).' AND follow_by = '.mysqli_real_escape_string($link,$_SESSION['user_pid'])))!=0) {
$following = true;
echo ' none';
}
echo '" data-action="/users/' . htmlspecialchars($row['user_id']) . '/follow" data-screen-name="' . htmlspecialchars($row['user_name']) . '">Follow</button><button type="button" class="unfollow-button button symbol';
if($following!=true) {
echo ' none';
}
echo '" data-action="/users/' . htmlspecialchars($row['user_id']) . '/unfollow" data-screen-name="' . htmlspecialchars($row['user_name']) . '">Follow</button></div></div>';
} else {
echo '<div id="edit-profile-settings"><a href="/settings/profile" class="button symbol">Profile Settings</a></div>';
}}
echo '<ul id="sidebar-profile-status"><li><a href="/users/'.htmlspecialchars($row['user_id']).'/following"';
if($selected == 'following') {
echo ' class="selected"';
}
echo '><span class="number">';
$following = mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_by = '.mysqli_real_escape_string($link, $row['user_pid'])));
$followers = mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = '.mysqli_real_escape_string($link, $row['user_pid'])));
if($row['user_relationship_visibility'] == 2) {
    if($signed_in && $_SESSION['user_pid'] == $row['user_pid']) {
        echo $following;
    } else {
        echo '-';
    }
} else if ($row['user_relationship_visibility'] == 1) {
    if($signed_in && ($_SESSION['user_pid'] == $row['user_pid']) || (mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0)) {
        echo $following;
    } else {
        echo '-';
    }
} else { 
echo $following;
}
echo '</span>Following</a></li><li><a href="/users/'.htmlspecialchars($row['user_id']).'/followers"';
if($selected == 'followers') {
echo ' class="selected"';
}
echo '><span class="number">';
if($row['user_relationship_visibility'] == 2) {
    if($signed_in && $_SESSION['user_pid'] == $row['user_pid']) {
        echo $followers;
    } else {
        echo '-';
    }
} else if ($row['user_relationship_visibility'] == 1) {
    if($signed_in && ($_SESSION['user_pid'] == $row['user_pid']) || (mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0)) {
        echo $followers;
    } else {
        echo '-';
    }
} else { 
echo $followers;
}
echo '</span>Followers</a></li></ul></div>';
}
if(!isset($personal) || $personal == false) {
echo '<div class="sidebar-container sidebar-setting"><div class="sidebar-post-menu"><a href="/users/'.htmlspecialchars($row['user_id']).'/posts" class="sidebar-menu-post with-count symbol';
if($selected == 'posts') {
echo ' selected';
}
echo '"><span>All Posts</span><span class="post-count"><span class="test-post-count">' . mysqli_num_rows(mysqli_query($link, 'SELECT post_id FROM posts WHERE post_by = '.mysqli_real_escape_string($link,$row['user_pid']))) . '</span></span></a><a href="/users/'.htmlspecialchars($row['user_id']).'/empathies" class="sidebar-menu-empathies with-count symbol';
if($selected == 'empathies') {
echo ' selected';
}
echo '"><span>Yeahs</span><span class="post-count"><span class="test-empathy-count">';
echo mysqli_num_rows(mysqli_query($link,'SELECT yeah_id FROM post_yeahs WHERE yeah_by = '.mysqli_real_escape_string($link,$row['user_pid'])))+mysqli_num_rows(mysqli_query($link,'SELECT ryeah_id FROM reply_yeahs WHERE ryeah_by = '.mysqli_real_escape_string($link,$row['user_pid'])));
echo '</span></span></a></div></div><div class="sidebar-container sidebar-profile">';
if(strlen($row['user_profile_comment'])!=0){
echo '<div class="profile-comment"><p class="js-truncated-text">'.htmlspecialchars($row['user_profile_comment']).'</p></div>';
}
echo '<div class="user-data"><div class="data-content"><h4><span>Region</span></h4><div class="note"><span>';
if(strlen($row['user_country'])!=0){
echo htmlspecialchars($row['user_country']);
} else {
echo 'Not Set';
}
echo '</span></div></div><div class="data-content"><h4><span>Birthday</span></h4><div class="note"><span>';
if($row['user_birthday']!='0000-00-00'){
echo date('m/d', strtotime($row['user_birthday']));
} else {
echo 'Not Set';
}
echo '</span></div></div><div class="data-content game-skill"><h4><span>Game Experience</span></h4><div class="note">';
if(strlen($row['user_skill'])!=0){
if($row['user_skill']==1){
echo '<span class="beginner">Beginner</span>';
} else if($row['user_skill']==2){
echo '<span class="intermediate">Intermediate</span>';
} else if($row['user_skill']==3){
echo '<span class="expert">Expert</span>';
} else {
echo '<span>Not Set</span>';
}} else {
echo '<span>Not Set</span>';
}
echo '</div></div><div class="data-content"><h4><span>Member ID</span></h4><div class="note"><span>#' . number_format($row['user_pid']) . '</span></div></div><div class="favorite-game-genre"><h4><span>Website</span></h4><div class="note"><span>';
if(strlen($row['user_website'])!=0){
echo '<a href="'.htmlspecialchars($row['user_website']).'">'.htmlspecialchars($row['user_website']).'</a>';
} else {
echo 'Not Set';
}
echo '</span></div></div></div></div><div class="sidebar-container sidebar-favorite-community"><h4><a href="';
if($signed_in && $row['user_pid']==$_SESSION['user_pid']) {
echo '/communities/favorites';
} else {
echo '/users/' . htmlspecialchars($row['user_id']) . '/favorites';
}
echo '" class="favorite-community-button symbol"><span>Favorite Communities</span></a></h4><ul>';
$cresult = mysqli_query($link, 'SELECT * FROM favorites LEFT JOIN communities ON community_id = favorite_to WHERE favorite_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' ORDER BY favorite_id DESC LIMIT 0,10');
while($crow = mysqli_fetch_assoc($cresult)){
echo '<li class="favorite-community"><a href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '"><span class="icon-container"><img class="icon" src="' . htmlspecialchars($crow['community_icon']) . '"></span></a>';
if($crow['community_platform'] < 3){
echo '<span class="platform-tag"><img src="/assets/img/platform-tag-';
if($crow['community_platform']==0) {
echo '3ds';
} elseif($crow['community_platform']==1){
echo 'wiiu';
} elseif($crow['community_platform']==2){
echo 'wiiu-3ds';
}
echo '.png"></span>';
}
echo '</li>';
}
for($x=mysqli_num_rows($cresult); $x<10; $x++){
echo '<li class="favorite-community empty"><span class="icon-container empty-icon"><img class="icon" src="/assets/img/empty.png"></span></li>';
}
echo '</ul></div>';
} else {
echo '<div class="sidebar-container sidebar-setting"><ul><li><a class="sidebar-menu-setting symbol" href="/settings/account"><span>Account Settings</span></a></li><li><a class="sidebar-menu-info symbol" href="/titles/1/3"><span>Openverse Announcements</span></a></li><li><a class="sidebar-menu-info symbol" href="/titles/1/4"><span>Openverse Changelog</span></a></li><li><a class="sidebar-menu-guide symbol" href="/guide/rules"><span>Openverse Code of Conduct</span></a></li><li><a class="sidebar-menu-guide symbol" href="/guide/faq"><span>Frequently Asked Questions (FAQ)</span></a></li></ul></div>';
}
echo '</div>';
}
// Clarification: Doing this for function arguments gives them a default value. Look at php.net's page.
function openNoContentWindow($text = '', $style = null) {
?><div class="<?=($style!==null ? $style.' ' : '')?>no-content"><p><?=$text?></p></div><?php
}
// Print a server error. $extra_msg will print an extra message at the end of it, e.g. "Openverse isn't ready yet, sorry :(((((((((((("
function openServerErr($err = 500, $extra_msg = null) {
// A switch() for the type of error.
	switch($err) {
	case 400: $text = '400 Bad Request'; $message = 'The request you sent was invalid.'; break;
	case 401: $text = '401 Unauthorized'; $message = 'You\'re not authorized to view this page.'; break;
	case 403: $text = '403 Forbidden'; $message = 'You\'re not allowed to view this page.'; break;
	case 404: $text = '404 Not Found'; $message = 'The page could not be found.'; break;
    case 500: $text = '500 Internal Server Error'; $message = 'The server couldn\'t process your request properly.'; break;
    case 501: $text = '501 Not Implemented'; $message = 'That feature is not implemented yet.'; break;
	// 503 doesn't have a message because there could be many causes, should be added with extra_msg
    case 503: $text = '503 Service Unavailable'; $message = ''; break;
	}
	// Do standard things for an error page.
		!isset($_SERVER['HTTP_X_PJAX']) ? http_response_code($err) : null;
    openHead($text, true);
	// If there is an extra message...
		if(isset($extra_msg)) {
		// Append to message. If this is a 503, don't make a line break.
		$message .= ($err != 503 ? "\n<br>\n" : "") . $extra_msg;
		}
	openNoContentWindow($message);
    openFoot();
// Exit!
exit();
}