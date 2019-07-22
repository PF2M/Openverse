<?php
$title = 'Activity Feed';
$selected = 'activity';
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if(isset($_GET['offset']) && is_numeric($_GET['offset'])){
$offset = $_GET['offset'];
} else {
$offset = 0;
}
if($signed_in) {
$sql = 'SELECT * FROM users LEFT JOIN posts ON posts.post_by = users.user_pid WHERE users.user_pid = "' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . '"';
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$personal = true;
include 'user_sidebar.php';
echo '<div class="main-column"><div class="headline"><h2><span class="activity-headline symbol">Activity Feed</span></h2><form class="search" action="/users"><input type="text" name="query" placeholder="Search Users" minlength="1" maxlength="32"><input type="submit" value="q"></form></div>
';
require_once '../inc/libqueries.php';
$posts = userGetActivityFeed($_SESSION['user_pid']);
if($posts == false) {
echo '<div class="list post-list">';
openNoContentWindow('There aren\'t any posts to display.', 'post-list-outline');
echo '</div>';
} else {
echo '<div class="list post-list js-post-list" data-next-page-url="';
if(count($posts) > 19) {
echo '/activity?offset=';
echo $offset+20;
}
echo '">';
foreach($posts as $row) {
// Start printing post
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
echo '<div class="post trigger post-list-outline';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo ' hidden';
}
echo '" data-href';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo '-hidden';
}
echo '="/posts/' . htmlspecialchars($row['post_id']) . '"><p class="community-container"><a href="/titles/1/' . htmlspecialchars($row['community_id']) . '"><img class="community-icon" src="' . htmlspecialchars($row['community_icon']) . '">' . htmlspecialchars($row['community_name']) . '</a></p><a class="icon-container';
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
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="';
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo $avatar.'"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p>';
echo '<p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($row['post_date']) .'</a></p><div class="body post-content">';
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
echo '<button type="button" class="symbol submit empathy-button disabled" disabled data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol">';
} else {
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
}}
echo '<span class="empathy-count">' . mysqli_num_rows($resultB) . '</span></div><div class="reply symbol"><span class="reply-count">' . mysqli_num_rows($resultD) . '</span></div></div>';
if(mysqli_num_rows($resultD)>0){
$rsql = 'SELECT * FROM replies LEFT JOIN users ON users.user_pid = replies.reply_by WHERE reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' AND user_pid != ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND reply_status = 0 ORDER BY reply_id DESC LIMIT 0,1';
$rresult = mysqli_query($link, $rsql);
if(!mysqli_error($link)){
if(mysqli_num_rows($rresult)!=0){
$rrow = mysqli_fetch_assoc($rresult);
$avatar = $rrow['user_avatar'];
$feeling_id = $rrow['reply_feeling_id'];
include 'avatar.php';
echo '<div class="recent-reply-content"><div class="recent-reply trigger" data-href="/posts/' . htmlspecialchars($row['post_id']) . '"><a class="icon-container" href="/users/' . $rrow['user_id'] . '"><img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($rrow['user_id']) . '">' . htmlspecialchars($rrow['user_name']) . '</a></p><p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($rrow['reply_date']) . '</a></p><div class="body post-content"><p class="recent-reply-content-text">' . parsePost($rrow['reply_content']) . '</p></div></div></div>';
}}
            }
echo '</div></div>';
// End of printing post

// End foreach
    }

// End no posts if
}
	// End of mysqli::result if?
	echo '</div></div>';
		// End of first if
		} else {
		echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
		}
openFoot();