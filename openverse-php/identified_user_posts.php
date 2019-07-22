<?php
$title = 'Posts from Verified Users';
$selected = 'communities';
require_once '../inc/connect.php';
array_push($classes, 'identified_user');
array_push($classes, 'is-autopagerized');
require_once '../inc/htm.php'; openHead();
$sql = 'SELECT * FROM users LEFT JOIN posts ON posts.post_by = users.user_pid WHERE users.user_pid = "' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . '"';
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$personal = true;
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><div id="image-header-content"><span class="image-header-title"><span class="title">Posts from Verified Users</span><span class="text">Get the latest news here!</span></span><img src="https://d13ph7xrk1ee39.cloudfront.net/img/identified-user.png"></div><div class="list post-list js-post-list">';
$postArray = [];
$sql = 'SELECT * FROM users WHERE user_rank > 2';
$result = mysqli_query($link, $sql);
if(!mysqli_error($link)){
while ($row = mysqli_fetch_assoc($result)) {
$fresult = mysqli_query($link, 'SELECT * FROM posts LEFT JOIN users ON users.user_pid = posts.post_by LEFT JOIN communities ON communities.community_id = posts.post_community WHERE post_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND post_status < 2');
while ($frow = mysqli_fetch_assoc($fresult)) {
array_push($postArray, $frow);
}}
function sortFunction($a, $b) {
    return strtotime($b['post_date']) - strtotime($a['post_date']);
}
usort($postArray, "sortFunction");
$i = 0;
                foreach($postArray as $row)
                {
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<div class="post trigger" data-href="/posts/' . htmlspecialchars($row['post_id']) . '"><p class="community-container"><a href="/titles/1/' . htmlspecialchars($row['community_id']) . '"><img class="community-icon" src="' . htmlspecialchars($row['community_icon']) . '">' . htmlspecialchars($row['community_name']) . '</a></p><a class="icon-container';
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
if(strlen($row['user_profile_comment']) > 0) {
echo '<p class="text">' . htmlspecialchars($row['user_profile_comment']) .'</p>';    
}
echo '<div class="body post-content">';
if (preg_match('/(http:\/\/|https:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu\.be\/))([A-Za-z0-9-_]{11}).*/i', $row['post_url'], $res)) {
echo '<a href="/posts/'.$row['post_id'].'" class="screenshot-container video"><img height="48" src="https://i.ytimg.com/vi/'.$res[6].'/default.jpg"></a>';
}
if($row['post_screenshot']) {
echo '<a class="screenshot-container still-image" href="/posts/' . $row['post_id'] . '"><img src="' . htmlspecialchars($row['post_screenshot']) . '"></a>';
}
if(mb_strlen($row['post_content'],'utf8')!=0){
echo '<p class="post-content-text">';
if(mb_strlen($row['post_content'],'utf8')<204) {
echo htmlspecialchars($row['post_content']);
} else {
echo mb_substr(htmlspecialchars($row['post_content']), 0, 200, 'utf8') . '...';
}
echo '</p>';
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
echo '<span class="empathy-count">' . mysqli_num_rows($resultB) . '</span></div><div class="reply symbol"><span class="reply-count">' . mysqli_num_rows($resultD) . '</span></div></div></div></div>';
            }
} else {
echo '<div class="post-list-outline no-content"><p>The posts could not be loaded.</p></div>';
}
echo '</div></div></div>';
openFoot();