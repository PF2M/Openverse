<?php
$title = 'Messages';
$personal = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if($signed_in==true){
$result = mysqli_query($link, 'SELECT * FROM users WHERE user_id = "' . mysqli_real_escape_string($link, $_GET['id']) . '"');
if(mysqli_num_rows($result)!=0){
$temprow = mysqli_fetch_assoc($result);
$result = mysqli_query($link, 'SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_to WHERE friend_by = ' . mysqli_real_escape_string($link, $temprow['user_pid']) . ' UNION SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_by WHERE friend_to = ' . mysqli_real_escape_string($link, $temprow['user_pid']) . ' LIMIT 0,1');
if(mysqli_num_rows($result)>0){
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Conversation with ' . htmlspecialchars($temprow['user_name']) . ' (' . htmlspecialchars($temprow['user_id']) . ')</h2><div class="list follow-list">';
$result = mysqli_query($link, 'SELECT * FROM messages LEFT JOIN users ON user_pid = message_by WHERE (message_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND message_by = ' . $temprow['user_pid'] . ') OR (message_to = ' . $temprow['user_pid'] . ' AND message_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ')');
if(!mysqli_error($link)) {
if(mysqli_num_rows($fresult)!=0){
echo '<ul class="list-content-with-icon-and-text">';
while($frow = mysqli_fetch_assoc($fresult)) {
$avatar = $frow['user_avatar'];
$feeling_id = false;
include 'avatar.php';
}
echo '</ul>';
} else {
echo '<div class="no-content"><p>You currently have no messages.</p></div>';
}} else {
echo '<div class="no-content"><p>An error occurred while trying to grab message data.</p></div>';
}
echo '</div></div></div>';
} else {
http_response_code(403);
echo '<div class="no-content"><p>You\'re not friends with that user.</p></div>';
}} else {
http_response_code(404);
echo '<div class="no-content"><p>That user does not exist.</p></div>';
}} else {
http_response_code(401);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();