<?php
$title = 'Messages';
$personal = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if($signed_in==true){
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Messages</h2><div class="list follow-list">';
$fsql = 'SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_to WHERE friend_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' UNION SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_by WHERE friend_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' ORDER BY friend_id DESC';
$fresult = mysqli_query($link, $fsql);
if(!mysqli_error($link)) {
if(mysqli_num_rows($fresult)!=0){
echo '<ul class="list-content-with-icon-and-text">';
while($frow = mysqli_fetch_assoc($fresult)) {
$avatar = $frow['user_avatar'];
$feeling_id = false;
include 'avatar.php';
echo '<li class="trigger" data-href="/messages/' . htmlspecialchars($frow['user_id']) . '"><a class="icon-container';
if($frow['user_rank'] == 1) {
echo ' donator';
}
if($frow['user_rank'] == 2) {
echo ' tester';
}
if($frow['user_rank'] == 3) {
echo ' moderator';
}
if($frow['user_rank'] == 4) {
echo ' administrator';
}
if($frow['user_rank'] == 5) {
echo ' developer';
}
echo '" href="/users/' . htmlspecialchars($frow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><p class="title"><span class="nick-name"><a href="/users/' . htmlspecialchars($frow['user_id']) . '">' . htmlspecialchars($frow['user_name']) . '</a></span> <span class="id-name">' . htmlspecialchars($frow['user_id']) . '</span></p><p class="text">You haven\'t exchanged messages with this user yet.</p></div></li>';
}
echo '</ul>';
} else {
echo '<div class="no-content"><p>You currently have no friends.</p></div>';
}} else {
echo '<div class="no-content"><p>An error occurred while trying to grab message data.</p></div>';
}
echo '</div></div></div>';
} else {
http_response_code(403);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();