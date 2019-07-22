<?php
$title = 'Administrative Tools';
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
if(!$signed_in || $_SESSION['user_rank'] < 3) {
echo '<div class="no-content"><p>You do not have access to this resource.</p></div>';
} else {
$sql = 'SELECT * FROM users WHERE user_pid = ' . mysqli_real_escape_string($link, $_SESSION['user_pid']);
$result = mysqli_query($link, $sql);
$row = mysqli_fetch_assoc($result);
include 'user_sidebar.php';
echo '<div class="main-column"><div class="post-list-outline"><h2 class="label">Administrative Tools</h2><ul class="setting-form settings-list">
<li><p class="settings-label"><b>Ban User</b><br><form action="/admin/ban" method="post"><input type="text" name="username" placeholder="Username"><select name="length"><option value="7">One week</option><option value="14">Two weeks</option><option value="0">Permanent (use this with caution!)</option></select> <input type="submit"></form></p></li>
<li><p class="settings-label"><b>Session Administrator</b><br>Edit other users\' sessions.</p><div class="select-content"><button id="admin-1">goto here</button></div></li></ul></div>
</div>';
}
openFoot();