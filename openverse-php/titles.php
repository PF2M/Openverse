<?php
require_once '../inc/connect.php';
$tsql = "SELECT * FROM titles WHERE title_id = " . mysqli_real_escape_string($link, $_GET['id']);
$tresult = mysqli_query($link, $tsql);
if(mysqli_num_rows($tresult)!=0){
$trow = mysqli_fetch_assoc($tresult);
$title = htmlspecialchars($trow['title_name']);
$selected = 'communities';
} else {
$title = 'Error';
}
require_once '../inc/htm.php'; openHead();
if(mysqli_num_rows($tresult)==0){
echo '<div class="no-content"><p>That title could not be found.</p></div>';
} else {
echo '<div id="sidebar"><section class="sidebar-container" id="sidebar-community"><span id="sidebar-cover"> <a href="/titles/' . htmlspecialchars($trow['title_id']) . '"><img src="' . htmlspecialchars($trow['title_banner']) . '"></a></span><header id="sidebar-community-body"><span id="sidebar-community-img"><span class="icon-container"><a href="/titles/' . htmlspecialchars($trow['title_id']) . '"><img src="' . htmlspecialchars($trow['title_icon']) . '" class="icon"></a></span>';
if($trow['title_platform']<3) {
echo '<span class="platform-tag"><img src="/assets/img/platform-tag-'; 
if($trow['title_platform']==0) {
echo '3ds';
} elseif($trow['title_platform']==1){
echo 'wiiu';
} elseif($trow['title_platform']==2){
echo 'wiiu-3ds';
}
echo '.png"></span>';
}
echo '</span><h1 class="community-name"><a href="/titles/' . htmlspecialchars($trow['title_id']) . '">' . htmlspecialchars($trow['title_name']) . '</a></h1></header></section></div><div class="main-column"><div class="post-list-outline"><h2 class="label">Communities</h2><ul class="list community-list other-communities-list">';
$sql = 'SELECT * FROM communities WHERE community_title = ' . mysqli_real_escape_string($link, $trow['title_id']);
$result = mysqli_query($link, $sql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>There was an error while grabbing the community listing for that title.</p></div>';
} else {
if(mysqli_num_rows($result)==0){
echo '<div class="no-content"><p>This title has no communities.</p></div>';
} else {
while($row = mysqli_fetch_assoc($result)){
echo '<li class="trigger" data-href="/titles/' . htmlspecialchars($trow['title_id']) . '/' . htmlspecialchars($row['community_id']) . '"><div class="community-list-body"><span class="icon-container"><img class="icon" src="';
if(mb_strlen($row['community_icon'],'utf8')>0){
echo htmlspecialchars($row['community_icon']);
} else {
echo htmlspecialchars($trow['title_icon']);
}
echo '"></span><div class="body">';
if($row['community_type']==1){
echo '<span class="news-community-badge">Main Community</span>';
} elseif($row['community_type']==2){
echo '<span class="news-community-badge">Announcement Community</span>';
}
echo '<a class="title" href="/titles/' . htmlspecialchars($trow['title_id']) . '/' . htmlspecialchars($row['community_id']) . '">' . htmlspecialchars($row['community_name']) . '</a></div></div></li>';
}
}
}
echo '</ul></div></div>';
}
openFoot();