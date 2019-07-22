<?php
$title = 'Search Communities';
$selected = 'communities';
require_once '../inc/connect.php';
array_push($classes, 'search');
require_once '../inc/htm.php'; openHead();
if($signed_in) {
if(isset($_GET['query']) && mb_strlen($_GET['query']) > 1){
$sql = 'SELECT * FROM users WHERE users.user_pid = "' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . '"';
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
$personal = true;
include 'user_sidebar.php';
echo '<div class="main-column post-list-outline"><h2 class="label">Search Communities</h2><form class="search user-search" action="/titles/search"><input type="text" name="query" value="' . htmlspecialchars($_GET['query']) . '" placeholder="Openverse, etc." minlength="1" maxlength="32"><input type="submit" value="q" title="Search"></form>';
$fsql = 'SELECT * FROM communities LEFT JOIN titles ON title_id = community_title WHERE community_name LIKE "%' . mysqli_real_escape_string($link, $_GET['query']) . '%" ORDER BY community_name';
$fresult = mysqli_query($link, $fsql);
if(!mysqli_error($link)) {
if(mysqli_num_rows($fresult)!=0) {
echo '<div class="search-content"><p class="note">Communities found for "' . htmlspecialchars($_GET['query']) . '".</p><div class="list"><ul class="list community-list community-title-list">';

while($row = mysqli_fetch_assoc($fresult)){
echo '<li class="trigger" data-href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><div class="community-list-body"><span class="icon-container"><img class="icon" src="';
if(mb_strlen($row['community_icon'],'utf8')>0){
echo htmlspecialchars($row['community_icon']);
} else {
echo htmlspecialchars($row['title_icon']);
}
echo '"></span><div class="body">';
if($row['community_type']==1){
echo '<span class="news-community-badge">Main Community</span>';
} elseif($row['community_type']==2){
echo '<span class="news-community-badge">Announcement Community</span>';
}
echo '<a class="title" href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '">' . htmlspecialchars($row['community_name']) . '</a>';
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
echo '<span class="text">';
if($row['title_type']==0){
if($row['title_platform']==0){
echo '3DS Games';
} elseif($row['title_platform']==1){
echo 'Wii U Games';
} elseif($row['title_platform']==2){
echo 'Wii U Games・3DS Games';
} else {
echo 'General Community';
}
} elseif($row['title_type']==2){
echo 'Special Community';
} else {
echo 'General Community';
}
echo '</span></div></div></li>';
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