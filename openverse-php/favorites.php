<?php
$title = 'Favorite Communities';
$selected = 'communities';
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if($signed_in==true){
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Communities</h2>';
$csql = 'SELECT * FROM favorites LEFT JOIN communities ON community_id = favorite_to LEFT JOIN titles ON title_id = community_title WHERE favorite_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' ORDER BY favorite_id DESC';
$cresult = mysqli_query($link, $csql);
if(!mysqli_error($link)) {
if(mysqli_num_rows($cresult)!=0) {
echo '<ul class="list community-list">';
while($crow = mysqli_fetch_assoc($cresult)) {
echo '<li class="trigger" data-href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '"><div class="community-list-body"><a class="icon-container" href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '"><img class="icon" src="' . htmlspecialchars($crow['community_icon']) . '"></a><div class="body"><a class="title" href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '">' . htmlspecialchars($crow['community_name']) . '</a>';
if($crow['community_platform']<3){
echo '<span class="platform-tag"><img src="/assets/img/platform-tag-';
if($crow['community_platform']==0) {
echo '3ds';
} elseif($crow['community_platform']==1){
echo 'wiiu';
} elseif($crow['community_platform']==2){
echo 'wiiu-3ds';
}
echo '.png"></span>';
}
echo '<span class="text">' . htmlspecialchars($crow['title_name']) . '</span></div></div></li>';
}
echo '</ul>';
} else {
echo '<div class="no-content"><p>Tap the ☆ button on a community\'s page to have it show up as a favorite community here.</p></div>';
}} else {
echo '<div class="no-content"><p>An error occurred while trying to grab community data.</p></div>';
}
echo '</div></div>';
}
openFoot();