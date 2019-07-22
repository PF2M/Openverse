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
$whoDidThis = "SELECT post_by, user_skill FROM posts LEFT JOIN users ON users.user_pid = posts.post_by WHERE post_id = " . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result)==0){
http_response_code(404);
echo 'Post not found.';
} else {
$row = mysqli_fetch_assoc($result);
if($row['post_by']==$_SESSION['user_pid']){
http_response_code(403);
echo 'You don\'t have permission to give a Yeah to that post.';
} else {
$testYeah = "SELECT * FROM post_yeahs WHERE yeah_post = " . mysqli_real_escape_string($link, $_GET['id']) . " AND yeah_by = " . $_SESSION['user_pid'];
$result = mysqli_query($link, $testYeah);
if(mysqli_num_rows($result)!=0) {
echo 'Post yeahed successfully!';
} else {
        $sql = "INSERT INTO
                    post_yeahs(yeah_post, yeah_by, yeah_date)
                VALUES ('" . $_GET['id'] . "',
                        '" . $_SESSION['user_pid'] . "',
                        NOW())";
        $result = mysqli_query($link, $sql);
        if(!$result) {
http_response_code(400);
            echo 'An error occurred while trying to give a Yeah to that post.';
        } else {
if($row['user_skill']!=2){
$post_by = mysqli_real_escape_string($link, $row['post_by']);
$user_pid = mysqli_real_escape_string($link, $_SESSION['user_pid']);
$id = mysqli_real_escape_string($link, $_GET['id']);
mysqli_query($link,'DELETE FROM notifications WHERE notif_to = '.$post_by.' AND notif_by = '.$user_pid.' AND notif_topic = '.$id.' AND notif_type = 0');
mysqli_query($link,'INSERT INTO notifications(notif_to,notif_by,notif_topic,notif_type,notif_read,notif_date) VALUES ('.$post_by.','.$user_pid.','.$id.',0,0,NOW())');
}
echo 'Post yeahed successfully!';
}}}}}}