<?php
require_once '../inc/connect.php';
if($signed_in == true) {
header('Location: /users/' . $_SESSION['user_id']);
} else {
http_response_code(403);
$title = 'User Page';
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
openFoot();
}