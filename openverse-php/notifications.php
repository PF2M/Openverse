<?php
$title = 'Notifications';
$selected = 'news';
$personal = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if($signed_in==true){
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
// Put notify class in CSS
// data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH4QMfAhIVSLsfyAAAAB1pVFh0Q29tbWVudAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAADjUlEQVQ4y2WUy26cRRCFv+ru/z4zjieO7ThBGIEIiZQs2MBuVkhIrHmFPEeeI0/BmgVgCSEkFggiFhBC7r4k8dgZzz//rbuaRSBE4UhHtaiqszuf8IYiCCDMMGxiKDFMkdcHcyIrlGcoeygQBeK/a3kjyDDDsIujwpHgOIejryxpFHqJpHXgFM+Ap8bzAM8eKqCvwyIYbmKBhCkZWZlByAkxI00cYAClHzxWOrAt3apjTgcM3CYIqEQQbuIYk5KQE/OKSiuiHYEtMSbzEq2LElDtIKyQsCSYmq6tGWg5o+c2XuKXWC6TAgWTfAT2HEbWvWXq860ds7azY5Ky1GG10pf7+6492neBORpPIJyyaJc8oQF6B1g8CeOiADPBcL6x6TvuvdnMTS5+alz6jkEyJXa6feNxXOz/2N/f20vpDGqVoghsN55DguMahjNSEi1wbtIEt23e/+wLe+njz007H/PiD0F7jElSU6xf1enu5cGko/7Pr79Kje/AtyR0XKM3LLHkZYJ1hSdMwuZH1+30g5k5tztGo3DyEI4fwPw+HN8Xs3w+ttXmzJ+/et0TJlhXkJcJS6zDYEg0wUimIR3btd0bpj+7wMMfhMNfYHEAGsBYGDoQJ+Z8dUHW3r2h899+RvqMJCYMGEeJQDQgrlVbpEmxbeoT4fguLJ5C10AMIBZwkJ5h2pVIUmy3gy3SBAfRUCKOt6RBIUYICv4fRwURsAohgkai6tuvGFZEvAQGfMS3/XJxqK5QXAm2AEn/sy3AleAK7erFYcS3DHi8BFZEg6LE1oNpM/yifn73Th/iEdVWpNyEYgPy6atZbkK1FdsQj+rje3cy/AJMS2w9itpbH2IZYcE453BNvRx6t1a40daOy0Y5LhfSCVQbMNmJXTI+WRw//o5nv36/noYDBn2JhppndPbWJwiKoUoNUSV3oqfHT45X6hpNx07KjSSWU/XJeLnsu3unz//61h/89M1WoY9MDHOMXbLwLdC/qlNJwkUKbD4is5MhyvRFzdZSyksxme5IkhdxaBsZ5vujuHq6UXGUaDzBh5eEdskBDSuGV0WfYblCSkVOUpSkocK6qjGm6nstgsFaJaSpaQrVmuBrelszNCtqWn6nZ4/gBGLcQ7nCAESkCVh6WtMUeVgULjpsKoQ+ouJppSfvW4QO6OE10+L/4biJYR1HhqPCAoYMoSMCSk2gw/OIQEN4E45/A/zIyc6snh4oAAAAAElFTkSuQmCC

echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Notifications</h2><div class="list news-list">';
$nsql = 'SELECT *, GROUP_CONCAT(DISTINCT notif_by ORDER BY notif_id ASC) AS pid_list, GROUP_CONCAT(DISTINCT notif_read) AS notifs_read FROM notifications LEFT JOIN posts ON post_id = notif_topic LEFT JOIN replies ON reply_id = notif_topic WHERE notif_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']) . ' GROUP BY notif_topic, notif_type ORDER BY notif_id DESC LIMIT 0,50';
$nresult = mysqli_query($link, $nsql);
if(mysqli_error($link)){
echo '<div class="no-content"><p>An error occurred while grabbing the notifications.</p></div>';
} elseif(mysqli_num_rows($nresult)==0) {
echo '<div class="no-content"><p>You have no notifications.</p></div>';
} else {
while($nrow = mysqli_fetch_assoc($nresult)) {
if($nrow['notif_type'] == 0) {
echo '<div class="news-list-content';
if(strpos($nrow['notifs_read'], '0') !== false) {
echo ' notify';
}
echo ' trigger" data-href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">';
include 'notifrender.php';
echo ' gave <a class="link" href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">your post (';
if(!empty($nrow['post_drawing'])) { echo 'handwritten'; } else {
if (mb_strlen($nrow['post_content'],'utf8')>18) {
echo htmlspecialchars(mb_substr($nrow['post_content'], 0, 15, 'utf8')) . '...';
} else {
echo htmlspecialchars($nrow['post_content']);
    }
}
echo ')</a> a Yeah. <span class="timestamp">' . displayTime($nrow['notif_date']) . '</span></div></div>';
}

if($nrow['notif_type'] == 1) {
echo '<div class="news-list-content';
if(strpos($nrow['notifs_read'], '0') !== false) {
echo ' notify';
}
echo ' trigger" data-href="/replies/' . htmlspecialchars($nrow['notif_topic']) . '">';
include 'notifrender.php';
echo ' gave <a class="link" href="/replies/' . htmlspecialchars($nrow['notif_topic']) . '">your reply (';
if(!empty($nrow['reply_drawing'])) { echo 'handwritten'; } else {
if (mb_strlen($nrow['reply_content'],'utf8')>18) {
echo htmlspecialchars(mb_substr($nrow['reply_content'], 0, 15, 'utf8')) . '...';
} else {
echo htmlspecialchars($nrow['reply_content']);
    }
}
echo ')</a> a Yeah. <span class="timestamp">' . displayTime($nrow['notif_date']) . '</span></div></div>';
}

if($nrow['notif_type'] == 2) {
echo '<div class="news-list-content';
if(strpos($nrow['notifs_read'], '0') !== false) {
echo ' notify';
}
echo ' trigger" data-href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">';
include 'notifrender.php';
echo ' commented on <a class="link" href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">your post (';
if(!empty($nrow['post_drawing'])) { echo 'handwritten'; } else {
if (mb_strlen($nrow['post_content'],'utf8')>18) {
echo htmlspecialchars(mb_substr($nrow['post_content'], 0, 15, 'utf8')) . '...';
} else {
echo htmlspecialchars($nrow['post_content']);
    }
}
echo ')</a>. <span class="timestamp">' . displayTime($nrow['notif_date']) . '</span></div></div>';
}


if($nrow['notif_type'] == 3) {
echo '<div class="news-list-content';
if(strpos($nrow['notifs_read'], '0') !== false) {
echo ' notify';
}
echo ' trigger" data-href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">';
include 'notifrender.php';
echo ' commented on <a class="link" href="/posts/' . htmlspecialchars($nrow['notif_topic']) . '">their post (';
if(!empty($nrow['post_drawing'])) { echo 'handwritten'; } else {
if (mb_strlen($nrow['post_content'],'utf8')>18) {
echo htmlspecialchars(mb_substr($nrow['post_content'], 0, 15, 'utf8')) . '...';
} else {
echo htmlspecialchars($nrow['post_content']);
    }
}
echo ')</a>. <span class="timestamp">' . displayTime($nrow['notif_date']) . '</span></div></div>';
}

if($nrow['notif_type'] == 4) {
echo '<div class="news-list-content';
if(strpos($nrow['notifs_read'], '0') !== false) {
echo ' notify';
}
echo ' trigger" data-href="/users/' . htmlspecialchars($nrow['user_id']) . '">';
$sports = 1;
include 'notifrender.php';
echo '. <span class="timestamp">' . displayTime($nrow['notif_date']) . '</span>';
$fsql = 'SELECT * FROM follows WHERE follow_to = ' . mysqli_real_escape_string($link, $nrow['user_pid']) . ' AND follow_by = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$fresult = mysqli_query($link, $fsql);
if(mysqli_num_rows($fresult)==0){
echo '<div class="toggle-button"><button type="button" class="button follow-button symbol" data-action="/users/' . htmlspecialchars($nrow['user_id']) . '/follow" data-track-action="follow">Follow</button><button type="button" class="button follow-done-button relationship-button symbol none" disabled>Follow</button></div>';
}
echo '</div></div>';
$sports = 0;
}

if($nrow['notif_type'] == 5) {

}

}}
echo '</div></div></div>';
mysqli_query($link, 'UPDATE notifications SET notif_read = 1 WHERE notif_to = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']));
} else {
http_response_code(403);
echo '<div class="no-content"><p>You must be signed in to view this page.</p></div>';
}
openFoot();