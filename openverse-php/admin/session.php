<?php
$title = 'Session Stealer';
require_once '../../inc/connect.php';
/*$valid_passwords = array("admin" => "a");
$valid_users = array_keys($valid_passwords);
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];
$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);*/
$validated = $signed_in && $_SESSION['user_rank'] > 3;
if($validated){
if(isset($_GET['id'])){
$result = mysqli_query($link, 'SELECT * FROM users WHERE user_id = "' . mysqli_real_escape_string($link, $_GET['id']) . '"');
mysqli_store_result($link);
if(mysqli_error($link)){
http_response_code(500);
require_once '../../inc/htm.php'; openHead();
echo '<div class="no-content"><p>An error occurred while trying to access that page.</p></div>';
} elseif(mysqli_num_rows($result)==0){
http_response_code(404);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>That user could not be found.</p></div>';
} else {
$row = mysqli_fetch_assoc($result);
session_name('openverse');
session_start();
$_SESSION['signed_in'] = true;
$_SESSION['user_id'] = $row['user_id'];
$_SESSION['user_pid'] = $row['user_pid'];
$_SESSION['user_name'] = $row['user_name'];
$_SESSION['user_rank'] = $row['user_rank'];
$_SESSION['user_avatar'] = $row['user_avatar'];
$_SESSION['user_timezone'] = 'America/New_York';
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>Successfully logged into ' . htmlspecialchars($row['user_name']) . '\'s account!</p></div>';
}} else {
http_response_code(400);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You must specify a username.</p></div>';
}} else {
header('WWW-Authenticate: Basic realm="Openverse"');
http_response_code(401);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You\'re not authorized to view this page.</p></div>';
}
openFoot();