<?php
require_once '../inc/connect.php';
if($_SERVER['REQUEST_METHOD']!='POST') {
http_response_code(405);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>The page could not be found.</p></div>';
openFoot();
} else {
if($signed_in == false) {
http_response_code(401);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You must be signed in to do that. Are you in the right mode?</p></div>';
openFoot();
} else {
$whoDidThis = 'SELECT post_by FROM posts WHERE post_id = ' . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result) == 0) {
http_response_code(404);
echo 'Post not found.';
} else {
$row = mysqli_fetch_assoc($result);
if($row['post_by']!=$_SESSION['user_pid'] && $_SESSION['user_rank']<2) {
http_response_code(403);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You don\'t have permission to do that.</p></div>';
openFoot();
} else {
if($_POST['feeling_id']<0 || $_POST['feeling_id']>5 || mb_strlen($_POST['feeling_id'])==0 || !isset($_POST['feeling_id'])){
if($_SESSION['user_rank']!=5){
http_response_code(400);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You have entered an invalid feeling ID.</p></div>';
openFoot();
exit();
}}
if($_POST['is_spoiler']=='' || $_POST['is_spoiler']==0){
$is_spoiler = 0;
} elseif($_POST['is_spoiler']==1){
$is_spoiler = 1;
} else {
http_response_code(400);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You have entered an invalid spoiler value.</p></div>';
openFoot();
exit();
}
if(mb_strlen($_POST['body'])>1000 || mb_strlen($_POST['body'])==0 || !isset($_POST['body'])){
http_response_code(400);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>The text you entered is too long or empty.</p></div>';
openFoot();
exit();
}
$deletePost = 'UPDATE posts SET post_content = "' . mysqli_real_escape_string($link, $_POST['body']) . '", post_feeling_id = ' . mysqli_real_escape_string($link, $_POST['feeling_id']) . ', post_is_spoiler = ' . $is_spoiler . ', post_edited = NOW() WHERE post_id = ' . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $deletePost);
if(!$result){
http_response_code(400);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>An error occurred while trying to perform that action.</p></div>';
openFoot();
} else {
header('Location: /posts/' . $_GET['id']);
}}}}}