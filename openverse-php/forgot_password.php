<?php
$title = 'Forgot Password?';
$auth = true;
require_once '../inc/connect.php';
require_once '../inc/htm.php'; openHead();
echo '<div class="main-column center"><div class="post-list-outline"><br><img src="/assets/img/menu-logo.png"><br><p style="font-weight: bold; font-size: 20px;">Forgot Password?</p><p>Enter your email address here and we will email you instructions on how to recover your account.</p><br><form method="post"><input type="email" class="auth-input" name="email"> <input type="submit" value="Send Email"></form><br><p>This doesn\'t actually do anything right now, sorry.</p><p>Have any questions? Contact us at <a href="https://www.pf2m.com/contact/">pf2m.com/contact</a> or DM us on Twitter at <a href="https://twitter.com/openverse_admin">@openverse_admin</a>.</p><br></div></div>';
openFoot();