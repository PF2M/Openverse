<?php
require_once '../inc/connect.php';
if($_SERVER['REQUEST_METHOD'] != 'POST') {
http_response_code(405);
echo 'The URL you specified can\'t be typed manually.';
} else {
if(!$_SESSION['signed_in']) {
http_response_code(403);
echo 'You must be signed in to do that. Are you in the right mode?';
} else {
$whoDidThis = "SELECT * FROM posts WHERE post_id = " . $_GET['id'];
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result)==0){
http_response_code(404);
echo 'Post not found.';
} else {
if(mysqli_fetch_assoc($result)['post_by']==$_SESSION['user_pid']){
http_response_code(403);
echo 'You don\'t have permission to give a Yeah to that post.';
} else {
$testYeah = "SELECT * FROM post_yeahs WHERE yeah_post = " . mysqli_real_escape_string($link, $_GET['id']) . " AND yeah_by = " . $_SESSION['user_pid'];
$result = mysqli_query($link, $testYeah);
if(mysqli_num_rows($result)!=0) {
$deleteYeah = "DELETE FROM post_yeahs WHERE yeah_id = " . mysqli_fetch_assoc($result)['yeah_id'];
$result = mysqli_query($link, $deleteYeah);
if(!$result){
http_response_code(400);
echo 'An error occurred while trying to unyeah that post.';
} else {
echo 'Post unyeahed successfully!';
}}}}}}