<?php
$title = 'Reports';
require_once '../../inc/connect.php';
require_once '../../inc/htm.php'; openHead();
if($signed_in){
if($_SESSION['user_rank']>2){
if($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo '<div class="post-list-outline center"><h2 class="label">Reports (unfinished)</h2>';
    $result = mysqli_query($link, 'SELECT * FROM post_reports LEFT JOIN reply_reports ON report_date = rreport_date ORDER BY report_date DESC');
    if(!mysqli_error($link)) {
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) {
                echo '<iframe src="/posts/' . $row['report_to'] . '" width="400" height="240"><br>';
            }
        } else {
            echo 'No reports at this time.';
        }
    } else {
        echo mysqli_error($link);
    }
    echo '</div>';
} else {
    echo 'nya';
}
} else {
http_response_code(403);
echo '<div class="no-content"><p>You\'re not authorized to view this page.</p></div>';
}
} else {
http_response_code(401);
echo '<div class="no-content"><p>You must be signed in to access this page.</p></div>';
}
openFoot();