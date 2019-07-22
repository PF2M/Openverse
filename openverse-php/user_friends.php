<?php
require_once '../inc/connect.php';
if(isset($_SESSION) && $_GET['id']==$_SESSION['user_id']) {
$title = 'User Page';
} else {
$title = htmlspecialchars($_GET['id']) . '\'s Profile';
}
require_once '../inc/htm.php'; openHead();
$sql = 'SELECT * FROM users LEFT JOIN posts ON posts.post_by = users.user_pid WHERE users.user_id = "' . mysqli_real_escape_string($link, $_GET['id']) . '"';
$result = mysqli_query($link, $sql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>There was an error while searching for that user.<br>Error: ' . mysqli_error($link) . '</p></div>';
} else {
$row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result)==0) {
            http_response_code(404);
            echo '<div class="no-content"><p>That user could not be found.</p></div>';
        }
        else
        {
            {
$selected = 'friends';
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">' . htmlspecialchars($row['user_name']) . '\'s Friends</h2><div class="list follow-list">';
$fsql = 'SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_to WHERE friend_by = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' UNION SELECT * FROM friendships LEFT JOIN users ON user_pid = friend_by WHERE friend_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' ORDER BY friend_id DESC';
$fresult = mysqli_query($link, $fsql);
if(!mysqli_error($link)) {
if(mysqli_num_rows($fresult)!=0) {

echo '<ul class="list-content-with-icon-and-text">';
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
echo '" href="/users/' . htmlspecialchars($frow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><p class="title"><span class="nick-name"><a href="/users/' . htmlspecialchars($frow['user_id']) . '">' . htmlspecialchars($frow['user_name']) . '</a></span> <span class="id-name">' . htmlspecialchars($frow['user_id']) . '</span></p><p class="text">' . htmlspecialchars($frow['user_profile_comment']) . '</p></div></li>';
}
echo '</ul>';
} else {
echo '<div class="no-content"><p>This user isn\'t currently friends with anyone.</p></div>';
}} else {
echo '<div class="no-content"><p>An error occurred while trying to grab the data for that user.</p></div>';
}
echo '</div></div></div>';
            }
        }
}
openFoot();