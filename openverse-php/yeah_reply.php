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
$whoDidThis = "SELECT reply_by, user_skill FROM replies LEFT JOIN users ON users.user_pid = replies.reply_by WHERE reply_id = " . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result)==0){
http_response_code(404);
echo 'Reply not found.';
} else {
$row = mysqli_fetch_assoc($result);
if($row['reply_by']==$_SESSION['user_pid']){
http_response_code(403);
echo 'You don\'t have permission to give a Yeah to that reply.';
} else {
$testYeah = "SELECT * FROM reply_yeahs WHERE ryeah_reply = " . mysqli_real_escape_string($link, $_GET['id']) . " AND ryeah_by = " . $_SESSION['user_pid'];
$result = mysqli_query($link, $testYeah);
if(mysqli_num_rows($result)!=0) {
echo 'Reply yeahed successfully!';
} else {
        $sql = "INSERT INTO
                    reply_yeahs(ryeah_reply, ryeah_by, ryeah_date)
                VALUES ('" . $_GET['id'] . "',
                        '" . $_SESSION['user_pid'] . "',
                        NOW())";
        $result = mysqli_query($link, $sql);
        if(!$result) {
http_response_code(400);
            echo 'An error occurred while trying to give a Yeah to that reply.';
        } else {
if($row['user_skill']!=2){
$reply_by = mysqli_real_escape_string($link, $row['reply_by']);
$user_pid = mysqli_real_escape_string($link, $_SESSION['user_pid']);
$id = mysqli_real_escape_string($link, $_GET['id']);
mysqli_query($link,'DELETE FROM notifications WHERE notif_to = '.$reply_by.' AND notif_by = '.$user_pid.' AND notif_topic = '.$id.' AND notif_type = 1');
mysqli_query($link,'INSERT INTO notifications(notif_to,notif_by,notif_topic,notif_type,notif_read,notif_date) VALUES ('.$reply_by.','.$user_pid.','.$id.',1,0,NOW())');
}
echo 'Reply yeahed successfully!';
}}}}}}