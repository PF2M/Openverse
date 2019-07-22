<?php
require_once '../inc/connect.php';
if(isset($_SESSION) && $_GET['id']==$_SESSION['user_id']) {
$title = 'User Page';
} else {
$title = htmlspecialchars($_GET['id']) . '\'s Profile';
}
require_once '../inc/htm.php'; openHead();
$sql = 'SELECT * FROM users LEFT JOIN posts ON posts.post_by = users.user_pid WHERE users.user_id = "' . mysqli_real_escape_string($link, $_GET['id']) . '"';
$result = mysqli_query($link, $sql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>There was an error while searching for that user.<br>Error: ' . mysqli_error($link) . '</p></div>';
} else {
$row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result)==0) {
            http_response_code(404);
            echo '<div class="no-content"><p>That user could not be found.</p></div>';
        }
        else
        {
            {
$selected = 'empathies';
include 'user_sidebar.php';
echo '<div class="main-column"><div class="list post-list post-list-outline"><h2 class="label">'.htmlspecialchars($row['user_name']).'\'s Yeahs</h2>';
$sql = 'SELECT 0, post_id, yeah_post, user_pid, post_by, community_title, community_id, community_icon, community_name, community_type, post_community, yeah_by, yeah_date, user_id, user_name, user_avatar, post_date, post_feeling_id, post_is_spoiler, post_content, post_screenshot, post_url, post_html FROM post_yeahs LEFT JOIN posts ON post_id = yeah_post LEFT JOIN users ON user_pid = post_by LEFT JOIN communities ON community_id = post_community WHERE yeah_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND post_status < 2 UNION SELECT 1, reply_id, ryeah_reply, user_pid, reply_by, 0, 0, 0, 0, 0, reply_to, ryeah_by, ryeah_date, user_id, user_name, user_avatar, reply_date, reply_feeling_id, reply_is_spoiler, reply_content, reply_screenshot, 0, 0 FROM reply_yeahs LEFT JOIN replies ON reply_id = ryeah_reply LEFT JOIN users ON user_pid = reply_by WHERE ryeah_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND reply_status = 0 ORDER BY yeah_date DESC';
$result = mysqli_query($link, $sql);
while($row = mysqli_fetch_assoc($result)){
if($row[0]=='0'){
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<div class="post';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo ' hidden';
}
echo ' trigger" data-href';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo '-hidden';
}
echo '="/posts/' . htmlspecialchars($row['post_id']) . '">';
echo '<p class="community-container"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img class="community-icon" src="' . htmlspecialchars($row['community_icon']) . '">' . htmlspecialchars($row['community_name']) . '</a></p><a class="icon-container';
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
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p>';
echo '<p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($row['post_date']) . '</a>';
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
echo '<p class="post-content-text">';
if($row['post_html']==1) {
echo $row['post_content'];
} else {
if(strlen($row['post_content'])<204) {
echo parsePost($row['post_content']);
} else {
echo parsePost(mb_substr($row['post_content'], 0, 200) . '...');
}
}
echo '</p>';
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
if(mysqli_num_rows($results)!=0) {
echo '<button type="button" class="symbol submit empathy-button empathy-added" data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
} else {
echo '<button type="button" class="symbol submit empathy-button" data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol">';
}
}
echo '<span class="empathy-count">' . mysqli_num_rows($resultB) . '</span></div><div class="reply symbol"><span class="reply-count">' . mysqli_num_rows($resultD) . '</span></div></div>';
if(mysqli_num_rows($resultD)>0){
$rsql = 'SELECT * FROM replies LEFT JOIN users ON users.user_pid = replies.reply_by WHERE reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' AND user_pid != ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND reply_is_spoiler = 0 ORDER BY reply_id DESC LIMIT 0,1';
$rresult = mysqli_query($link, $rsql);
if(!mysqli_error($rresult)){
if(mysqli_num_rows($rresult)!=0){
$rrow = mysqli_fetch_assoc($rresult);
$avatar = $rrow['user_avatar'];
$feeling_id = $rrow['reply_feeling_id'];
include 'avatar.php';
echo '<div class="recent-reply-content"><div class="recent-reply trigger" data-href="/posts/' . htmlspecialchars($row['post_id']) . '"><a class="icon-container" href="/users/' . $rrow['user_id'] . '"><img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($rrow['user_id']) . '">' . htmlspecialchars($rrow['user_name']) . '</a></p> <p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row['post_id']) . '">' . displayTime($rrow['reply_date']) . '</a></p><div class="body post-content"><p class="recent-reply-content-text">' . parsePost($rrow['reply_content']) . '</p></div></div></div>';
}}}
echo '</div></div>';
} elseif($row[0]=='1'){
echo '<div class="post';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo ' hidden';
}
echo ' trigger" data-href';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo '-hidden';
}
echo '="/posts/' . htmlspecialchars($row['post_id']) . '">';
$prow = mysqli_fetch_assoc(mysqli_query($link,'SELECT user_name, user_avatar, post_feeling_id FROM users LEFT JOIN posts ON post_by = user_pid WHERE post_id = '.mysqli_real_escape_string($link,$row['post_community'])));
$avatar = $prow['user_avatar'];
$feeling_id = $prow['post_feeling_id'];
include 'avatar.php';
echo '<p class="community-container"><a href="/posts/' . htmlspecialchars($row['post_community']) . '"><img class="community-icon" src="' . htmlspecialchars($avatar) . '">Comment on ' . htmlspecialchars($prow['user_name']) . '\'s Post</a></p><a class="icon-container';
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
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '">';
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<img class="icon" src="' . $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p>';
echo '<p class="timestamp-container"> <a class="timestamp" href="/replies/' . htmlspecialchars($row['post_id']) . '">' . displayTime($row['post_date']) .'</a>';
if($row['post_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
echo '</p><div class="body post-content">';
if($row['post_screenshot']) {
echo '<a class="screenshot-container still-image" href="/posts/' . $row['post_id'] . '"><img src="';
if($_COOKIE['proxy']=='1'){
echo 'https://pf2m.000webhostapp.com/mini.php?';
}
echo htmlspecialchars($row['post_screenshot']) . '"></a>';
}
echo '<p class="post-content-text">';
if(strlen($row['post_content'])<204) {
echo parsePost($row['post_content']);
} else {
echo parsePost(mb_substr($row['post_content'], 0, 200) . '...');
}
echo '</p>';
if($row['post_is_spoiler'] == 1 && $row['post_by'] != $_SESSION['user_pid']) {
echo '<div class="hidden-content"><p>This reply contains spoilers.</p><button type="button" class="hidden-content-button">View Post</button></div>';
}
$ysqla = 'SELECT * FROM reply_yeahs WHERE ryeah_reply = ' . $row['post_id'];
$resultB = mysqli_query($link, $ysqla);
$ysqlb = 'SELECT * FROM reply_yeahs WHERE ryeah_reply = ' . $row['post_id'] . ' AND ryeah_by = ' . $_SESSION['user_pid'];
$resultC = mysqli_query($link, $ysqlb);
$sql = 'SELECT * FROM replies LEFT JOIN users ON replies.reply_by = users.user_pid WHERE replies.reply_to = ' . mysqli_real_escape_string($link, $row['post_id']) . ' ORDER BY replies.reply_id DESC';
$resultD = mysqli_query($link, $sql);
echo '<div class="post-meta">';
if(!$signed_in||$row['post_by']==$_SESSION['user_pid']) {
echo '<button type="button" class="symbol submit empathy-button disabled" disabled data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol">';
} else {
$testYeah = "SELECT * FROM reply_yeahs WHERE ryeah_reply = " . mysqli_real_escape_string($link, $row['post_id']) . " AND ryeah_by = " . $_SESSION['user_pid'];
$results = mysqli_query($link, $testYeah);
if(mysqli_num_rows($results)!=0) {
echo '<button type="button" class="symbol submit empathy-button empathy-added" data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
} else {
echo '<button type="button" class="symbol submit empathy-button" data-feeling="' . htmlspecialchars($row['post_feeling_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol">';
}
}
echo '<span class="empathy-count">' . mysqli_num_rows($resultB) . '</span></div></div></div></div>';
}
}
echo '</div></div>';
            }
        }
}
openFoot();