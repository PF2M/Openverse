<?php
$title = 'Notifications';
$selected = 'news';
$personal = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if($signed_in==true){
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Notifications</h2><div class="list news-list">';
$nsql = 'SELECT * FROM notifications LEFT JOIN users ON users.user_pid = notifications.notif_by LEFT JOIN posts ON posts.post_id = notifications.notif_topic LEFT JOIN replies ON replies.reply_id = notifications.notif_topic WHERE notif_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' ORDER BY notif_id DESC';
$nresult = mysqli_query($link, $nsql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>An error occurred while grabbing the notifications.</p></div>';
} elseif(mysqli_num_rows($nresult)==0) {
echo '<div class="no-content"><p>You have no notifications.</p></div>';
} else {
while($nrow = mysqli_fetch_assoc($nresult)) {
$avatar = $nrow['user_avatar'];
$feeling_id = false;
include 'avatar.php';
if($nrow['notif_type'] == 0) {
echo '<div class="news-list-content';
if($nrow['notif_read']==0) {
echo ' notify';
}
echo ' trigger" data-href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '"><a class="icon-container" href="/users/' . htmlspecialchars($nrow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><a class="nick-name" href="/users/' . htmlspecialchars($nrow['user_id']) . '">' . htmlspecialchars($nrow['user_name']) . '</a> gave <a class="link" href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">your post (';
if (strlen($nrow['post_content'])>18) {
echo htmlspecialchars(substr($nrow['post_content'], 0, 15)) . '...';
} else {
echo htmlspecialchars($nrow['post_content']);
}
echo ')</a> a Yeah. <span class="timestamp">' . date("m/d/Y H:i:s",strtotime($nrow['notif_date'])) . '</span></div></div>';
}

if($nrow['notif_type'] == 1) {
echo '<div class="news-list-content';
if($nrow['notif_read']==0) {
echo ' notify';
}
echo ' trigger" data-href="/replies/' . htmlspecialchars($nrow['notif_topic']) . '"><a class="icon-container" href="/users/' . htmlspecialchars($nrow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><a class="nick-name" href="/users/' . htmlspecialchars($nrow['user_id']) . '">' . htmlspecialchars($nrow['user_name']) . '</a> gave <a class="link" href="/replies/' . htmlspecialchars($nrow['notif_topic']) . '">your reply (';
if (strlen($nrow['reply_content'])>18) {
echo htmlspecialchars(substr($nrow['reply_content'], 0, 15)) . '...';
} else {
echo htmlspecialchars($nrow['reply_content']);
}
echo ')</a> a Yeah. <span class="timestamp">' . date("m/d/Y H:i:s",strtotime($nrow['notif_date'])) . '</span></div></div>';
}

if($nrow['notif_type'] == 2) {
echo '<div class="news-list-content';
if($nrow['notif_read']==0) {
echo ' notify';
}
echo ' trigger" data-href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '"><a class="icon-container" href="/users/' . htmlspecialchars($nrow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><a class="nick-name" href="/users/' . htmlspecialchars($nrow['user_id']) . '">' . htmlspecialchars($nrow['user_name']) . '</a> replied to <a class="link" href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">your post (';
if (strlen($nrow['post_content'])>18) {
echo htmlspecialchars(substr($nrow['post_content'], 0, 15)) . '...';
} else {
echo htmlspecialchars($nrow['post_content']);
}
echo ')</a>. <span class="timestamp">' . date("m/d/Y H:i:s",strtotime($nrow['notif_date'])) . '</span></div></div>';
}

if($nrow['notif_type'] == 3) {

}

if($nrow['notif_type'] == 4) {

}

if($nrow['notif_type'] == 5) {

}

}}
echo '</div></div></div>';
mysqli_query($link, 'UPDATE notifications SET notif_read = 1 WHERE notif_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']));
} else {
http_response_code(403);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();