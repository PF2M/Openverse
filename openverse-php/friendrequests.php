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

// Here, check for friend requests that aren't finished. If there is at least one, print a data modal which will be href'd to and will accept the FR.
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Notifications</h2><div class="tab-container"><div class="tab2"><a href="/news/my_news">Updates</a><a class="selected" href="/news/friend_requests">Friend Requests</a></div></div><br>

<div class="no-content"><p>Will be worked on eventually.</p></div>';

echo '</div></div>';

} else {
http_response_code(403);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();