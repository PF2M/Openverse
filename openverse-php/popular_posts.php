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
if(mysqli_error($link))
{
    echo '<div class="no-content"><p>An error occurred while trying to access that page.</p></div>';
}
else
{
    if(mysqli_num_rows($result) == 0) {
        echo '<div class="no-content"><p>The page could not be found.</p></div>';
    }
    else
    {
if(isset($_GET['date']) && $_GET['date']!=''){
$date = strtotime('-1 day', strtotime($_GET['date']));
} else {
$date = strtotime('-1 day', time());
}
echo '<div id="sidebar"><section class="sidebar-container" id="sidebar-community"><span id="sidebar-cover"> <a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img src="' . htmlspecialchars($row['community_banner']) . '"></a></span><header id="sidebar-community-body"><span id="sidebar-community-img"><span class="icon-container"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img src="' . htmlspecialchars($row['community_icon']) . '" class="icon"></a></span>';
if($row['title_platform']<3) {
echo '<span class="platform-tag"><img src="/assets/img/platform-tag-'; 
if($row['title_platform']==0) {
echo '3ds';
} elseif($row['title_platform']==1){
echo 'wiiu';
} elseif($row['title_platform']==2){
echo 'wiiu-3ds';
}
echo '.png"></span>';
}
echo '</span><h1 class="community-name"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '">' . htmlspecialchars($row['community_name']) . '</a></h1></header><div class="community-description"><p class="text">' . nl2br(htmlspecialchars($row['community_description'])) . '</p></div>';
//remember to insert favorite button code here at some point
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
echo '</div><div class="main-column"><div class="post-list-outline"><div class="tab-container"><div class="tab2"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '">All Posts</a><a class="selected" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/hot">Popular Posts</a></div></div><div class="body-content"><div class="pager-button">';
if(time() > date('U', strtotime('+2 days',$date))){
echo '<a class="button back-button symbol" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/hot/' . date('Y-m-d',strtotime('+2 days', $date)) . '"><span class="symbol-label">←</span></a>';
}
echo '<a class="button selected" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/hot">' . date('m/d/Y', $date) . '</a><a class="button next-button symbol" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '/hot/' . date('Y-m-d',$date) . '"><span class="symbol-label">→</span></a></div><div class="list post-list js-post-list">';
$sql = 'SELECT *, COUNT(yeah_id) AS yeah_count FROM posts LEFT JOIN users ON user_pid = post_by LEFT JOIN post_yeahs ON yeah_post = post_id WHERE post_community = ' . mysqli_real_escape_string($link, $row['community_id']) . ' AND cast(post_date as date) = "' . mysqli_real_escape_string($link, date('Y-m-d', $date)) . '" AND post_status = 0 GROUP BY post_id ORDER BY yeah_count DESC';
$result = mysqli_query($link, $sql);
if(mysqli_error($link)) {
echo '<div class="no-content"><p>An error occurred while trying to grab the posts for that community.</p></div>';
} else {
if(mysqli_num_rows($result) == 0) {
echo '<div class="no-content"><p>No posts were made to this community on that date.</p></div>';
} else { 
while($row = mysqli_fetch_assoc($result)) {
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<div class="post trigger';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo ' hidden';
}
echo '" data-href';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
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
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="'.$avatar.'"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p><p class="timestamp-container"><a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . date("m/d/Y g:i A",strtotime($row['post_date'])) .'</a>';
if($row['post_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
echo '</p><div class="body post-content">';
if (preg_match('/(http:\/\/|https:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu\.be\/))([A-Za-z0-9-_]{11}).*/i', $row['post_url'], $res)) {
echo '<a href="/posts/'.$row['post_id'].'" class="screenshot-container video"><img height="48" src="https://i.ytimg.com/vi/'.$res[6].'/default.jpg"></a>';
}
if($row['post_screenshot']) {
echo '<a class="screenshot-container still-image" href="/posts/' . $row['post_id'] . '"><img src="' . htmlspecialchars($row['post_screenshot']) . '"></a>';
}
if(!empty($row['post_drawing'])) {
echo '<p class="post-content-memo"><img src="' . htmlspecialchars($row['post_drawing']) . '" class="post-memo"></p>';
}
else {
echo '<p class="post-content-text">';
if(mb_strlen($row['post_content'])<204) {
echo htmlspecialchars($row['post_content']);
} else {
echo mb_substr(htmlspecialchars($row['post_content']), 0, 200) . '...';
}
echo '</p>';
}
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo '<div class="hidden-content"><p>This post contains spoilers.</p><button type="button" class="hidden-content-button">View Post</button></div>';
}
$ysqla = 'SELECT * FROM post_yeahs WHERE yeah_post = ' . $row['post_id'];
$resultB = mysqli_query($link, $ysqla);
$ysqlb = 'SELECT * FROM post_yeahs WHERE yeah_post = ' . $row['post_id'] . ' AND yeah_by = ' . $_SESSION['user_pid'];
$resultC = mysqli_query($link, $ysqlb);
$sql = 'SELECT * FROM replies LEFT JOIN users ON replies.reply_by = users.user_pid WHERE replies.reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' ORDER BY replies.reply_id DESC';
$resultD = mysqli_query($link, $sql);
echo '<div class="post-meta">';
if(!$signed_in||$row['post_by']==$_SESSION['user_pid']) {
echo '<button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">';
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
} else {
$testYeah = "SELECT * FROM post_yeahs WHERE post_yeahs.yeah_post = " . mysqli_real_escape_string($link, $row['post_id']) . " AND post_yeahs.yeah_by = " . $_SESSION['user_pid'];
$results = mysqli_query($link, $testYeah);
if(mysqli_num_rows($results)!=0) {
echo '<button type="button" class="symbol submit empathy-button empathy-added" data-feeling="';
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
echo '<button type="button" class="symbol submit empathy-button" data-feeling="';
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
echo '<div class="recent-reply-content"><div class="recent-reply trigger" data-href="/posts/' . htmlspecialchars($row['post_id']) . '"><a class="icon-container" href="/users/' . htmlspecialchars($rrow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($rrow['user_id']) . '">' . htmlspecialchars($rrow['user_name']) . '</a></p> <p class="timestamp-container"><a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . date('m/d/Y g:i A', strtotime($rrow['reply_date'])) . '</a></p><div class="body post-content"><p class="recent-reply-content-text">' . htmlspecialchars($rrow['reply_content']) . '</p></div></div></div>';
}}
}
echo '</div></div>';
            }
        }
    }}
}
echo '</div></div></div></div>';
openFoot();