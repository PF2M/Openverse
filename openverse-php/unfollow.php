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
$result = mysqli_query($link, 'SELECT user_pid FROM users WHERE user_id = "'.mysqli_real_escape_string($link, $_GET['id']).'"');
if(mysqli_num_rows($result)!=0) {
$row = mysqli_fetch_assoc($result);
if(mysqli_num_rows(mysqli_query($link, 'SELECT follow_id FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid'])))!=0){
$sql = 'DELETE FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
mysqli_query($link, $sql);
if(!mysqli_error($link)){
echo '{"success":1}';
} else {
http_response_code(400);
echo '{"success":0,"errors":"A database error occurred."}';
}
} else {
echo '{"success":1}';
}} else {
http_response_code(404);
echo '{"success":0,"errors":"That user could not be found."}';
}
}}