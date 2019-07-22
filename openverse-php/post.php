<?php
$title = 'Create Post';
require_once '../inc/connect.php';
if($_SERVER['REQUEST_METHOD'] != 'POST') {
http_response_code(405);
require_once '../inc/htm.php'; openHead();
echo '<div class="no-content"><p>The page could not be found.</p></div>';
openFoot();
} else {
$body = mysqli_real_escape_string($link, $_POST['body']);
$feeling_id = mysqli_real_escape_string($link, $_POST['feeling_id']);
$url = mysqli_real_escape_string($link, $_POST['url']);
$is_spoiler = mysqli_real_escape_string($link, $_POST['is_spoiler']);
$community_id = mysqli_real_escape_string($link, $_POST['community_id']);

if(!$signed_in){
http_response_code(401);
exit('{"success":0,"errors":[{"message":"You must be signed in to do that.","error_code":6661337}],"code":400}');
}

if(!empty($_POST['painting'])) {
require_once('image.php');
$drawing = Drawing($_POST['painting']);
if(!$drawing) { http_response_code(400); exit('{"success":0,"errors":[{"message":"Your drawing is invalid.","error_code":6661337}],"code":400}'); }
else { $drawing = imgurUpload(base64_encode($drawing)); if(!$drawing) { http_response_code(400); exit('{"success":0,"errors":[{"message":"An error occurred while trying to upload your drawing.","error_code":6661337}],"code":400}'); } }
} else {
if(mb_strlen($body)>1000 || mb_strlen($body)==0){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"Your post is too long.","error_code":6661337}],"code":400}');
    }
}

if($feeling_id<0 || $feeling_id>5){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"You have entered an invalid feeling ID.","error_code":6661337}],"code":400}');
}

if (isset($_POST['URL']) && filter_var($url, FILTER_VALIDATE_URL) == false) {
if($_SESSION['user_rank']<4){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"You have entered an invalid URL.","error_code":6661337}],"code":400}');
}
}

if(mb_strlen($url)>1024){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"Your URL is too long.","error_code":6661337}],"code":400}');
}

if($is_spoiler!='' && $is_spoiler!=0 && $is_spoiler!=1){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"Your spoiler value is invalid.","error_code":6661337}],"code":400}');
} else {
$is_spoiler = 0;
}

$result = mysqli_query($link, 'SELECT community_perms FROM communities WHERE community_id = ' . $community_id);
if(mysqli_num_rows($result)==0){
http_response_code(400);
exit('{"success":0,"errors":[{"message":"That community does not exist.","error_code":6661337}],"code":400}');
}

$row = mysqli_fetch_assoc($result);
if($row['community_perms'] > $_SESSION['user_rank']) {
http_response_code(400);
exit('{"success":0,"errors":[{"message":"You don\'t have permission to make a post to that community.","error_code":6661337}],"code":400}');
}

if(!empty($_POST['screenshot'])){
require_once('image.php');
$image = imgurUpload($_POST['screenshot']);
if(!$image) {
http_response_code(400);
exit('{"success":0,"errors":[{"message":"An error occurred while trying to upload your image.","error_code":6661337}],"code":400}');
}}

$result = mysqli_query($link, "SELECT post_id FROM posts ORDER BY post_id DESC LIMIT 1;");
$row = mysqli_fetch_assoc($result);

$query  = "BEGIN WORK;";
        $result = mysqli_query($link, $query);
            $sql = 'INSERT INTO 
                         posts(post_content,
                               post_feeling_id,
                               post_screenshot,
                               post_drawing,
                               post_url,
                               post_is_spoiler,
                               post_date,
                               post_community,
                               post_by,
                               post_edited)
                   VALUES("' . $body . '",
                          ' . $feeling_id . ',
                          "' . $image . '",
                          "' . (isset($drawing) ? $drawing : '') . '",
                          "' . $url . '",
                          ' . $is_spoiler . ',
                               NOW(),
                          ' . $community_id . ',
                          ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ',
                               NOW())';
            $result = mysqli_query($link, $sql);
            if(!$result)
            {
                //something went wrong, display the error
                http_response_code(500);
                echo 'An error occurred while inserting your data. Please try again later. ' . mysqli_errno($link);
                $sql = "ROLLBACK;";
                $result = mysqli_query($link, $sql);
            } 
            else {
                    $sql = "COMMIT;";
                    $result = mysqli_query($link, $sql);
                    if(!$result){
                    echo '<script>window.location.replace("/communities/' . htmlspecialchars($_POST['community_id']) . '#err' . mysqli_errno($link) . '");</script>';
                    } else {
# Successful post, search for it then respond with it.
$sql_postcreatedfetch = 'SELECT * FROM posts LEFT JOIN users ON user_pid = post_by WHERE post_by = "'.$_SESSION['user_pid'].'" AND post_community = "' . mysqli_real_escape_string($link, $_POST['community_id']) . '" ORDER BY post_id DESC LIMIT 0,1';
$result_postcreatedfetch = mysqli_query($link, $sql_postcreatedfetch);
$row_postcreatedfetch = mysqli_fetch_assoc($result_postcreatedfetch);

echo '<div class="post trigger" data-href="/posts/' . htmlspecialchars($row_postcreatedfetch['post_id']) . '"><a class="icon-container';
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
$feeling_id = $row_postcreatedfetch['post_feeling_id'];
include 'avatar.php';
echo $avatar . '"></a><p class="user-name"><a href="/users/' . htmlspecialchars($row_postcreatedfetch['user_id']) . '">' . htmlspecialchars($row_postcreatedfetch['user_name']) . '</a></p>';
echo '<p class="timestamp-container"> <a class="timestamp" href="/posts/' . htmlspecialchars($row_postcreatedfetch['post_id']) . '">' . displayTime($row_postcreatedfetch['post_date']) . '</a>';
if($row_postcreatedfetch['post_is_spoiler']==1) {
echo ' 路 <span class="spoiler">Spoilers</span>';
}
echo '</p><div class="body post-content">';
if (preg_match('/(http:\/\/|https:\/\/)?(www\.)?((youtube\.com\/watch\?v=)|(youtu\.be\/))([A-Za-z0-9-_]{11}).*/i', $row_postcreatedfetch['post_url'], $res)) {
echo '<a href="/posts/'.$row_postcreatedfetch['post_id'].'" class="screenshot-container video"><img height="48" src="https://i.ytimg.com/vi/'.$res[6].'/default.jpg"></a>';
}
if($row_postcreatedfetch['post_screenshot']) {
echo '<a class="screenshot-container still-image" href="/posts/' . $row_postcreatedfetch['post_id'] . '"><img src="' . htmlspecialchars($row_postcreatedfetch['post_screenshot']) . '"></a>';
}
if(!empty($row_postcreatedfetch['post_drawing'])) {
echo '<p class="post-content-memo"><img src="' . htmlspecialchars($row_postcreatedfetch['post_drawing']) . '" class="post-memo"></p>';
}
else {
echo '<p class="post-content-text">';
if(mb_strlen($row_postcreatedfetch['post_content'])<204) {
echo parsePost($row_postcreatedfetch['post_content']);
} else {
echo parsePost(mb_substr($row_postcreatedfetch['post_content'], 0, 200) . '...');
}
echo '</p>';
}
echo '<div class="post-meta"><button type="button" class="symbol submit empathy-button disabled" disabled><span class="empathy-button-text">Yeah!</span></button><div class="empathy symbol"><span class="empathy-count">0</span></div><div class="reply symbol"><span class="reply-count">0</span></div></div></div></div>';



                    }
}}