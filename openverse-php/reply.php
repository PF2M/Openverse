<?php
$title = 'Create Post';
require_once '../inc/connect.php';

if($_SERVER['REQUEST_METHOD'] != 'POST')
{
http_response_code(405);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>The page could not be found.</p></div>';
openFoot();
}
else
{
$id = mysqli_real_escape_string($link, $_GET['id']);
$body = mysqli_real_escape_string($link, $_POST['body']);
$feeling_id = mysqli_real_escape_string($link, $_POST['feeling_id']);
$url = mysqli_real_escape_string($link, $_POST['url']);
$is_spoiler = mysqli_real_escape_string($link, $_POST['is_spoiler']);
$user_pid = mysqli_real_escape_string($link, $_SESSION['user_pid']);

if(!$signed_in){
exit('You need to be signed in to do that.');
}

if(strlen($body)>1000 || strlen($body)==0){
http_response_code(400);
exit('Your body length is bad, fam.');
}

if($feeling_id<0 || $feeling_id>5){
if($_SESSION['user_rank']!=5){
http_response_code(400);
exit('Your feeling ID is bad, fam.');
}
}

if(strlen($url)>1024){
http_response_code(400);
exit('Your URL length is bad, fam.');
}

if($is_spoiler!='' && $is_spoiler!='0' && $is_spoiler!='1'){
http_response_code(400);
exit('Your spoiler is bad, fam.');
}

if(isset($_POST['screenshot']) && $_POST['screenshot']!=''){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Client-ID d165d32c3b3e8eb'));
curl_setopt($ch, CURLOPT_POSTFIELDS, array('image' => $_POST['screenshot']));
$response = curl_exec($ch);
if(curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200){
$image = mysqli_real_escape_string($link, str_replace('http://', 'https://', json_decode($response)->data->link));
curl_close($ch);
} else {
curl_close($ch);
http_response_code(400);
exit('{"success":0,"errors":[{"message":"An error occurred while trying to upload your image.","error_code":6661337}],"code":400}');
}} else {
$image = '';
}

$sql = 'SELECT * FROM posts WHERE post_id = ' . $id;
$result = mysqli_query($link, $sql);
if(mysqli_num_rows($result)==0){
http_response_code(400);
exit('That post does not exist.');
} else {
$row = mysqli_fetch_assoc($result);
}

$query  = "BEGIN WORK;";
        $result = mysqli_query($link, $query);
     
            //the form has been posted, so save it
            //insert the topic into the topics table first, then we'll save the post into the posts table
        $sql = "INSERT INTO 
                    replies(reply_content,
                            reply_screenshot,
                            reply_url,
                            reply_is_spoiler,
                            reply_feeling_id,
                            reply_date,
                            reply_to,
                            reply_by)
                VALUES ('" . $body . "',
                        '" . $image . "',
                        '" . $url . "',
                        '" . $is_spoiler . "',
                        '" . $feeling_id . "',
                        NOW(),
                        '" . $id . "',
                        " . $user_pid . ")";
        $result = mysqli_query($link, $sql);
            if(mysqli_error($link))
            {
                //something went wrong, display the error
                echo 'An error occurred while inserting your data. Please try again later.';
                mysqli_query($link, 'ROLLBACK;');
            } 
            else {
                    $result = mysqli_query($link, 'COMMIT;');
                    if(mysqli_error($link)){
                    http_response_code(400);
                    echo 'A database error occurred while submitting that request.';
                    } else {
# Successful post, search for it then respond with it.
$sql_postcreatedfetch = 'SELECT * FROM replies LEFT JOIN users ON replies.reply_by = users.user_pid WHERE reply_by = "' . $user_pid . '" AND reply_to = "' . $id . '" ORDER BY reply_date DESC LIMIT 1';
$result_postcreatedfetch = mysqli_query($link, $sql_postcreatedfetch);
$row_postcreatedfetch = mysqli_fetch_assoc($result_postcreatedfetch);

// Actually, for replies we need to send a notification first, and we're also going to set a flag used on the next line.
$post_by = mysqli_real_escape_string($link, $row['post_by']);
if($post_by != $user_pid) {
mysqli_query($link,'INSERT INTO notifications(notif_to,notif_by,notif_topic,notif_type,notif_read,notif_date) VALUES ('.$post_by.','.$user_pid.','.$id.',2,0,NOW())');
} else {
$my = ' my';
$array = [$user_pid];
$rsql = 'SELECT reply_by FROM replies WHERE reply_to = ' . $id;
$rresult = mysqli_query($link, $rsql);
while($rrow = mysqli_fetch_assoc($rresult)){
$reply_by = mysqli_real_escape_string($link, $rrow['reply_by']);
if(!in_array($reply_by, $array)){
array_push($array, $reply_by);
mysqli_query($link,'DELETE FROM notifications WHERE notif_to = '.$reply_by.' AND notif_by = '.$user_pid.' AND notif_topic = '.$id.' AND notif_type = 3');
mysqli_query($link,'INSERT INTO notifications(notif_to,notif_by,notif_topic,notif_type,notif_read,notif_date) VALUES ('.$reply_by.','.$user_pid.','.$id.',3,0,NOW())');
}
}}

echo '<div class="post' . $my . ' trigger" data-href="/replies/' . htmlspecialchars($row_postcreatedfetch['reply_id']) . '"><a class="icon-container';
if($row_postcreatedfetch['user_rank'] == 1) {
echo ' donator';
}
if($row_postcreatedfetch['user_rank'] == 2) {
echo ' tester';
}
if($row_postcreatedfetch['user_rank'] == 3) {
echo ' moderator';
}
if($row_postcreatedfetch['user_rank'] == 4) {
echo ' administrator';
}
if($row_postcreatedfetch['user_rank'] == 5) {
echo ' developer';
}
echo '" href="/users/' . htmlspecialchars($row_postcreatedfetch['user_id']) . '"><img class="icon" src="';
$avatar = $row_postcreatedfetch['user_avatar'];
$feeling_id = $row_postcreatedfetch['reply_feeling_id'];
include 'avatar.php';
echo $avatar.'"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row_postcreatedfetch['user_id']) . '">' . htmlspecialchars($row_postcreatedfetch['user_name']) . '</a></p>';
echo '<p class="timestamp-container"> <a class="timestamp" href="/replies/' . htmlspecialchars($row_postcreatedfetch['reply_id']) . '">Less than a minute ago</a>';
if($row['reply_is_spoiler']==1) {
echo ' · <span class="spoiler">Spoilers</span>';
}
echo '</p><div class="body">';
if(strlen($row_postcreatedfetch['reply_content'])!=0){
echo '<p class="reply-content-text">'.htmlspecialchars($row_postcreatedfetch['reply_content']).'</p>';
}
if($row_postcreatedfetch['reply_screenshot']) {
echo '<a class="screenshot-container still-image" href="/replies/' . $row_postcreatedfetch['reply_id'] . '"><img src="' . htmlspecialchars($row_postcreatedfetch['reply_screenshot']) . '"></a>';
}

echo '<div class="reply-meta"><button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol"><span class="empathy-count">0</span></div></div></div></div>';
                    }
}}