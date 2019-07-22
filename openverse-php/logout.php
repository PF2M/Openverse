<?php
require_once '../inc/connect.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
if($_SESSION['signed_in']==true){
mysqli_query($link, 'DELETE FROM login_tokens WHERE login_tokens.token_for = "'.$_SESSION['user_pid'].'"');
setcookie('openverse-auth', null, (time() - strtotime('1 minute')), '/');
$_SESSION['signed_in'] = false;
$_SESSION['user_id'] = '';
$_SESSION['user_pid'] = '';
$_SESSION['user_name'] = '';
$_SESSION['user_rank'] = '';
$_SESSION['user_avatar'] = '';
}
if(isset($_GET['callback']) && strlen($_GET['callback']) > 0){
header('Location: /' . $_GET['callback']);
} else {
header('Location: /');
}
} else {
http_response_code(405);
$title = '405 Method Not Allowed';
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You cannot access this page with a GET request. Use a POST request or press the Log Out button instead.</p></div>';
openFoot();
}