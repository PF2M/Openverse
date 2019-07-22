<?php
$fuck = 0;
echo '<div id="sidebar" class="';
if(isset($personal) && $personal == true) {
    echo 'general';
} else {
    echo 'user';
}
echo '-sidebar">';
if ((!empty($personal) && $signed_in == true) || empty($personal)) {
echo '<div class="sidebar-container">';
if ($row['user_favorite_post']!=0) {
$fpsql = 'SELECT * FROM posts WHERE post_id = ' . mysqli_real_escape_string($link, $row['user_favorite_post']);
$fpresult = mysqli_query($link, $fpsql);
$fprow = mysqli_fetch_assoc($fpresult);
if($fprow["post_status"] == 0) {
    echo '<a id="sidebar-cover" class="sidebar-cover-image" href="/posts/'.htmlspecialchars($row['user_favorite_post']).'" style="background-image: url('.htmlspecialchars($fprow['post_screenshot']).')"><img src="'.htmlspecialchars($fprow['post_screenshot']).'"></a>';
} else {
    $fuck = 1;
}
}
echo '<div id="sidebar-profile-body"';
if ($row['user_favorite_post']!=0 && $fuck == 0) {
echo ' class="with-profile-post-image"';
}
$avatar = $row['user_avatar'];
$feeling_id = false;
include 'avatar.php';
echo '><div class="icon-container';
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
echo '"><a href="/users/'.htmlspecialchars($row['user_id']).'"><img src="'.$avatar.'" class="icon"></a></div>';
if(strlen($row['user_rank'])!=0 && $row['user_rank']!=0) {
echo '<p class="user-organization">';
$bresult = mysqli_query($link, 'SELECT * FROM bans WHERE ban_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' ORDER BY ban_id DESC');
if(mysqli_num_rows($bresult) > 0){
$brow = mysqli_fetch_assoc($bresult);
if($brow['ban_length'] == 0 || time() < (strtotime($brow['ban_date']) + ($brow['ban_length'] * 86400))) {
echo 'Banned ';
}
}
if($row['user_rank'] == 1) {
echo 'Donator';
}
if($row['user_rank'] == 2) {
echo 'Tester';
}
if($row['user_rank'] == 3) {
echo 'Moderator';
}
if($row['user_rank'] == 4) {
echo 'Administrator';
}
if($row['user_rank'] == 5) {
echo 'Developer';
}
echo '</p>';
}
echo '<a href="/users/'.htmlspecialchars($row['user_id']).'" class="nick-name">'.htmlspecialchars($row['user_name']).'</a><p class="id-name">'.htmlspecialchars($row['user_id']).'</p></div>';
if(!isset($personal) || $personal == false) {
if($row['user_pid']!=$_SESSION['user_pid']){
echo '<div class="user-action-content"><div class="toggle-button"><button type="button" class="follow-button button symbol';
if(mysqli_num_rows(mysqli_query($link, 'SELECT follow_to FROM follows WHERE follow_to = '.mysqli_real_escape_string($link,$row['user_pid']).' AND follow_by = '.mysqli_real_escape_string($link,$_SESSION['user_pid'])))!=0) {
$following = true;
echo ' none';
}
echo '" data-action="/users/' . htmlspecialchars($row['user_id']) . '/follow" data-screen-name="' . htmlspecialchars($row['user_name']) . '">Follow</button><button type="button" class="unfollow-button button symbol';
if($following!=true) {
echo ' none';
}
echo '" data-action="/users/' . htmlspecialchars($row['user_id']) . '/unfollow" data-screen-name="' . htmlspecialchars($row['user_name']) . '">Follow</button></div></div>';
} else {
echo '<div id="edit-profile-settings"><a href="/settings/profile" class="button symbol">Profile Settings</a></div>';
}}
echo '<ul id="sidebar-profile-status"><li><a href="/users/'.htmlspecialchars($row['user_id']).'/following"';
if($selected == 'following') {
echo ' class="selected"';
}
echo '><span class="number">';
$following = mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_by = '.mysqli_real_escape_string($link, $row['user_pid'])));
$followers = mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = '.mysqli_real_escape_string($link, $row['user_pid'])));
if($row['user_relationship_visibility'] == 2) {
    if($signed_in && $_SESSION['user_pid'] == $row['user_pid']) {
        echo $following;
    } else {
        echo '-';
    }
} else if ($row['user_relationship_visibility'] == 1) {
    if($signed_in && ($_SESSION['user_pid'] == $row['user_pid']) || (mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0)) {
        echo $following;
    } else {
        echo '-';
    }
} else { 
echo $following;
}
echo '</span>Following</a></li><li><a href="/users/'.htmlspecialchars($row['user_id']).'/followers"';
if($selected == 'followers') {
echo ' class="selected"';
}
echo '><span class="number">';
if($row['user_relationship_visibility'] == 2) {
    if($signed_in && $_SESSION['user_pid'] == $row['user_pid']) {
        echo $followers;
    } else {
        echo '-';
    }
} else if ($row['user_relationship_visibility'] == 1) {
    if($signed_in && ($_SESSION['user_pid'] == $row['user_pid']) || (mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0)) {
        echo $followers;
    } else {
        echo '-';
    }
} else { 
echo $followers;
}
echo '</span>Followers</a></li></ul></div>';
}
if(!isset($personal) || $personal == false) {
echo '<div class="sidebar-container sidebar-setting"><div class="sidebar-post-menu"><a href="/users/'.htmlspecialchars($row['user_id']).'/posts" class="sidebar-menu-post with-count symbol';
if($selected == 'posts') {
echo ' selected';
}
echo '"><span>All Posts</span><span class="post-count"><span class="test-post-count">' . mysqli_num_rows(mysqli_query($link, 'SELECT post_id FROM posts WHERE post_by = '.mysqli_real_escape_string($link,$row['user_pid']))) . '</span></span></a><a href="/users/'.htmlspecialchars($row['user_id']).'/empathies" class="sidebar-menu-empathies with-count symbol';
if($selected == 'empathies') {
echo ' selected';
}
echo '"><span>Yeahs</span><span class="post-count"><span class="test-empathy-count">';
echo mysqli_num_rows(mysqli_query($link,'SELECT yeah_id FROM post_yeahs WHERE yeah_by = '.mysqli_real_escape_string($link,$row['user_pid'])))+mysqli_num_rows(mysqli_query($link,'SELECT ryeah_id FROM reply_yeahs WHERE ryeah_by = '.mysqli_real_escape_string($link,$row['user_pid'])));
echo '</span></span></a></div></div><div class="sidebar-container sidebar-profile">';
if(strlen($row['user_profile_comment'])!=0){
echo '<div class="profile-comment"><p class="js-truncated-text">'.htmlspecialchars($row['user_profile_comment']).'</p></div>';
}
echo '<div class="user-data"><div class="data-content"><h4><span>Region</span></h4><div class="note"><span>';
if(strlen($row['user_country'])!=0){
echo htmlspecialchars($row['user_country']);
} else {
echo 'Not Set';
}
echo '</span></div></div><div class="data-content"><h4><span>NNID</span></h4><div class="note"><span>';
if(!empty($row['user_nnid'])){
echo htmlspecialchars($row['user_nnid']);
} else {
echo 'Not Set';
}
echo '</span></div></div><div class="data-content game-skill"><h4><span>Game Experience</span></h4><div class="note">';
if(strlen($row['user_skill'])!=0){
if($row['user_skill']==1){
echo '<span class="beginner">Beginner</span>';
} else if($row['user_skill']==2){
echo '<span class="intermediate">Intermediate</span>';
} else if($row['user_skill']==3){
echo '<span class="expert">Expert</span>';
} else {
echo '<span>Not Set</span>';
}} else {
echo '<span>Not Set</span>';
}
echo '</div></div><div class="data-content"><h4><span>Member ID</span></h4><div class="note"><span>#' . number_format($row['user_pid']) . '</span></div></div><div class="favorite-game-genre"><h4><span>Website</span></h4><div class="note"><span>';
if(strlen($row['user_website'])!=0){
echo '<a href="'.htmlspecialchars($row['user_website']).'">'.htmlspecialchars($row['user_website']).'</a>';
} else {
echo 'Not Set';
}
echo '</span></div></div></div></div><div class="sidebar-container sidebar-favorite-community"><h4><a href="';
if($signed_in && $row['user_pid']==$_SESSION['user_pid']) {
echo '/communities/favorites';
} else {
echo '/users/' . htmlspecialchars($row['user_id']) . '/favorites';
}
echo '" class="favorite-community-button symbol"><span>Favorite Communities</span></a></h4><ul>';
$cresult = mysqli_query($link, 'SELECT * FROM favorites LEFT JOIN communities ON community_id = favorite_to WHERE favorite_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' ORDER BY favorite_id DESC LIMIT 0,10');
while($crow = mysqli_fetch_assoc($cresult)){
echo '<li class="favorite-community"><a href="/titles/' . htmlspecialchars($crow['community_title']) . '/' . htmlspecialchars($crow['community_id']) . '"><span class="icon-container"><img class="icon" src="' . htmlspecialchars($crow['community_icon']) . '"></span></a>';
if($crow['community_platform'] < 3){
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
echo '</li>';
}
for($x=mysqli_num_rows($cresult); $x<10; $x++){
echo '<li class="favorite-community empty"><span class="icon-container empty-icon"><img class="icon" src="/assets/img/empty.png"></span></li>';
}
echo '</ul></div>';
} else {
echo '<div class="sidebar-container sidebar-setting"><ul><li><a class="sidebar-menu-setting symbol" href="/settings/profile"><span>Profile Settings</span></a></li><li><a class="sidebar-menu-info symbol" href="/titles/1/1"><span>Openverse Announcements</span></a></li><li><a class="sidebar-menu-info symbol" href="/titles/1/2"><span>Openverse Changelog</span></a></li><li><a class="sidebar-menu-guide symbol" href="/guide/rules"><span>Openverse Code of Conduct</span></a></li><li><a class="sidebar-menu-guide symbol" href="/guide/faq"><span>Frequently Asked Questions (FAQ)</span></a></li></ul></div>';
}
echo '</div>';