<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
$pid_list = explode(',', $nrow['pid_list']);
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[0])));
$avatar = $urow['user_avatar'];
$feeling_id = false;
include 'avatar.php';
echo '<a class="icon-container" href="/users/' . htmlspecialchars($urow['user_id']) . '"><img class="icon" src="' . $avatar . '"></a><div class="body">';
if(isset($sports)) {
echo 'Followed by ';
}
if(count($pid_list) == 1) {
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>';
} elseif(count($pid_list) == 2) {
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a> and ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[1])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>';
} elseif(count($pid_list) == 3) {
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[1])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, and ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[2])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>';
} elseif(count($pid_list) == 4) {
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[1])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[2])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, and 1 other person';
} else {
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[1])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, ';
$urow = mysqli_fetch_assoc(mysqli_query($link, "SELECT * FROM users WHERE user_pid = " . mysqli_real_escape_string($link, $pid_list[2])));
echo '<a class="nick-name" href="/users/' . htmlspecialchars($urow['user_id']) . '">' . htmlspecialchars($urow['user_name']) . '</a>, and ' . bcsub(count($pid_list), 3) . ' others';
}