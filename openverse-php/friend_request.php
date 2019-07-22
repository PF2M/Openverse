<?php
require_once '../inc/connect.php';
header('Content-Type: application/json');
if($signed_in!=true){
http_response_code(401);
echo '{"success":0,"errors":"You must be signed in to do that."}';
} else {
if($_SERVER['REQUEST_METHOD']!='POST'){
http_response_code(405);
echo '{"success":0,"errors":"You must use a POST request to do that."}';
} else {
$followCount = mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid'])));
if($followCount<1001){
$result = mysqli_query($link, 'SELECT user_pid FROM users WHERE user_id = "'.mysqli_real_escape_string($link, $_GET['id']).'"');
if(mysqli_num_rows($result)!=0) {
$row = mysqli_fetch_assoc($result);
if($row['user_pid']!=$_SESSION['user_pid']){
if(mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND follow_by = '.mysqli_real_escape_string($link, $_SESSION['user_pid'])))==0){
$sql = 'INSERT INTO follows(follow_to, follow_by) VALUES(' . mysqli_real_escape_string($link, $row['user_pid']) . ', ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ')';
mysqli_query($link, $sql);
if(!mysqli_error($link)){
mysqli_query($link,'DELETE FROM notifications WHERE notif_to = '.mysqli_real_escape_string($link,$row['user_pid']).' AND notif_by = '.mysqli_real_escape_string($link,$_SESSION['user_pid']).' AND notif_type = 4');
mysqli_query($link,'INSERT INTO notifications(notif_to,notif_by,notif_topic,notif_type,notif_read,notif_date) VALUES ('.mysqli_real_escape_string($link,$row['user_pid']).','.mysqli_real_escape_string($link,$_SESSION['user_pid']).',0,4,0,NOW())');
echo '{"success":1,"can_follow_more":';
if($followCount+1<1000){
echo 'true';
} else {
echo 'false';
}
echo ',"following_count":';
echo $followCount+1;
echo '}';
} else {
http_response_code(400);
echo '{"success":0,"errors":"A database error occurred."}';
}
} else {
echo '{"success":1,"can_follow_more":';
if($followCount<1000){
echo 'true';
} else {
echo 'false';
}
echo ',"following_count":';
echo $followCount;
echo '}';
}} else {
echo '{"success":0,"errors":"You cannot follow yourself."}';
}} else {
http_response_code(404);
echo '{"success":0,"errors":"That user could not be found."}';
}
} else {
http_response_code(403);
echo '{"success":0,"errors":"You\'re already following the maximum amount of users you can follow."}';
}
}}