<?php
require_once '../inc/connect.php';
if($signed_in && $_GET['id']==$_SESSION['user_id']) {
$title = 'User Page';
$selected = 'user';
} else {
$title = htmlspecialchars($_GET['id']) . '\'s Profile';
}
array_push($classes, 'profile-top');
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
include 'user_sidebar.php';

echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Recent Posts</h2><div class="post-list empty"><p>This user has not made any posts yet.</p></div></div><a class="big-button" href="/users/' . htmlspecialchars($row['user_id']) . '/posts">View Posts</a><div class="post-list-outline"><h2 class="label">Recent Yeahs</h2><div class="post-list empty"><p>This user has not given any Yeahs yet.</p></div></div><a class="big-button" href="/users/' . htmlspecialchars($row['user_id']) . '/empathies">View Yeahs</a></div>';

}}}
openFoot();