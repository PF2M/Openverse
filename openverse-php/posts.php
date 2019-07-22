<?php
require_once '../inc/connect.php';
        $sql = "SELECT * FROM posts LEFT JOIN users ON posts.post_by = users.user_pid LEFT JOIN communities ON posts.post_community = communities.community_id WHERE posts.post_id = " . mysqli_real_escape_string($link, $_GET['id']);
        $resultA = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($resultA);
if(mysqli_num_rows($resultA)!=0){
if($signed_in && $_SESSION["user_pid"] == $row["post_by"]) {
$title = "Your Post";
} else {
$title = htmlspecialchars($row["user_name"]) . "'s Post";
}} else {
$title = 'Not Found';
}
require_once '../inc/htm.php'; openHead();
if(!$resultA)
{
    echo '<div class="no-content"><p>The post could not be found.</p></div>';
}
else
{
    if(mysqli_num_rows($resultA)==0)
    {
        http_response_code(404);
        echo '<div class="no-content"><p>The post could not be found.</p></div>';
    } elseif ($row['post_status'] == 2) {
        http_response_code(404);
        echo '<div class="no-content"><p>Deleted by poster.</p></div>';
    } elseif ($row['post_status'] == 3) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by moderator.<br>Post ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['post_status'] == 4) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by administrator.<br>Post ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['post_status'] == 5) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by developer.<br>Post ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } else {
$avatar = $row['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
$post_feeling_id = $row['post_feeling_id'];
$post_is_spoiler = $row['post_is_spoiler'];
$post_by = $row['post_by'];
$post_content = $row['post_content'];
$screenshot = $row['post_screenshot'];
echo '<div class="main-column"><div class="post-list-outline"><section id="post-content" class="post"><header class="community-container"><meta http-equiv="Content-Type" content="text/html; charset=gb18030"><h1 class="community-container-heading"><a href="/titles/' . htmlspecialchars($row['community_title']) . '/' . htmlspecialchars($row['community_id']) . '"><img class="community-icon" src="' . htmlspecialchars($row['community_icon']) . '">' . htmlspecialchars($row['community_name']) . '</a></h1></header>';
if($row['post_by']==$_SESSION['user_pid'] || $_SESSION['user_rank']>2){
echo '<div class="edit-buttons-content"><button type="button" class="symbol button edit-button" data-modal-open="#edit-post-page"><span class="symbol-label">Edit</span></button></div>';
} else {
echo '<div class="report-buttons-content" style="float:right"><button type="button" class="report-button" data-modal-open="#report-violation-page" data-screen-name="' . htmlspecialchars($row['user_name']) . '" data-support-text="#' . htmlspecialchars($row['post_id']) . '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/violations" data-can-report-spoiler="1" data-track-action="openReportModal" data-track-category="reportViolation">Report Violation</button></div>';
}
echo '<div class="user-content"><a class="icon-container';
if($row['user_rank'] == 1) {
echo ' donator';
}
if($row['user_rank'] == 2) {
echo ' tester';
}
if($row['user_rank'] == 3) {
echo ' moderator';
}
if($row['user_rank'] == 4) {
echo ' administrator';
}
if($row['user_rank'] == 5) {
echo ' developer';
}
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="user-name-content">';
if(strlen($row['user_rank'])!=0 && $row['user_rank']!=0) {
echo '<p class="user-organization">';
if($row['user_rank'] == 1) {
echo 'Donator';
}
if($row['user_rank'] == 2) {
echo 'Tester';
}
if($row['user_rank'] == 3) {
echo 'Moderator';
}
if($row['user_rank'] == 4) {
echo 'Administrator';
}
if($row['user_rank'] == 5) {
echo 'Developer';
}
echo '</p>';
}
echo '<p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a> <span class="user-id">' . htmlspecialchars($row['user_id']) . '</span></p><p class="timestamp-container"><span class="timestamp">' . displayTime($row['post_date']) . '</span>';
if($row['post_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
if(strtotime($row['post_edited']) - strtotime($row['post_date']) > 300) {
echo ' · <span class="spoiler">Edited</span>';
}
echo '</p></div></div><div class="body">';

if($row['post_screenshot']) {
echo '<div class="screenshot-container still-image"><img src="';
if($_COOKIE['proxy']=='1'){
echo 'https://pf2m.000webhostapp.com/mini.php?';
}
echo htmlspecialchars($row['post_screenshot']) . '"></div>';
}
if(!empty($row['post_drawing'])) {
echo '<p class="post-content-memo"><img src="' . htmlspecialchars($row['post_drawing']) . '" class="post-memo"></p>';
}
    else {
echo '<p class="post-content-text">';
if($row['post_html']==1) {
echo $post_content;
} else {
echo parsePost($post_content);
}
echo '</p>';
}
if($row['post_url']) {
if (preg_match('/(http:\/\/|https:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu\.be\/))([A-Za-z0-9-_]{11}).*/i', $row['post_url'], $res)) {
echo '<div class="screenshot-container video"><iframe class="youtube-player" type="text/html" width="490" height="276" src="https://www.youtube.com/embed/' . $res[6] . '?rel=0&amp;modestbranding=1&amp;iv_load_policy=3" frameborder="0"></iframe></div>';
} else if(mb_substr($row['post_url'],strlen($row['post_url'])-4,4)=='.mp3') {
echo '<br><center><audio controls><source src="'.htmlspecialchars($row['post_url']).'" type="audio/mpeg"></audio></center>';
} else if(mb_substr($row['post_url'],strlen($row['post_url'])-4,4)=='.wav') {
echo '<br><center><audio controls><source src="'.htmlspecialchars($row['post_url']).'" type="audio/wav"></audio></center>';
} else if(mb_substr($row['post_url'],strlen($row['post_url'])-4,4)=='.ogg') {
echo '<br><center><audio controls><source src="'.htmlspecialchars($row['post_url']).'" type="audio/ogg"></audio></center>';
} else if(mb_substr($row['post_url'],strlen($row['post_url'])-4,4)=='.nes'){
echo '<br><center><link rel="import" href="/assets/x-nes.html"><x-nes src="'.htmlspecialchars($row['post_url']).'" poster="/assets/img/nes.png" preload="auto"></center>';
} else {
echo '<p class="url-link"><a target="_blank" href="' . htmlspecialchars($row['post_url']) . '">' . htmlspecialchars($row['post_url']) . '</a></p>';
}
}
echo '<div class="post-meta">';
if($signed_in){
$ysql = 'SELECT * FROM post_yeahs LEFT JOIN users ON post_yeahs.yeah_by = users.user_pid WHERE yeah_post = ' . $_GET['id'] . ' AND yeah_by != ' . $_SESSION['user_pid'] . ' ORDER BY yeah_date DESC';
} else {
$ysql = 'SELECT * FROM post_yeahs LEFT JOIN users ON post_yeahs.yeah_by = users.user_pid WHERE yeah_post = ' . $_GET['id'] . ' ORDER BY yeah_date DESC';
}
$resultB = mysqli_query($link, $ysql);
$sql = 'SELECT * FROM replies LEFT JOIN users ON users.user_pid = replies.reply_by WHERE reply_to = ' . mysqli_real_escape_string($link, $_GET['id']) . ' AND reply_status = 0';
$resultC = mysqli_query($link, $sql);
$replyCount = mysqli_num_rows(mysqli_query($link,'SELECT reply_id FROM replies WHERE reply_to = '.mysqli_real_escape_string($link,$_GET['id'])));
if(!$signed_in || $row['post_by']==$_SESSION['user_pid']) {
echo '<button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah♥';
} elseif($feeling_id == 3){
echo 'Yeah!?';
} elseif($feeling_id == 4 || $feeling_id == 5){
echo 'Yeah...';
} else {
echo 'Yeah!';
}
echo '</span></button><div class="empathy symbol">';
} else {
$testYeah = "SELECT * FROM post_yeahs WHERE post_yeahs.yeah_post = " . mysqli_real_escape_string($link, $row['post_id']) . " AND post_yeahs.yeah_by = " . $_SESSION['user_pid'];
$results = mysqli_query($link, $testYeah);
if($signed_in && mysqli_num_rows($results) > 0) {
echo '<button type="button" class="symbol submit empathy-button empathy-added';
if(!$signed_in || $post_by_me || $post_blocked){
echo ' disabled" disabled';
} else {
echo '"';
}
echo ' data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
} else {
echo '<button type="button" class="symbol submit empathy-button';
if(!$signed_in || $post_by_me || $post_blocked){
echo ' disabled" disabled';
} else {
echo '"';
}
echo ' data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/posts/' . htmlspecialchars($row['post_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['post_id']) . '"><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah♥';
} elseif($feeling_id == 3){
echo 'Yeah!?';
} elseif($feeling_id == 4 || $feeling_id == 5){
echo 'Yeah...';
} else {
echo 'Yeah!';
}
echo '</span></button><div class="empathy symbol">';
}}if($signed_in && mysqli_num_rows(mysqli_query($link, 'SELECT * FROM blocks WHERE block_to = ' . mysqli_real_escape_string($link, $row['user_pid']) . ' AND block_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']))) > 0) {
$user_blocked = true;
} else {
$user_blocked = false;
}
if($signed_in && mysqli_num_rows(mysqli_query($link, 'SELECT * FROM blocks WHERE block_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' AND block_by = ' . mysqli_real_escape_string($link, $row['user_pid']))) > 0) {
$post_blocked = true;
} else {
$post_blocked = false;
}
if($signed_in && $row['post_by'] == $_SESSION['user_pid']) {
$post_by_me = true;
} else {
$post_by_me = false;
}
$realECount = mysqli_query($link, 'SELECT * FROM post_yeahs WHERE yeah_post = '.$_GET['id']);
echo '<span class="symbol-label">Yeahs</span><span class="empathy-count">' . mysqli_num_rows($realECount) . '</span></div><div class="reply symbol"><span class="symbol-label">Replies</span><span class="reply-count">' . $replyCount . '</span></div></div></div></section>';
echo '<div id="empathy-content"';
if(mysqli_num_rows($realECount) == 0) {
echo ' class="none"';
}
echo '><a class="post-permalink-feeling-icon visitor"';
if(!$signed_in || mysqli_num_rows($results)==0){
echo ' style="display: none;"';
}
echo ' href="/users/' . htmlspecialchars($_SESSION['user_id']) . '"><img class="user-icon" src="';
$avatar = $_SESSION['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo $avatar.'"></a>';
while($yrow = mysqli_fetch_assoc($resultB)) {
$avatar = $yrow['user_avatar'];
$feeling_id = $row['post_feeling_id'];
include 'avatar.php';
echo '<a class="post-permalink-feeling-icon" href="/users/' . htmlspecialchars($yrow['user_id']) . '"><img class="user-icon" src="' . $avatar . '"></a>';
}
echo '</div><br><h2 class="reply-label">Comments</h2><div id="reply-content">';
if(mysqli_num_rows($resultC) == 0) {
               echo '<div class="no-reply-content"><p>This post has no comments.</p></div>';
            } else {
echo '<ul class="list reply-list js-post-list">';
while($row = mysqli_fetch_assoc($resultC)) {
$avatar = $row['user_avatar'];
$feeling_id = $row['reply_feeling_id'];
include 'avatar.php';
$feeling_id = $row['reply_feeling_id'];
if($row['reply_by']==$post_by) {
if($row['reply_is_spoiler'] == 1 && $row['reply_by'] != $_SESSION['user_pid']) {
echo '<li class="post my hidden trigger" data-href-hidden="/replies/' . $row['reply_id'] . '">';
} else {
echo '<li class="post my trigger" data-href="/replies/' . $row['reply_id'] . '">';
}} else {
if($row['reply_is_spoiler'] == 1 && $row['reply_by'] != $_SESSION['user_pid']) {
echo '<li class="post other hidden trigger" data-href-hidden="/replies/' . $row['reply_id'] . '">';
} else {
echo '<li class="post other trigger" data-href="/replies/' . $row['reply_id'] . '">';
}}
echo '<a class="icon-container';
if($row['user_rank'] == 1) {
echo ' donator';
}
if($row['user_rank'] == 2) {
echo ' tester';
}
if($row['user_rank'] == 3) {
echo ' moderator';
}
if($row['user_rank'] == 4) {
echo ' administrator';
}
if($row['user_rank'] == 5) {
echo ' developer';
}
echo '" href="/users/' . htmlspecialchars($row['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body"><div class="header"><p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a></p><p class="timestamp-container"> <a class="timestamp" href="/replies/' . $row['reply_id'] . '">' . displayTime($row['reply_date']) . '</a>';
if($row['reply_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
echo '</p></div>';
if(strlen($row['reply_content'])!=0){
echo '<p class="reply-content-text">' . parsePost($row['reply_content']) . '</p>';
}
if($row['reply_screenshot']) {
echo '<div class="screenshot-container still-image"><img src="';
if($_COOKIE['proxy']=='1'){
echo 'https://pf2m.000webhostapp.com/mini.php?';
}
echo htmlspecialchars($row['reply_screenshot']) . '"></div>';
}
if($row['reply_is_spoiler'] == 1 && $row['reply_by'] != $_SESSION['user_pid']) {
echo '<div class="hidden-content"><p>This comment contains spoilers.</p><button type="button" class="hidden-content-button">View Comment</button></div>';
}
echo '<div class="reply-meta">';
if(!$signed_in||$row['reply_by'] == $_SESSION['user_pid']) {
echo '<button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">';
if($row['reply_feeling_id'] == 2){
echo 'Yeah♥';
} elseif($row['reply_feeling_id'] == 3){
echo 'Yeah!?';
} elseif($row['reply_feeling_id'] == 4 || $row['reply_feeling_id'] == 5){
echo 'Yeah...';
} else {
echo 'Yeah!';
}
echo '</span></button><div class="empathy symbol">';
} else {
$testYeah = "SELECT * FROM reply_yeahs WHERE ryeah_reply = " . mysqli_real_escape_string($link, $row['reply_id']) . " AND ryeah_by = " . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$results = mysqli_query($link, $testYeah);
if(mysqli_num_rows($results)!=0) {
echo '<button type="submit" class="symbol submit empathy-button empathy-added" data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/replies/' . htmlspecialchars($row['reply_id']) . '/empathies" data-is-in-reply-list="1" data-url-id="' . htmlspecialchars($row['reply_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
} else {
echo '<button type="submit" class="symbol submit empathy-button" data-feeling="';
if($feeling_id == 1){
echo 'happy';
} elseif($feeling_id == 2){
echo 'like';
} elseif($feeling_id == 3){
echo 'surprised';
} elseif($feeling_id == 4){
echo 'frustrated';
} elseif($feeling_id == 5){
echo 'puzzled';
} else {
echo 'normal';
}
echo '" data-action="/replies/' . htmlspecialchars($row['reply_id']) . '/empathies" data-is-in-reply-list="1" data-url-id="' . htmlspecialchars($row['reply_id']) . '"><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah♥';
} elseif($feeling_id == 3){
echo 'Yeah!?';
} elseif($feeling_id == 4 || $feeling_id == 5){
echo 'Yeah...';
} else {
echo 'Yeah!';
}
echo '</span></button><div class="empathy symbol">';
}
}
$ysql = 'SELECT * FROM reply_yeahs WHERE ryeah_reply = ' . mysqli_real_escape_string($link, $row['reply_id']);
$result = mysqli_query($link, $ysql);
echo '<span class="empathy-count">' . mysqli_num_rows($result) . '</span></div></div></div></li>';
}
}
echo '</ul></div><h2 class="reply-label">Add a Comment</h2>';
                if($signed_in) { //s
echo '<form id="reply-form" method="post" action="/posts/' . htmlspecialchars($_GET['id']) . '/replies">
    <center><div class="feeling-selector"><label class="symbol feeling-button feeling-button-normal checked"><input type="radio" name="feeling_id" value="0" checked=""><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy"><input type="radio" name="feeling_id" value="1"><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like"><input type="radio" name="feeling_id" value="2"><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised"><input type="radio" name="feeling_id" value="3"><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated"><input type="radio" name="feeling_id" value="4"><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled"><input type="radio" name="feeling_id" value="5"><span class="symbol-label">puzzled</span></label></div></center><div class="textarea-container">
    <textarea name="body" class="textarea-text textarea" maxlength="1000" placeholder="Add a reply to this post." data-open-folded-form data-required></textarea></div>
<label class="file-button-container">
      <span class="input-label">Image<span> Will be uploaded to Imgur.</span></span>
      <input type="file" class="file-button" id="upload" accept="image/*">
      <input type="hidden" name="screenshot" id="screenshot">
    </label>
  <div class="post-form-footer-options">
      <label class="spoiler-button symbol">
          <input type="checkbox" id="is_spoiler" name="is_spoiler" value="1">
          Spoilers
        </label>
  </div>
  <div class="form-buttons"><input type="submit" class="black-button post-button" value="Send" data-track-action="sendReply"></div>
</form>';
} else {
echo '<div class="guest-message"><p>You must sign in to post a comment.<br><br>Sign in using an Openverse account to make posts and comments, as well as give Yeahs and follow users.</p><a href="/signup" class="arrow-button"><span>Create an Account</span></a><a href="/guide/about" class="arrow-button"><span>Learn More</span></a></div>';
}
echo '<div id="report-violation-page" class="dialog none" data-modal-types="report report-violation" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Report Violation to Openverse Administrators</h1>
    <div class="window-body">
      <p class="description">
          You are about to report a post with content which violates the Openverse Code of Conduct. This report will be sent to Openverse\'s administrators and not to the creator of the post.</p>
      <form method="post" action="/posts/' . htmlspecialchars($_GET['id']) . '/violations">
        <p class="select-button-label">Violation Type: </p>
        <select name="type" class="cannot-report-spoiler">
          <option value="" selected>Please make a selection.</option>
          <option value="1">Personal Information</option>
          <option value="2">Violent Content</option>
          <option value="3">Inappropriate/Harmful</option>
          <option value="4">Hateful/Bullying</option>
          <option value="6">Advertising</option>
          <option value="5">Sexually Explicit</option>
          <option value="7">Other</option>
        </select>
        <select name="type" class="can-report-spoiler">
          <option value="" selected>Please make a selection.</option>
          <option value="spoiler" data-body-required="1" data-track-action="Spoiler">Spoiler</option>
          <option value="1" data-track-action="Personal">Personal Information</option>
          <option value="2" data-track-action="Violent">Violent Content</option>
          <option value="3" data-track-action="Inappropriate">Inappropriate/Harmful</option>
          <option value="4" data-track-action="Hateful">Hateful/Bullying</option>
          <option value="6" data-track-action="Advertising">Advertising</option>
          <option value="5" data-track-action="Sexual">Sexually Explicit</option>
          <option value="7" data-track-action="Other">Other</option>
        </select>
        <textarea name="body" class="textarea" maxlength="100" data-placeholder="Enter a reason for the report."></textarea>
        <p class="post-id">Post ID: #' . htmlspecialchars($_GET['id']) . '</p>
        <div class="form-buttons">
          <input type="button" class="olv-modal-close-button gray-button" value="Cancel">
          <input type="submit" class="post-button black-button" value="Submit Report" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="openReportModal">
        </div>
      </form>
    </div>
  </div>
</div>
</div>
<div id="disabled-report-violation-notice" class="dialog none">
  <div class="dialog-inner">
    <div class="window">
      <h1 class="window-title">Report Violation to Openverse Administrators</h1>
      <div class="window-body">
        <div class="window-body-inner">
          <p>You cannot report posts made automatically by a software title.</p>
          <div class="form-buttons">
            <input class="olv-modal-close-button gray-button" type="button" value="Close">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div id="edit-post-page"
     class="dialog none"
     data-modal-types="edit-post">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Edit Post</h1>
    <div class="window-body">
      <form method="post" class="edit-post-form" action="">
        <p class="select-button-label">Select an action:</p>
        <select name="edit-type" onchange="setTimeout(setButton,1)">
          <option selected>Select an option.</option>';
if($post_by==$_SESSION['user_pid'] && strlen($screenshot)>0){
echo '<option value="screenshot-profile-post" data-action="/posts/' . htmlspecialchars($_GET['id']) . '/favorite_screenshot">Set Screenshot as Favorite Post</option>';
}
if($post_by==$_SESSION['user_pid']){
echo '<option value="edit">Edit Post</option>
<option value="delete" data-action="/posts/' . htmlspecialchars($_GET['id']) . '/delete" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="deletePost">Delete</option>';
}
if($_SESSION['user_rank'] > 2) {
    echo '<option value="spoiler" data-action="/posts/' . htmlspecialchars($_GET['id']) . '/set_spoiler" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="removePost">Set as Spoiler</option><option value="remove" data-action="/posts/' . htmlspecialchars($_GET['id']) . '/remove" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="removePost">Remove</option>';
}
        echo '</select>
        <div class="form-buttons">
          <input type="button" class="olv-modal-close-button gray-button" value="Cancel">
          <input type="submit" class="post-button black-button" value="Submit">
        </div>
      </form>
    </div>
  </div>
</div>
</div>';
echo '<script>
function setButton(){
var button = $(\'.edit-post-form .post-button\');
if($(\'select[name="edit-type"] option\').filter(":selected").val() == \'edit\') {
button.removeAttr(\'disabled\');
button.removeClass(\'disabled\');
button.attr(\'onclick\', \'editPost()\');
} else {
button.removeAttr(\'onclick\');
}}
function editPost(){
$(\'.body\').eq(0).replaceWith(\'<div class="body"><form id="edit-form" action="/posts/' . htmlspecialchars($_GET['id']) . '/edit" method="post"><div class="feeling-selector"><label class="symbol feeling-button feeling-button-normal';
if($post_feeling_id==0 || !isset($post_feeling_id)){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="0"';
if($post_feeling_id==0 || !isset($post_feeling_id)){
echo ' checked';
}
echo '><span class="symbol-label">normal</span></label><label class="symbol feeling-button feeling-button-happy';
if($post_feeling_id==1){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="1"';
if($post_feeling_id==1){
echo ' checked';
}
echo '><span class="symbol-label">happy</span></label><label class="symbol feeling-button feeling-button-like';
if($post_feeling_id==2){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="2"';
if($post_feeling_id==2){
echo ' checked';
}
echo '><span class="symbol-label">like</span></label><label class="symbol feeling-button feeling-button-surprised';
if($post_feeling_id==3){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="3"';
if($post_feeling_id==3){
echo ' checked';
}
echo '><span class="symbol-label">surprised</span></label><label class="symbol feeling-button feeling-button-frustrated';
if($post_feeling_id==4){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="4"';
if($post_feeling_id==4){
echo ' checked';
}
echo '><span class="symbol-label">frustrated</span></label><label class="symbol feeling-button feeling-button-puzzled';
if($post_feeling_id==5){
echo ' checked';
}
echo '"><input type="radio" name="feeling_id" value="5"';
if($post_feeling_id==5){
echo ' checked';
}
echo '><span class="symbol-label">puzzled</span></label></div><div class="textarea-container"><textarea name="body" class="textarea-text textarea" maxlength="1000" placeholder="Edit the text of your post." onkeyup="if($(&quot;#edit-form .textarea-text&quot;).val().length==0){$(&quot;#edit-form .post-button&quot;).attr(&quot;disabled&quot;,&quot;&quot;);$(&quot;#edit-form .post-button&quot;).addClass(&quot;disabled&quot;);}else{$(&quot;#edit-form .post-button&quot;).removeAttr(&quot;disabled&quot;);$(&quot;#edit-form .post-button&quot;).removeClass(&quot;disabled&quot;);}">' . str_replace('\'','&apos;',htmlspecialchars($post_content)) . '</textarea></div><div class="post-form-footer-options"><label class="spoiler-button symbol';
if($post_is_spoiler==1){
echo ' checked';
}
echo '"><input type="checkbox" id="is_spoiler" name="is_spoiler" value="1"';
if($post_is_spoiler==1){
echo ' checked';
}
echo '>Spoilers</label></div><div class="form-buttons"><input type="submit" class="post-button black-button" value="Edit Post"></div></form></div>\');
}
</script>';
}}
echo '</div>';
openFoot();