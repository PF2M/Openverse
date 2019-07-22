<?php
$title = 'Account Settings';
require_once '../inc/connect.php';
$sql = 'SELECT * FROM users WHERE users.user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>There was an error while searching for that user.<br>Error: ' . mysqli_error($link) . '</p></div>';
} else {
$row = mysqli_fetch_assoc($result);
        if(mysqli_num_rows($result)==0) {
            http_response_code(404);
            echo '<div class="no-content"><p>That user could not be found.</p></div>';
        }
        else
        {
            if($_SERVER['REQUEST_METHOD']=='GET'){
require_once '../inc/htm.php'; openHead();
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Openverse Settings</h2>
<form class="setting-form" method="post">

<ul class="settings-list">
      <li>
        <p class="settings-label"><label for="relationship_visibility">Who should be able to see your follower and following lists?</label></p>
        <div class="select-content">
          <div class="select-button">
            <select name="relationship_visibility" id="relationship_visibility">
              <option value="0">Everyone</option>
              <option value="1"';
if($row['user_relationship_visibility']==1){
echo ' selected';
}
echo '>Users I Follow</option>
              <option value="2"';
if($row['user_relationship_visibility']==2){
echo ' selected';
}
echo '>Just Me</option>
            </select>
          </div>
        </div>
      </li>
<div class="form-buttons"><input type="submit" class="black-button apply-button" value="Save Settings"></div>
</form>
</div></div>';
openFoot();
} else {
$results = mysqli_query($link, 'UPDATE users SET user_relationship_visibility = ' . mysqli_real_escape_string($link, $_POST['relationship_visibility']) . ' WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']));
if(mysqli_error($link)){
http_response_code(400);
echo 'failure';
} else {
echo 'success';
}}}}