<?php

$title = 'Sign Up';

$auth  = true;

require_once '../inc/connect.php';

if ($_SESSION['signed_in'] == true) {

    header('Location: /');

} else {

    require_once '../inc/htm.php'; openHead();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        $error = false;

        include 'signup-form.php';

    } else if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        if (!isset($_POST['username']) || strlen($_POST['username']) == 0) {

            $error = 'The Username field cannot be blank.';

            include 'signup-form.php';

        } else if (!isset($_POST['email']) || strlen($_POST['email']) == 0) {

            $error = 'The Email field cannot be blank.';

            include 'signup-form.php';

        } else if (!isset($_POST['password']) || strlen($_POST['password']) == 0) {

            $error = 'The Password field cannot be blank.';

            include 'signup-form.php';

        } else if (!isset($_POST['confirm_password']) || strlen($_POST['confirm_password']) == 0) {

            $error = 'The Confirm Password field cannot be blank.';

            include 'signup-form.php';

        } else if (!preg_match('/^[A-Za-z0-9-._]{4,32}$/', $_POST['username'])) {

            $error = 'The username you entered contains invalid characters, or is too long or short.<br>Valid characters include letters, numbers, dashes, dots, and underscores.';

            include 'signup-form.php';

        } else if (mysqli_num_rows(mysqli_query($link, 'SELECT user_id FROM users WHERE user_id = "' . mysqli_real_escape_string($link, $_POST['username']) . '"')) !== 0) {

            $error = 'The username you entered is already taken.';

            include 'signup-form.php';

        } else if (mysqli_num_rows(mysqli_query($link, 'SELECT user_email FROM users WHERE user_email = "' . mysqli_real_escape_string($link, $_POST['email']) .'"')) !== 0) {

            $error = 'The email you entered is already registered to another account.';

            include 'signup-form.php';

        } else if (strlen($_POST['screen_name']) > 32) {

            $error = 'The screen name you entered is too long.';

            include 'signup-form.php';

        } else if (isset($_POST['user_nnid']) && strlen($_POST['user_nnid']) > 0 && !preg_match('/^[A-Za-z0-9-._]{6,16}$/', $_POST['user_nnid'])) {

            $error = 'The Nintendo Network ID you entered is invalid.';

            include 'signup-form.php';

        } else if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

            $error = 'The email address you entered is invalid.';

            include 'signup-form.php';

        } else if(strlen($_POST['password']) > 32 || strlen($_POST['confirm_password']) > 32) {

            $error = 'The password you entered is too long.';

            include 'signup-form.php';

        } else if($_POST['password'] !== $_POST['confirm_password']) {

            $error = 'The passwords you entered do not match.';

            include 'signup-form.php';

        } else {

		if(isset($open_recaptcha_secret)) {

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');

            curl_setopt($ch, CURLOPT_POST, true);

            curl_setopt($ch, CURLOPT_HEADER, true);

            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

            curl_setopt($ch, CURLOPT_POSTFIELDS, 'secret=' . $open_recaptcha_secret . '&response=' . urlencode($_POST['g-recaptcha-response']) . '&remoteip=' . urlencode($_SERVER['REMOTE_ADDR']));

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $response = curl_exec($ch);

            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

            $body = substr($response, $header_size);

            $json = json_decode($body, true);

            curl_close($ch);

            if($json['success'] == 0) {

                $error = 'The captcha was not solved correctly.';

                include 'signup-form.php';

            } } else {

				

                if(isset($_POST['user_nnid']) && strlen($_POST['user_nnid']) > 0) {

                    $dh = curl_init();

                    curl_setopt($dh, CURLOPT_URL, 'https://ariankordi.net/seth/' . urlencode($_POST['user_nnid']));

                    curl_setopt($dh, CURLOPT_RETURNTRANSFER, true);

                    $mii_hash = curl_exec($dh);

                    if(curl_error($dh) or curl_getinfo($dh, CURLINFO_RESPONSE_CODE) != '200') {

                            $error = 'That account doesn\'t exist or something.';

                        include 'signup-form.php';

                        openFoot();

                        exit();

                    }

                } else {

                    $mii_hash = '';

                    $user_nnid = '';

                }

                if(!isset($_POST['screen_name']) || empty($_POST['screen_name'])) {

                    $screen_name = $_POST['username'];

                } else {

                    $screen_name = $_POST['screen_name'];

                }

                $screen_name = str_replace('â€®','',$screen_name);

                $confirmation_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

                $sql = 'INSERT INTO users(user_name, user_id, user_pass, user_avatar, user_email, user_nnid, user_date, user_rank, user_ip, user_code, user_email_confirmed, user_profile_comment, user_country, user_birthday, user_website, user_favorite_post, user_favorite_post_type, user_relationship_visibility) VALUES (

                "' . mysqli_real_escape_string($link, $screen_name) .'",

                "' . mysqli_real_escape_string($link, $_POST['username']) . '",

                "' . mysqli_real_escape_string($link, password_hash($_POST['password'], PASSWORD_BCRYPT)) . '",

                "' . mysqli_real_escape_string($link, $mii_hash) . '",

                "' . mysqli_real_escape_string($link, $_POST['email']) . '",

                "' . mysqli_real_escape_string($link, $user_nnid) . '",

                NOW(), 0,

                "' . mysqli_real_escape_string($link, $_SERVER['REMOTE_ADDR']) . '",

                "' . mysqli_real_escape_string($link, $confirmation_code) . '", 0, "", "", NOW(), "", 0, 0, 0)';

                $result = mysqli_query($link, $sql);

                if(mysqli_error($link)) {

                    $error = 'A database error occurred while trying to create your account.<br>Error code: ' . mysqli_errno($link);

                    include 'signup-form.php';

                } else {

                    $headers[] = 'MIME-Version: 1.0';

                    $headers[] = 'Content-type: text/html; charset=utf-8';

                    $headers[] = 'From: Openverse <no-reply@openverse.pf2m.com>';

                    mail($_POST['email'], 'Confirm your Openverse account', '<html><head><title>Confirm your Openverse account</title><style>body{text-align:center}</style></head><body><img src="https://openverse.pf2m.com/assets/img/menu-logo.png" alt="Openverse"><p><i>Your Openverse account is almost complete.</i> We just need to confirm your email to finish your registration.</p><p>To confirm your email address, enter this confirmation code: <b>' . $confirmation_code . '</b></p><p>If you need help, try <a href="https://openverse-dev.pf2m.com/account/resend">resending the confirmation email</a> or <a href="https://www.pf2m.com/contact/">contacting us here</a>.</p><br><p>Thank you for using our service! :)</p><!-- nico-nico-ni --></body></html>', implode("\r\n", $headers));

                    echo '<div class="main-column center"><div class="post-list-outline"><br><img src="/assets/img/menu-logo.png"><br><p style="font-weight: bold; font-size: 20px;">Sign-up Successful!</p><p>An email has been sent to the email address you provided with instructions on how to confirm your email address. <b>But email confirmation doesn\'t actually work yet, don\'t worry about it.</b><br>You can now <a href="/account/login">log in</a> to access all of Openverse!</p><br></div></div>';

                }

                curl_close($dh);

            }

        }

    }

    openFoot();

}