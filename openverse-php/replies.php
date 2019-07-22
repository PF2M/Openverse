<?php
require_once '../inc/connect.php';
        $sql = "SELECT * FROM replies LEFT JOIN posts ON replies.reply_to = posts.post_id LEFT JOIN users ON replies.reply_by = users.user_pid LEFT JOIN communities ON posts.post_community = communities.community_id WHERE replies.reply_id = " . mysqli_real_escape_string($link, $_GET['id']);
        $resultA = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($resultA);
if(mysqli_num_rows($resultA) != 0){
if($signed_in) {
if($_SESSION["user_pid"] == $row["reply_by"]) {
$title = "Your Comment";
} else {
$title = $row["user_name"] . "'s Comment";
}}} else {
$title = 'Not Found';
}
require_once '../inc/htm.php'; openHead();
if(mysqli_error($link))
{
    http_response_code(400);
    echo '<div class="no-content"><p>An error occurred while grabbing that reply.</p></div>';
}
else
{
    if(mysqli_num_rows($resultA)==0)
    {
        http_response_code(404);
        echo '<div class="no-content"><p>The reply could not be found.</p></div>';
    } elseif ($row['reply_status'] == 1) {
        http_response_code(404);
        echo '<div class="no-content"><p>Deleted by the author of the comment.</p></div>';
    } elseif ($row['reply_status'] == 2) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by poster.<br>Reply ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['reply_status'] == 3) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by moderator.<br>Reply ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['reply_status'] == 4) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by administrator.<br>Reply ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['reply_status'] == 5) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by developer.<br>Reply ID: #' . htmlspecialchars($_GET['id']) . '</p></div>';
    } elseif ($row['post_status'] == 2) {
        http_response_code(404);
        echo '<div class="no-content"><p>Deleted by poster.</p></div>';
    } elseif ($row['post_status'] == 3) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by moderator.<br>Post ID: #' . htmlspecialchars($row['post_id']) . '</p></div>';
    } elseif ($row['post_status'] == 4) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by administrator.<br>Post ID: #' . htmlspecialchars($row['post_id']) . '</p></div>';
    } elseif ($row['post_status'] == 5) {
        http_response_code(403);
        echo '<div class="no-content"><p>Deleted by developer.<br>Post ID: #' . htmlspecialchars($row['post_id']) . '</p></div>';
    } else {
    $postResult = mysqli_query($link, "SELECT * FROM posts LEFT JOIN replies ON replies.reply_to = posts.post_id LEFT JOIN users ON users.user_pid = posts.post_by WHERE replies.reply_id = ".mysqli_real_escape_string($link, $_GET['id']));
    if(mysqli_error($link)) {
    http_response_code(400);
    echo '<div class="no-content"><p>An error occurred while grabbing that reply.</p></div>';
    } elseif (mysqli_num_rows($postResult)==0) {
    echo '<div class="no-content"><p>The post this reply was made to was deleted.</p></div>';
    } else {
    $prow = mysqli_fetch_assoc($postResult);
$avatar = $prow['user_avatar'];
$feeling_id = $prow['post_feeling_id'];
include 'avatar.php';
    echo '<div class="main-column"><div class="post-list-outline"><a class="post-permalink-button" href="/posts/' . $prow['post_id'] . '"><span class="icon-container"><img class="icon" src="' . $avatar . '"></span><span>View <span class="post-user-description">' . htmlspecialchars($prow['user_name']) . '\'s post (';
    if(!empty($prow['post_drawing'])) { echo 'handwritten'; } else {
    if (strlen($prow['post_content'])>18){
    echo htmlspecialchars(substr($prow['post_content'], 0, 15)) . '...';
    } else {
    echo htmlspecialchars($prow['post_content']);
        }
    }
    echo '</span>) for this comment.</span></a></div>';
    echo '<div class="post-list-outline"><div id="post-content" class="post reply-permalink-post"><p class="community-container"><a href="/titles/1/' . htmlspecialchars($prow['post_community']) . '"><img class="community-icon" src="' . htmlspecialchars($row['community_icon']) . '">' . htmlspecialchars($row['community_name']) . '</a></p>';
if($row['reply_by']==$_SESSION['user_pid'] || $prow['post_by']==$_SESSION['user_pid'] || $_SESSION['user_rank']>2){
echo '<button type="button" class="symbol button edit-button edit-reply-button" data-modal-open="#edit-post-page"><span class="symbol-label">Edit</span></button>';
}
if($row['reply_by']!=$_SESSION['user_pid']) {
echo '<div class="report-buttons-content"><button type="button" class="report-button" data-modal-open="#report-violation-page" data-screen-name="'.$row['user_name'].'" data-support-text="#'.htmlspecialchars($row['reply_id']).'" data-action="/replies/'.htmlspecialchars($row['reply_id']).'/violations" data-is-permalink="1" data-can-report-spoiler="1" data-url-id="'.htmlspecialchars($row['reply_id']).'" data-track-action="openReportModal">Report Violation</button></div>';
}
$avatar = $row['user_avatar'];
$feeling_id = $row['reply_feeling_id'];
include 'avatar.php';
$feeling_id = $row['reply_feeling_id'];
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
echo 'Developer'; //please take me away from here
}
echo '</p>';
}
echo '<p class="user-name"><a href="/users/' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['user_name']) . '</a> <span class="user-id">' . htmlspecialchars($row['user_id']) . '</span></p><p class="timestamp-container"><span class="timestamp">' . displayTime($row['reply_date']) . '</span>';
if($row['reply_is_spoiler']==1) {
echo '<span class="spoiler"> · Spoilers</span>';
}
echo '</p></div></div><div class="body">';
if($row['reply_screenshot']) {
echo '<div class="screenshot-container still-image"><img src="';
if($_COOKIE['proxy']=='1'){
echo 'https://pf2m.000webhostapp.com/mini.php?';
}
echo htmlspecialchars($row['reply_screenshot']) . '"></div>';
}
echo '<p class="post-content-text">' . htmlspecialchars($row['reply_content']) . '</p><div class="post-meta">';
if(!$signed_in||$row['reply_by']==$_SESSION['user_pid']) {
echo '<button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah??';
} elseif($feeling_id == 3){
echo 'Yeah!?';
} elseif($feeling_id == 4 || $feeling_id == 5){
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
echo '" data-action="/replies/' . htmlspecialchars($row['reply_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['reply_id']) . '"><span class="empathy-button-text">Unyeah</span></button><div class="empathy symbol">';
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
echo '" data-action="/replies/' . htmlspecialchars($row['reply_id']) . '/empathies" data-url-id="' . htmlspecialchars($row['reply_id']) . '"><span class="empathy-button-text">';
if($feeling_id == 2){
echo 'Yeah??';
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
$realECount = mysqli_query($link, 'SELECT * FROM reply_yeahs WHERE ryeah_reply = '.$_GET['id']);
echo '<span class="empathy-count">' . mysqli_num_rows($realECount) . '</span></div></div></div></section>';
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
$feeling_id = $row['reply_feeling_id'];
include 'avatar.php';
echo $avatar.'"></a>';
$ysql = 'SELECT * FROM reply_yeahs LEFT JOIN users ON reply_yeahs.ryeah_by = users.user_pid WHERE ryeah_reply = ' . mysqli_real_escape_string($link, $_GET['id']) . ' AND ryeah_by != ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' ORDER BY ryeah_date DESC';
$yresult = mysqli_query($link, $ysql);
while($yrow = mysqli_fetch_assoc($yresult)) {
$avatar = $yrow['user_avatar'];
$feeling_id = $row['reply_feeling_id'];
include 'avatar.php';
echo '<a class="post-permalink-feeling-icon" href="/users/' . htmlspecialchars($yrow['user_id']) . '"><img class="user-icon" src="' . $avatar . '"></a>';
}
echo '</div>';
}
}}
echo '</div></div>';
echo '<div id="report-violation-page" class="dialog none" data-modal-types="report report-violation" data-is-template="1">
<div class="dialog-inner">
  <div class="window">
    <h1 class="window-title">Report Violation to Openverse Administrators</h1>
    <div class="window-body">
      <p class="description">
          You are about to report a reply with content which violates the Openverse Code of Conduct. This report will be sent to Openverse\'s administrators and not to the creator of the post.</p>
      <form method="post" action="/replies/' . htmlspecialchars($_GET['id']) . '/violations">
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
        <p class="post-id">Reply ID: #' . htmlspecialchars($_GET['id']) . '</p>
        <div class="form-buttons">
          <input type="button" class="olv-modal-close-button gray-button" value="Cancel">
          <input type="submit" class="post-button black-button" value="Submit Report" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="openReportModal">
        </div>
      </form>
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
        <select name="edit-type">
          <option value="" selected>Select an option.</option>
          <option value="spoiler" data-action="/replies/' . htmlspecialchars($_GET['id']) . '/set_spoiler" >Set as Spoiler</option>
          <option value="delete" data-action="/replies/' . htmlspecialchars($_GET['id']) . '/delete" data-url-id="' . htmlspecialchars($_GET['id']) . '" data-track-action="deleteReply">
            Delete
          </option>
        </select>
        <div class="form-buttons">
          <input type="button" class="olv-modal-close-button gray-button" value="Cancel">
          <input type="submit" class="post-button black-button" value="Submit"
        </div>
      </form>
    </div>
  </div>
</div>
</div>';
openFoot();