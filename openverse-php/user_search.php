<?php
$title = 'Search Users';
$selected = 'activity';
require_once '../inc/connect.php';
array_push($classes, 'search');
require_once '../inc/htm.php'; openHead();
if(isset($_GET['offset']) && $_GET['offset']!=''){
$offset = htmlspecialchars($_GET['offset']);
} else {
$offset = 0;
}
if($signed_in) {
if(isset($_GET['query'])){
$sql = 'SELECT * FROM users WHERE users.user_pid = "' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . '"';
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$personal = true;
include 'user_sidebar.php';
echo '<div class="main-column post-list-outline"><h2 class="label">Search Users</h2><form class="search user-search" action="/users"><input type="text" name="query" value="' . htmlspecialchars($_GET['query']) . '" placeholder="Arian K., ariankordi, etc." minlength="1" maxlength="32"><input type="submit" value="q" title="Search"></form>';
$fsql = 'SELECT * FROM users WHERE user_id = "' . mysqli_real_escape_string($link, $_GET['query']) . '" UNION SELECT * FROM users WHERE user_name = "' . mysqli_real_escape_string($link, $_GET['query']) . '" ORDER BY user_id DESC';
$fresult = mysqli_query($link, $fsql);
if(!mysqli_error($link)) {
if(mysqli_num_rows($fresult)!=0) {

echo '<div class="search-content search-user-content"><p class="user-found note">Found: ' . htmlspecialchars($_GET['query']) . '</p><div class="list"><ul id="searched-user-list" class="list-content-with-icon-and-text arrow-list" data-next-page-url>';
while($frow = mysqli_fetch_assoc($fresult)) {
$avatar = $frow['user_avatar'];
$feeling_id = false;
include 'avatar.php';
echo '<li class="trigger" data-href="/users/' . htmlspecialchars($frow['user_id']) . '"><a class="icon-container';
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
echo '" href="/users/' . htmlspecialchars($frow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a>';
if(mysqli_num_rows(mysqli_query($link,'SELECT follow_id FROM follows WHERE follow_by = '.mysqli_real_escape_string($link,$_SESSION['user_pid']).' AND follow_to = '.mysqli_real_escape_string($link,$frow['user_pid'])))==0 && $frow['user_pid']!=$_SESSION['user_pid']) {
echo '<div class="toggle-button"><button type="button" class="follow-button button symbol" data-action="/users/' . htmlspecialchars($frow['user_id']) . '/follow">Follow</button></div>';
}
echo '<div class="body"><p class="title"><span class="nick-name"><a href="/users/' . htmlspecialchars($frow['user_id']) . '">' . htmlspecialchars($frow['user_name']) . '</a></span> <span class="id-name">' . htmlspecialchars($frow['user_id']) . '</span></p><p class="text">' . htmlspecialchars($frow['user_profile_comment']) . '</p></div></li>';
}
echo '</ul>';
} else {
echo '<div class="search-content no-content search-user-content"><div class="search-content no-title-content"><p>"' . htmlspecialchars($_GET['query']) . '" could not be found.<br>Select Retry Search if you want to try again.</p></div></div>';
}} else {
echo '<div class="search-content no-content search-user-content"><div class="search-content no-title-content"><p>An error occurred while trying to search for that term.</p></div></div>';
}
echo '</div>';
} else {
http_response_code(400);
echo '<div class="no-content"><p>There was an error with your request.</p></div>';
}} else {
http_response_code(401);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();