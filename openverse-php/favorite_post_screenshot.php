<?php
require_once '../inc/connect.php';
if($_SERVER['REQUEST_METHOD']!='POST') {
http_response_code(405);
echo '{"success":0,"errors":"You must use a POST request to do that."}';
} else {
if($signed_in == false) {
http_response_code(401);
echo '{"success":0,"errors":"You must be signed in to do that."}';
} else {
$whoDidThis = 'SELECT * FROM posts WHERE post_id = ' . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result) == 0) {
http_response_code(404);
echo '{"success":0,"errors":"That post could not be found."}';
} else {
$row = mysqli_fetch_assoc($result);
if($row['post_by'] != $_SESSION['user_pid']) {
http_response_code(403);
echo '{"success":0,"errors":"You don\'t have permission to do that."}';
} else {
if(strlen($row['post_screenshot']) == 0){
echo '{"success":0,"errors":"That post does not have a screenshot."}';
} else {
$deletePost = 'UPDATE users SET user_favorite_post = ' . mysqli_real_escape_string($link, $_GET['id']) . ', user_favorite_post_type = 0 WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $deletePost);
if(!$result){
http_response_code(400);
echo '{"success":0}';
} else {
echo '{"success":1}';
}}}}}}