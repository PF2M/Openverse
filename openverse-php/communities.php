<?php
require_once '../inc/connect.php';
$sql = "SELECT * FROM communities LEFT JOIN titles ON title_id = community_title WHERE community_id = " . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$title = htmlspecialchars($row['community_name']);
$selected = 'communities';
array_push($classes, 'community-top');
array_push($classes, 'is-autopagerized');
require_once '../inc/htm.php'; openHead();
if(!$result)
{
    echo '<div class="no-content"><p>The page could not be found.</p></div>';
}
else
{
    if(mysqli_num_rows($result) == 0) {
        echo '<div class="no-content"><p>The page could not be found.</p></div>';
    }
    else
    {
echo '<div id="sidebar"><section class="sidebar-container" id="sidebar-community"><span id="sidebar-cover"> <a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img src="' . htmlspecialchars($row['community_banner']) . '"></a></span><header id="sidebar-community-body"><span id="sidebar-community-img"><span class="icon-container"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img src="' . htmlspecialchars($row['community_icon']) . '" class="icon"></a></span>';
if($row['community_platform']<3) {
echo '<span class="platform-tag"><img src="/assets/img/platform-tag-'; 
if($row['community_platform']==0) {
echo '3ds';
} elseif($row['community_platform']==1){
echo 'wiiu';
} elseif($row['community_platform']==2){
echo 'wiiu-3ds';
}
echo '.png"></span>';
}
echo '</span><h1 class="community-name"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '">' . htmlspecialchars($row['community_name']) . '</a></h1></header><div class="community-description"><p class="text">' . nl2br(htmlspecialchars($row['community_description'])) . '</p></div>';
if($signed_in){
echo '<button type="button" class="button favorite-button symbol';
if(mysqli_num_rows(mysqli_query($link,'SELECT favorite_id FROM favorites WHERE favorite_to = '.mysqli_real_escape_string($link,$row['community_id']).' AND favorite_by = '.mysqli_real_escape_string($link,$_SESSION['user_pid'])))!=0){
echo ' checked';
}
echo '" data-action-favorite="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/favorite" data-action-unfavorite="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/unfavorite"><span class="favorite-button-text">Favorite</span></button>';
}
echo '</section>';
$cresult = mysqli_query($link, 'SELECT * FROM communities WHERE community_title = ' . mysqli_real_escape_string($link, $row['community_title']) . ' AND community_id != ' . mysqli_real_escape_string($link, $row['community_id']));
if(mysqli_num_rows($cresult)>0){
echo '<div class="sidebar-setting sidebar-container"><ul class="sidebar-other-menu"><li><a class="sidebar-menu-relation symbol" href="/titles/' . $row['community_title'] . '"><span>Related Communities</span></a></li><li class="community-list"><ul>';
while($crow = mysqli_fetch_assoc($cresult)){
echo '<li class="trigger" data-href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '"><div class="community-list-body"><span class="icon-container"><img class="icon" src="' . htmlspecialchars($crow['community_icon']) . '"></span><div class="body">';
if($crow['community_type']==1){
echo '<span class="news-community-badge">Main Community</span>';
} elseif($crow['community_type']==2){
echo '<span class="news-community-badge">Announcement Community</span>';
}
echo '<a class="title" href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '">' . htmlspecialchars($crow['community_name']) . '</a></div></div></li>';
}
echo '</ul></li></ul></div>';
}
echo '</div><div class="main-column"><form class="search">
<input type="text" name="query" title="Search posts" placeholder="Search posts" minlength="2"';
if(isset($_GET['query']) && strlen($_GET['query']) > 1) {
    $query_exists = true;
    echo ' value="' . htmlspecialchars($_GET['query']) . '"';
} else {
    $query_exists = false;
}
echo '>
<input type="submit" value="q" title="Search">
</form><div class="post-list-outline"><div class="tab-container"><div class="tab2"><a class="selected" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '">All Posts</a><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/hot">Popular Posts</a></div></div>';
if($signed_in && $row['community_perms'] <= $_SESSION['user_rank']) {
echo '<form id="post-form" action="/posts" method="post" enctype="multipart/form-data" class="folded" data-post-subtype="default" name="test-post-default-form"><input type="hidden" name="community_id" value="' . $row['community_id'] . '">
    <center><div class="feeling-selector"><label class="symbol feeling-button feeling-button-normal checked"><input type="radio" name="feeling_id" value="0" checked=""><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy"><input type="radio" name="feeling_id" value="1"><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like"><input type="radio" name="feeling_id" value="2"><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised"><input type="radio" name="feeling_id" value="3"><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated"><input type="radio" name="feeling_id" value="4"><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled"><input type="radio" name="feeling_id" value="5"><span class="symbol-label">puzzled</span></label></div></center>
    <div class="textarea-with-menu active-text">
    <menu class="textarea-menu">
        <li><label class="textarea-menu-text">
            <input type="radio" name="_post_type" value="body">
        </label></li>
        <li><label class="textarea-menu-memo checked" data-modal-open="#memo-drawboard-page">
          <input type="radio" name="_post_type" value="painting">
        </label></li>
      </menu>
    <div class="textarea-container">
    <textarea name="body" class="textarea-text textarea" maxlength="1000" placeholder="Share your thoughts in a post to this community." data-open-folded-form="" data-required=""></textarea></div>
<!-- //Start of memo -->
<div id="memo-drawboard-page" class="dialog fade-scale in none">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Drawing</h1>
    <div class="window-body">
		<div class="memo-canvas">
		Drawings are currently in beta and work only on PC.<br>
		Pencil:
		<button type="button" id="setpen1">Small</button> <button type="button" id="setpen2">Medium</button> <button type="button" id="setpen3">Large</button><br>
		Eraser:
		<button type="button" id="seter1">Small</button> <button type="button" id="seter2">Medium</button> <button type="button" id="seter3">Large</button><br>
		<button type="button" id="clear-can">Clear</button> <br>
		<canvas id="can" width="320" height="120" style="background: white; border: 1px solid #0080ff; user-select: none;"></canvas><br>
		<input type="hidden" name="painting" value="">
		</div>
		<div class="form-buttons">
            <input class="olv-modal-close-button black-button memo-finish-btn" type="button" value="Finish">
          </div>
    </div>
  </div>
</div>
</div>
<div class="textarea-memo none"><br>

</div>
<!-- // -->
    </div>
<input type="text" class="textarea-line url-form" name="url" placeholder="URL" maxlength="1024">
<label class="file-button-container">
      <span class="input-label">Image<span> Will be uploaded to Imgur.</span></span>
      <input type="file" class="file-button" id="upload" accept="image/*">
      <input type="hidden" name="screenshot" id="screenshot">
    </label>
<div class="post-form-footer-options">
      <label class="spoiler-button symbol">
          <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1">
          Spoilers
        </label>
  </div>
<div class="form-buttons"><input type="submit" class="black-button post-button" value="Send" data-track-action="sendPost"></div>
</form>';
}
echo '<div class="body-content" id="community-post-list"><div class="list post-list js-post-list" data-next-page-url';
if(isset($_GET['offset']) && is_numeric($_GET['offset'])){
$offset = $_GET['offset'];
} else {
$offset = 0;
}
if($query_exists) {
    $query = mysqli_real_escape_string($link, $_GET['query']);
    $sql = 'SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_pid WHERE post_community = ' . mysqli_real_escape_string($link, $row['community_id']) . ' AND post_status = 0 AND post_content LIKE "%' . $query . '%" ORDER BY post_id DESC LIMIT ' . mysqli_real_escape_string($link, $offset) . ',50';
} else {
    if(isset($_GET["suck"]) && $_GET["suck"] == 1) {
        $sql = 'SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_pid WHERE post_community = ' . mysqli_real_escape_string($link, $row['community_id']) . ' ORDER BY post_id DESC LIMIT ' . mysqli_real_escape_string($link, $offset) . ',50';
    } else {
        $sql = 'SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_pid WHERE post_community = ' . mysqli_real_escape_string($link, $row['community_id']) . ' AND post_status = 0 ORDER BY post_id DESC LIMIT ' . mysqli_real_escape_string($link, $offset) . ',50';
    }
}
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result)>0){
echo '="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '?offset=' . htmlspecialchars($offset+50);
if($query_exists) {
 echo "&query=" . htmlspecialchars($_GET['query']);
}
echo '"';
}
echo '>';
        
        if(mysqli_error($link))
        {
            echo '<div class="no-content"><p>An error occurred while trying to grab the posts for that community.</p></div>';
        }
        else
        {
            if(mysqli_num_rows($result) == 0)
            {
                if(!$offset) {
                echo '<div class="no-content"><p>This community doesn\'t have any posts yet.</p></div>';
                }
            }
            else
            { 
                while($row = mysqli_fetch_assoc($result))
                {
if($signed_in && mysqli_num_rows(mysqli_query($link, 'SELECT * FROM blocks WHERE block_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND block_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']))) > 0) {
$user_blocked = true;
} else {
$user_blocked = false;
}
if($signed_in && mysqli_num_rows(mysqli_query($link, 'SELECT * FROM blocks WHERE block_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND block_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0) {
$post_blocked = true;
} else {
$post_blocked = false;
}
if($signed_in && $row['post_by'] == $_SESSION['user_pid']) {
$post_by_me = true;
} else {
$post_by_me = false;
}
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<div class="post trigger';
if(($post_by_me == false && $row['post_is_spoiler'] == 1) || ($user_blocked == true)) {
echo ' hidden';
}
echo '" data-href';
if(($post_by_me == false && $row['post_is_spoiler'] == 1) || ($user_blocked == true)) {
echo '-hidden';
}
echo '="/posts/' . htmlspecialchars($row['post_id']) . '"><a class="icon-container';
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
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="'.$avatar.'"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p><p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($row['post_date']) .'</a>';
if($row['post_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
echo '</p><div class="body post-content">';
if (preg_match('/(http:\/\/|https:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu\.be\/))([A-Za-z0-9-_]{11}).*/i', $row['post_url'], $res)) {
echo '<a href="/posts/'.$row['post_id'].'" class="screenshot-container video"><img height="48" src="https://i.ytimg.com/vi/'.$res[6].'/default.jpg"></a>';
}
if($row['post_screenshot']) {
echo '<a class="screenshot-container still-image" href="/posts/' . $row['post_id'] . '"><img src="';
if($_COOKIE['proxy']=='1'){
echo 'https://pf2m.000webhostapp.com/mini.php?';
}
echo htmlspecialchars($row['post_screenshot']) . '"></a>';
}
if(!empty($row['post_drawing'])) {
echo '<p class="post-content-memo"><img src="' . htmlspecialchars($row['post_drawing']) . '" class="post-memo"></p>';
}
else {
echo '<p class="post-content-text">';
if($row['post_html']==1) {
echo $row['post_content'];
} else {
if(mb_strlen($row['post_content'])<204) {
echo parsePost($row['post_content']);
} else {
echo parsePost(mb_substr($row['post_content'], 0, 200) . '...');
}
}
echo '</p>';
}
if($user_blocked == true) {
echo '<div class="hidden-content"><p>User blocked.</p><button type="button" class="hidden-content-button">View Post</button></div>';
} elseif(($post_by_me == false && $row['post_is_spoiler'] == 1) || ($user_blocked == true)) {
echo '<div class="hidden-content"><p>This post contains spoilers.</p><button type="button" class="hidden-content-button">View Post</button></div>';
}
$ysqla = 'SELECT * FROM post_yeahs WHERE yeah_post = ' . $row['post_id'];
$resultB = mysqli_query($link, $ysqla);
$ysqlb = 'SELECT * FROM post_yeahs WHERE yeah_post = ' . $row['post_id'] . ' AND yeah_by = ' . $_SESSION['user_pid'];
$resultC = mysqli_query($link, $ysqlb);
$sql = 'SELECT * FROM replies LEFT JOIN users ON replies.reply_by = users.user_pid WHERE replies.reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' ORDER BY replies.reply_id DESC';
$resultD = mysqli_query($link, $sql);
echo '<div class="post-meta">';
$testYeah = "SELECT * FROM post_yeahs WHERE post_yeahs.yeah_post = " . mysqli_real_escape_string($link, $row['post_id']) . " AND post_yeahs.yeah_by = " . $_SESSION['user_pid'];
$results = mysqli_query($link, $testYeah);
if($signed_in && mysqli_num_rows($results) > 0) {
echo '<button type="button" class="symbol submit empathy-button empathy-added';
if(!$signed_in || $post_by_me || $post_blocked){
echo ' disabled" disabled';
} else {
echo '"';
}
echo ' data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
} else {
echo '<button type="button" class="symbol submit empathy-button';
if(!$signed_in || $post_by_me || $post_blocked){
echo ' disabled" disabled';
} else {
echo '"';
}
echo ' data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah♥';
} elseif($feeling_id == 3){
echo 'Yeah!?';
} elseif($feeling_id == 4 || $feeling_id == 5){
echo 'Yeah...';
} else {
echo 'Yeah!';
}
echo '</span></button><div class="empathy symbol">';
}
echo '<span class="empathy-count">' . mysqli_num_rows($resultB) . '</span></div><div class="reply symbol"><span class="reply-count">' . mysqli_num_rows($resultD) . '</span></div></div>';
if(mysqli_num_rows($resultD)>0){
$rsql = 'SELECT * FROM replies LEFT JOIN users ON users.user_pid = replies.reply_by WHERE reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' AND user_pid != ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND reply_is_spoiler = 0 AND reply_status = 0 ORDER BY reply_id DESC LIMIT 0,1';
$rresult = mysqli_query($link, $rsql);
if(!mysqli_error($rresult)){
if(mysqli_num_rows($rresult)!=0){
$rrow = mysqli_fetch_assoc($rresult);
$avatar = $rrow['user_avatar'];
$feeling_id = $rrow['reply_feeling_id'];
include 'avatar.php';
echo '<div class="recent-reply-content"><div class="recent-reply trigger" data-href="/posts/' . htmlspecialchars($row['post_id']) . '"><a class="icon-container" href="/users/' . $rrow['user_id'] . '"><img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($rrow['user_id']) . '">' . htmlspecialchars($rrow['user_name']) . '</a></p> <p class="timestamp-container"><a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($rrow['reply_date']) . '</a></p><div class="body post-content"><p class="recent-reply-content-text">' . parsePost($rrow['reply_content']) . '</p></div></div></div>';
}}
}
echo '</div></div>';
            }
        }
    }}
}
echo '</div></div></div></div>';
openFoot();