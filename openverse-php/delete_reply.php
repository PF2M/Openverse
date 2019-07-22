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
$whoDidThis = 'SELECT reply_by FROM replies WHERE reply_id = ' . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $whoDidThis);
if(mysqli_num_rows($result) == 0) {
http_response_code(404);
echo '{"success":0,"errors":"That reply could not be found."}';
} else {
$row = mysqli_fetch_assoc($result);
if($row['reply_by'] != $_SESSION['user_pid']) {
http_response_code(403);
echo '{"success":0,"errors":"You don\'t have permission to delete this reply."}';
} else {
$deleteReply = 'UPDATE replies SET reply_status = 1 WHERE reply_id = ' . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $deleteReply);
if(!$result){
http_response_code(400);
echo '{"success":0}';
} else {
echo '{"success":1}';
}}}}}