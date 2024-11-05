<?php
require_once '../inc/connect.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($signed_in) {
        if (isset($_POST['type']) && strlen($_POST['type']) == 1 && is_numeric($_POST['type'])) {
            $result = mysqli_query($link, "SELECT * FROM replies WHERE reply_id = " . mysqli_real_escape_string($link, $_GET['id']) . " LIMIT 0,1");
            if (mysqli_error($link) || mysqli_num_rows($result) == 0) {
                http_response_code(400);
                echo 'That reply does not exist.';
            } else {
                $row = mysqli_fetch_assoc($result);
                $sresult = mysqli_query($link, "SELECT * FROM reply_reports WHERE rreport_to = " . mysqli_real_escape_string($link, $row['reply_id']) . " AND rreport_by = " . $_SESSION['user_pid']);
                if (mysqli_num_rows($sresult) > 0) {
                    echo 'Report sent successfully.';
                } else {
                    mysqli_query($link, "INSERT INTO reply_reports(rreport_to, rreport_by, rreport_type, rreport_body, rreport_date) VALUES (
                        " . mysqli_real_escape_string($link, $row['reply_id']) . ",
                        " . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ",
                        " . mysqli_real_escape_string($link, $_POST['type']) . ",
                        '" . mysqli_real_escape_string($link, $_POST['body']) . "',
                        NOW())");
                        if (mysqli_error($link)) {
                            http_response_code(400);
                            echo 'There was an error with your request.';
                        } else {
                            echo 'Report sent successfully.';
                        }
                }
            }
        } else {
            http_response_code(400);
            echo 'There was an error with your request.';
        }
    } else {
        http_response_code(401);
        require_once '../inc/htm.php'; openHead();
        echo '<div class="no-content"><p>You must be signed in to do that.</p></div>';
        openFoot();
    }
} else {
    http_response_code(405);
    require_once '../inc/htm.php'; openHead();
    echo '<div class="no-content"><p>You must use a POST request to do that.</p></div>';
    openFoot();
}
