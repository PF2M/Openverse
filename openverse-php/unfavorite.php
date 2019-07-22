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
$sql = "SELECT community_id FROM communities WHERE community_id = " . mysqli_real_escape_string($link, $_GET['id']);
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result)==0){
http_response_code(404);
echo '{"success":0}';
} else {
$testYeah = "SELECT favorite_id FROM favorites WHERE favorite_to = " . mysqli_real_escape_string($link, $_GET['id']) . " AND favorite_by = " . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $testYeah);
if(mysqli_num_rows($result)==0) {
echo '{"success":1}';
} else {
        $sql = "DELETE FROM favorites WHERE favorite_to = " . mysqli_real_escape_string($link, $_GET['id']) . " AND favorite_by = " . mysqli_real_escape_string($link, $_SESSION['user_pid']);
        $result = mysqli_query($link, $sql);
        if(mysqli_error($link)) {
            http_response_code(400);
            echo '{"success":0}';
        } else {
            echo '{"success":1}';
}}}}}