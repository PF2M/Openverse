<?php
require_once '../inc/connect.php';
header('Content-Type: application/json');
if($signed_in == true){
$notifs = 0;
$result = mysqli_query($link,'SELECT GROUP_CONCAT(DISTINCT notif_read) AS notifs_read FROM notifications WHERE notif_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' GROUP BY notif_topic, notif_type ORDER BY notif_id DESC LIMIT 0,50');
while($row = mysqli_fetch_assoc($result)) {
if(strpos($row['notifs_read'], '0') !== false){
    $notifs = $notifs + 1;
}}
echo '{"success":1,"news":{"unread_count":"' . $notifs . '"},"admin_message":{"unread_count":0},"mission":{"unread_count":0}}';
} else {
echo '{"success":1,"news":{"unread_count":"0"},"admin_message":{"unread_count":0},"mission":{"unread_count":0}}';
}